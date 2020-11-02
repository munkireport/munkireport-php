<?php

namespace App\Http\Controllers\Api;

use App\BusinessUnit;
use App\Http\Controllers\Controller;
use App\Http\Resources\BusinessUnits;
use App\Http\Resources\BusinessUnit as BusinessUnitResource;
use Illuminate\Http\Request;

class BusinessUnitsController extends Controller
{
    public function index()
    {
        $businessUnits = BusinessUnit::all();

        return new BusinessUnits($businessUnits);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function show(BusinessUnit $businessUnit)
    {
        return new BusinessUnitResource($businessUnit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BusinessUnit  $businessUnit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BusinessUnit $businessUnit)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BusinessUnit  $businessUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy(BusinessUnit $businessUnit)
    {
        $businessUnit->delete();
        return abort(204);
    }
}
