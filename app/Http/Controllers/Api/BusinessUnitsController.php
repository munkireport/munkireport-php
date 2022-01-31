<?php

namespace App\Http\Controllers\Api;

use App\BusinessUnit;
use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessUnits;
use App\Http\Resources\BusinessUnit as BusinessUnitResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BusinessUnitsController extends Controller
{
    public function index()
    {
        $businessUnits = BusinessUnit::all();

        return new BusinessUnits($businessUnits);
    }

//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
//     */
//    public function store(Request $request)
//    {
//        //
//    }

    public function show(BusinessUnit $businessUnit)
    {
        return new BusinessUnitResource($businessUnit);
    }

//    /**
//     * Update the specified resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  \App\BusinessUnit  $businessUnit
//     * @return \Illuminate\Http\Response
//     */
//    public function update(Request $request, BusinessUnit $businessUnit)
//    {
//
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\BusinessUnit $businessUnit
     * @return Response
     */
    public function destroy(BusinessUnit $businessUnit): Response
    {
        $businessUnit->delete();
        return response(null, 204);
    }
}
