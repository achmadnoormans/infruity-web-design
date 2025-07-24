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
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('crm::dashboard.index');
    }

    public function topDistribution(Request $request)
    {
        $totalCustomer = Customer::query()
            ->leftJoin('reg_districts as B', 'customer.district', '=', 'B.id')
            ->select([
                'customer.district',
                'B.name',
                DB::raw('COUNT(*) as total'),
            ])
            ->groupBy('customer.district', 'B.name')   // hindari ONLY_FULL_GROUP_BY error
            ->limit(6)
            ->orderBy('total', 'desc')
            ->get();
        return view('crm::dashboard.top_distribution', compact('totalCustomer'));
    }

    public function topTier()
    {
        $data = Customer::query()
            ->leftJoin('vw_customer_tier', 'customer.id', '=', 'vw_customer_tier.customer_id')
            ->leftJoin('crm_tier as C', 'vw_customer_tier.tier_id', '=', 'C.id')
            ->select('customer.id', 'customer.name', 'vw_customer_tier.*', 'C.name as tier_name')
            ->limit(6)
            ->orderBy('C.level', 'desc')
            ->get();
        return view('crm::dashboard.top_tier', compact('data'));
    }

    public function tierGraphic(Request $request)
    {
        $tiers = Tier::query()->leftJoin('vw_customer_tier as B', 'crm_tier.id', '=', 'B.tier_id')
            ->select([
                'crm_tier.id',
                'crm_tier.name',
                DB::raw('COUNT(B.customer_id) as total'),
            ])
            ->groupBy('crm_tier.id', 'crm_tier.name')
            ->orderBy('total', 'asc')
            ->get();
        return response()->json([
            'status' => true,
            'data' => $tiers,
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
