<?php

namespace Modules\Crm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Customer;
use Modules\Crm\Entities\Tier;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use \App\Traits\HasAccessControl;

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if ($denied = $this->requireAccess('crm-dashboard.index')) {
            return $denied;
        }

        $data['totalCustomer'] = Customer::count();
        return view('crm::dashboard.index', $data);
    }

    public function topDistribution(Request $request)
    {
        if ($denied = $this->requireAccess('crm-dashboard.top-distribution')) {
            return $denied;
        }

        // Query 5 teratas
        $top5 = DB::table('customer')
            ->leftJoin('reg_districts as B', 'customer.district', '=', 'B.id')
            ->select([
                'customer.district',
                'B.name',
                DB::raw('COUNT(*) as total')
            ])
            ->groupBy('customer.district', 'B.name')
            ->orderByDesc('total')
            ->limit(5);

        // Query total lainnya
        $others = DB::table(DB::raw('(
            SELECT `customer`.`district`, COUNT(*) as total
            FROM `customer`
            GROUP BY `customer`.`district`
            ORDER BY total DESC
            LIMIT 18446744073709551615 OFFSET 5
        ) as sub'))
            ->select([
                DB::raw("'Other' as district"),
                DB::raw("'Other' as name"),
                DB::raw('SUM(total) as total')
            ]);

        // Gabungkan dengan UNION ALL
        $totalCustomer = $top5->unionAll($others)->get();
        return view('crm::dashboard.top_distribution', compact('totalCustomer'));
    }

    public function genderDistribution()
    {
        if ($denied = $this->requireAccess('crm-dashboard.gender-distribution')) {
            return $denied;
        }

        $data = Customer::select('gender', DB::raw('COUNT(*) as total'))
            ->groupBy('gender')
            ->get();
            
        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function topTier()
    {
        if ($denied = $this->requireAccess('crm-dashboard.top-tier')) {
            return $denied;
        }

        $data = Customer::query()
            ->leftJoin('vw_customer_tier', 'customer.id', '=', 'vw_customer_tier.customer_id')
            ->leftJoin('crm_tier as C', 'vw_customer_tier.tier_id', '=', 'C.id')
            ->select('customer.id', 'customer.name', 'vw_customer_tier.*', 'C.name as tier_name')
            ->limit(6)
            ->orderBy('C.level', 'desc')
            ->orderBy('vw_customer_tier.customer_exp', 'desc')
            ->get();
        return view('crm::dashboard.top_tier', compact('data'));
    }

    public function tierGraphic(Request $request)
    {
        if ($denied = $this->requireAccess('crm-dashboard.tier-graphic')) {
            return $denied;
        }

        $tiers = Tier::query()->leftJoin('vw_customer_tier as B', 'crm_tier.id', '=', 'B.tier_id')
            ->select([
                'crm_tier.id',
                'crm_tier.name',
                DB::raw('COUNT(B.customer_id) as total'),
            ])
            ->groupBy('crm_tier.id', 'crm_tier.name')
            ->orderBy('crm_tier.level', 'asc')
            ->get();
        return response()->json([
            'status' => true,
            'data' => $tiers,
        ]);
    }

    public function customerGraphic(Request $request)
    {
        if ($denied = $this->requireAccess('crm-dashboard.customer-distribution')) {
            return $denied;
        }

        $customer = Customer::getCustomerGraph();
        return response()->json([
            'status' => true,
            'data' => $customer,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('crm::create');
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
        return view('crm::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('crm::edit');
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
        //
    }
}
