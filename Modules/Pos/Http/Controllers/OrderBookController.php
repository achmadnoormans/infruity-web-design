<?php

namespace Modules\Pos\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Pos\Entities\OrderBook;
use Modules\Pos\Entities\OrderBookDetail;
use Modules\Master\Entities\Product;
use Modules\Master\Entities\Branch;
use Modules\Master\Entities\UserBranch;
use Modules\Pos\Entities\PosModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Http;
use App\Helpers\SlackHelper;
use Illuminate\Support\Facades\Log;
use App\Services\FcmService;
use App\Models\UserDevice;


class OrderBookController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['branches'] = Branch::whereIn('id', UserBranch::getUserBranch())->get();
        return view('pos::order-book.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['alpinejs'] = true;
        $data['invoice_number'] = OrderBook::getOrderNumber();
        return view('pos::order-book.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('pos::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['alpinejs'] = true;
        $data['data'] = OrderBook::with('customer', 'details', 'details.product', 'details.product.unit')->find($id);
        $data['invoice_number'] = $data['data']->invoice_number;
        return view('pos::order-book.create', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $pos = OrderBook::findOrFail($id);
            $pos->delete();
            OrderBookDetail::where('order_book_id', $id)->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveTransaction(Request $request)
    {
        $data = $request->validate([
            'branch_id' => 'required|exists:branch,id',
            'invoice_number' => 'required',
            'customer_id' => 'nullable',
            'date' => 'required|date',
            'status' => 'nullable|in:draft,posting,process,done',
            'note' => 'required',
        ]);

        try {
            $updateAt = null;
            $userId = Auth::id();
            DB::beginTransaction();
            $cek = OrderBook::where('invoice_number', $data['invoice_number'])->first();
            if ($cek) {
                $pos = OrderBook::find($cek->id);
                OrderBookDetail::where('order_book_id', $cek->id)->delete();
                $pos->delete();
                $updateAt = date('Y-m-d H:i:s');
            }
            $orderBook = OrderBook::create([
                'branch_id' => $data['branch_id'],
                'invoice_number' => $data['invoice_number'],
                'customer_id' => $data['customer_id'],
                'date' => $data['date'],
                'status' => $data['status'],
                'note' => $data['note'],
                'created_by' => $userId,
                'updated_at' => $updateAt,
            ]);
            $products = Product::where('price', '>', 0)->select('id', 'name', 'product_unit')->get();
            $dataItem = $this->sendToAi($products, $data['note']);
            // dd($dataItem);
            Log::info('AI Response: ' . json_encode($dataItem, JSON_UNESCAPED_SLASHES));

            if (!isset($dataItem['items']) || !is_array($dataItem['items'])) {
                return response()->json(['error' => 'Respons AI tidak valid.'], 400);
            }

            foreach ($dataItem['items'] as $item) {
                if (!isset($item['product_id']) || !isset($item['quantity']))
                    continue;

                // Validasi: pastikan product_id benar-benar ada di database
                $productId = (int) $item['product_id'];
                if (!$products->contains('id', $productId)) {
                    continue; // skip jika ID tidak valid
                }

                OrderBookDetail::create([
                    'order_book_id' => $orderBook->id,
                    'product_id' => $productId,
                    'quantity' => (int) $item['quantity']
                ]);
            }
            DB::commit();
            $tokens = UserDevice::whereNotNull('fcm_token')
                ->pluck('fcm_token')
                ->unique()
                ->values()
                ->toArray();
            $fcm = new FcmService();
            $title = "Ada Pesanan Masuk 🍉";
            $body = "List Pesanan : \n" . $data['note'] . "\n\n Semangat Bekerja!";
            $fcm->sendNotification($tokens, $title, $body);
            // dd($data);
            DB::disconnect();
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'order_book_id' => $orderBook->id
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            DB::disconnect();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function order($id)
    {
        $orderBook = OrderBook::with('customer', 'details', 'details.product', 'details.product.unit', 'branch')->find($id);
        $data['order'] = $orderBook;
        $data['alpinejs'] = true;
        $data['data'] = $orderBook;
        $data['detail'] = $orderBook->details ?? [];
        $data['invoice_number'] = $orderBook->invoice_number;
        return view('pos::order-book.order', $data);
    }

    public function sendToAi($products, $note)
    {
        // 1. Validasi produk
        if ($products->isEmpty()) {
            return [
                'error' => 'Tidak ada produk di database.',
                'items' => []
            ];
        }

        // 2. Siapkan data
        $productJson = $products->toJson(JSON_UNESCAPED_UNICODE);

        // $systemPrompt = "You are an intelligent order matcher for an Indonesian grocery store.

        // AVAILABLE PRODUCTS (in valid JSON format):
        // {$productJson}

        // INSTRUCTIONS:
        // - The user will send a shopping list in informal Indonesian, possibly with typos, misspellings, or shorthand (e.g., 'strawberi', 'jruk', 'smngka').
        // - For each line:
        // • Compare the item name to the 'name' field in the JSON list.
        // • Accept close matches based on sound, spelling, or common Indonesian variations.
        //     Examples:
        //     'strawberi', 'stroberi', 'strawbery' → match 'Strawberry'
        //     'jruk', 'jerok' → match 'Jeruk'
        //     'semangkaa', 'smngka' → match 'Semangka'
        // • Ignore differences in capitalization, extra spaces, or minor character substitutions.
        // • ONLY match if the name is reasonably similar.
        // • If NO product is similar (e.g., 'kelengkeng' when not in the list), SKIP the item completely.
        // - Extract for each matched item:
        // • product_id = the 'id' from the matched product
        // • quantity = integer (default to 1 if not specified)
        // • unit = the 'unit' from the matched product (unless user explicitly states a different unit)
        // - NEVER invent a product_id.
        // - NEVER match to a product just because it exists — it must be semantically or phonetically similar.

        // OUTPUT FORMAT:
        // - Return ONLY a valid JSON object with a top-level key \"items\".
        // - Each item must be: {\"product_id\": integer, \"quantity\": integer, \"unit\": string}
        // - Example: {\"items\":[{\"product_id\":1,\"quantity\":2,\"unit\":\"Kg\"}]}

        // CRITICAL:
        // - NO explanations.
        // - NO markdown.
        // - NO text before or after the JSON.
        // - If no items match, return: {\"items\":[]}";

        $systemPrompt = "
            You are an order matcher.
            Output must be valid JSON.

            PRODUCTS (JSON):
            {$productJson}

            RULES:
            - User sends Indonesian shopping items, possibly with typos.
            - Match each line to a product name using fuzzy similarity.
            - Only match if clearly similar; otherwise skip.
            - Extract product_id, quantity (default 1), unit.
            - Never invent product_id or force-match.

            OUTPUT:
            Return ONLY valid JSON:
            {\"items\":[{\"product_id\":int,\"quantity\":int,\"unit\":\"string\"}]}

            If no match:
            {\"items\":[]}

            No explanation. No markdown. No extra text.
            ";

        // 3. Kirim ke Groq
        try {
            // HAPUS SPASI DI URL! (ini sering jadi penyebab 404)
            $url = 'https://api.groq.com/openai/v1/chat/completions';

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type'  => 'application/json',
            ])
                ->withoutVerifying()
                ->timeout(30)
                ->post($url, [
                    // 'model' => 'llama-3.3-70b-versatile',
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $note],
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.0,
                ]);

            // 4. Cek apakah request gagal (HTTP error)
            if (!$response->successful()) {
                $errorMessage = 'Groq API error: ' . $response->status();
                if ($response->body()) {
                    $errorMessage .= ' - ' . $response->body();
                }
                return [
                    'error' => $errorMessage,
                    'items' => []
                ];
            }

            // 5. Ambil konten AI
            $aiContent = $response->json()['choices'][0]['message']['content'] ?? '{}';

            // 6. Decode JSON
            $data = json_decode($aiContent, true);

            // 7. Validasi format
            if (!is_array($data) || !isset($data['items']) || !is_array($data['items'])) {
                return [
                    'error' => 'Respons AI tidak valid: format JSON salah.',
                    'items' => []
                ];
            }

            return $data; // ✅ Hanya array, tidak pernah JsonResponse

        } catch (\Exception $e) {
            return [
                'error' => 'Exception: ' . $e->getMessage(),
                'items' => []
            ];
        }
    }

    public function get_data(Request $request)
    {
        $query = OrderBook::with('customer')->whereIn('branch_id', UserBranch::getUserBranch());
        if ($request->has('status_filter') && $request->status_filter !== 'all') {
            $query = $query->where('status', $request->status_filter);
        }
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        if ($request->has('cabang_filter') && $request->cabang_filter !== 'all') {
            $query->where('branch_id', $request->cabang_filter);
        }
        $data = $query->orderBy('id', 'DESC');
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($item) {
                $warningIcon = $item->updated_at ? '<span class="text-warning mx-1" title="Ada updated_at">⚠️</span>' : '';
                $html = '<div class="d-flex align-items-center">';
                $html .= '<div class="ms-5">';
                if (isset($item->customer->name)) {
                    $html .= $warningIcon . '<a href="javascript:void(0)" class="text-gray-800 text-hover-primary fs-5 fw-bold">' . $item->customer->name . '</a>';
                } else {
                    $html .= $warningIcon . '<a href="javascript:void(0)" class="text-gray-800 text-hover-primary fs-5 fw-bold">Pelanggan Umum</a>';
                }
                if ($item->updated_at != null) {
                    $html .= '<br><span class="text-muted d-block fs-7">Di Edit : ' . $item->updated_at->format('d M Y H:i') . '</span>';
                }
                $html .= '</div>';
                $html .= '</div>';
                return $html;
            })
            ->addColumn('date', function ($item) {
                $html = '<span class="text-muted d-block fs-8">' . date('d M Y H:i', strtotime($item->created_at)) . '</span>';
                if ($item->status == 'draft') {
                    $html .= '<span class="badge badge-light-danger">Draft</span>';
                } else if ($item->status == 'done') {
                    $html .= '<span class="badge badge-light-success">Selesai</span>';
                } else if ($item->status == 'process') {
                    $html .= '<span class="badge badge-light-warning">Proses</span>';
                } else {
                    $html .= '<span class="badge badge-light-warning">' . $item->status . '</span>';
                }
                return $html;
            })
            ->editColumn('status', function ($item) {
                $html = '';
                if ($item->status == 'pending') {
                    $html .= '<span class="badge badge-light-danger">Proses</span>';
                } else if ($item->status == 'done') {
                    $html .= '<span class="badge badge-light-success">Selesai</span>';
                } else {
                    $html .= '<span class="badge badge-light-warning">' . $item->status . '</span>';
                }
                return $html;
            })
            ->addColumn('action', function ($item) {
                $html = '';
                $html .= '
                    <div class="dropstart">
                        <button class="btn btn-sm btn-light-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu p-1" style="min-width: 40px; z-index: 1050;">';
                $html .= '
                            <li>
                                <a class="dropdown-item" href="' . route('order-book.edit', $item->id) . '">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </li>';
                $html .= '
                            <li>
                                <a class="dropdown-item" href="' . route('order-book.order', $item->id) . '" tooltip="Proses Pesanan" title="Proses Pesanan">
                                    <i class="bi bi-box"></i>
                                </a>
                            </li>';
                $html .= '
                            <li>
                                <a class="dropdown-item text-primary d-flex justify-content-center" href="javascript:void(0)" onclick="deleteProduct(' . $item->id . ')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </li>';

                $html .= '
                        </ul>
                    </div>
                    ';
                return $html;
            })
            ->rawColumns(['name', 'action', 'date'])
            ->make(true);
    }
}
