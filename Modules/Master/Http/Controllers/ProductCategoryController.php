<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\ProductCategory;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Facades\Excel;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('master::category.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('master::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Simpan data ke database
        try {
            DB::beginTransaction();
            $category = new ProductCategory();
            $category->name = $validated['name'];
            $category->description = $validated['description'] ?? null;
            $category->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Kategori gagal disimpan.',
                'data' => $category
            ], 404);
        }

        // Kirim response JSON
        return response()->json([
            'message' => 'Kategori berhasil disimpan.',
            'data' => $category
        ], 201);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('master::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();
            $category = ProductCategory::findOrFail($id);
            $category->name = $validated['name'];
            $category->description = $validated['description'] ?? null;
            $category->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Category updated failed']);
        }

        return response()->json(['message' => 'Category updated successfully']);
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
            $category = ProductCategory::findOrFail($id);
            $category->delete();
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

    public function get_data(Request $request)
    {
        $data = ProductCategory::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($category) {
                return '
                    <div class="dropdown text-end">
                        <button class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary dropdown-toggle" 
                            type="button" 
                            id="dropdownMenuButton' . $category->id . '" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                            Actions
                            <i class="ki-outline ki-down fs-5 ms-1"></i>
                        </button>
            
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' . $category->id . '">
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="editProduct(' . $category->id . ')">
                                    Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteProduct(' . $category->id . ')">
                                    Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                ';
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
    }

    public function excel()
    {
        $category = ProductCategory::all();

        $dataExport = [];

        // Konversi data menjadi array
        foreach ($category as $key => $value) {
            $dataExport[] = [
                'no' => $key + 1,
                'name' => $value->name,
                'description' => $value->description
            ];
        }

        // Header untuk data
        $headings = [
            'No',
            'Name',
            'Description'
        ];

        // Gunakan anonymous class untuk ekspor Excel
        return Excel::download(new class ($dataExport, $headings) implements FromArray, WithHeadings, WithEvents {
            private $data;
            private $headings;

            public function __construct(array $data, array $headings)
            {
                $this->data = $data;
                $this->headings = $headings;
            }

            public function array(): array
            {
                return $this->data;
            }

            public function headings(): array
            {
                return $this->headings;
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        $sheet = $event->sheet->getDelegate();

                        // Styling header
                        $sheet->getStyle('A1:C1')->applyFromArray([
                            'font' => [
                                'bold' => true,
                            ],
                        ]);

                        // Tambahkan filter untuk seluruh kolom
                        $highestColumn = $sheet->getHighestColumn();
                        $sheet->setAutoFilter("A1:{$highestColumn}1");

                        foreach (range('A', $highestColumn) as $column) {
                            $sheet->getColumnDimension($column)->setAutoSize(true);
                        }
                    },
                ];
            }
        }, 'category.xlsx');
    }
}
