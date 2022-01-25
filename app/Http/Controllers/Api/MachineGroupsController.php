<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MachineGroups as MachineGroupsResource;
use App\Http\Resources\MachineGroup as MachineGroupResource;
use App\MachineGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MachineGroupsController extends Controller
{
    public function index()
    {
        $machineGroups = MachineGroup::all();

        return new MachineGroupsResource($machineGroups);
    }

    public function store(Request $request)
    {
        if (!$request->json('data')) {
            return abort(400);
        }

        $machineGroup = new MachineGroup;
        $machineGroup->fill($request->json('data'));
        $machineGroup->key = Str::uuid();
        $machineGroup->save();

        return new MachineGroupResource($machineGroup);
    }

    public function show(MachineGroup $machineGroup)
    {
        return new MachineGroupResource($machineGroup);
    }

    public function update(Request $request, MachineGroup $machineGroup)
    {
        if (!$request->json('data')) {
            return abort(400);
        }

        $machineGroup->update($request->json('data'));

        return new MachineGroupResource($machineGroup);
    }

    public function destroy(MachineGroup $machineGroup)
    {
        $machineGroup->delete();
        return response(null, 204);
    }
}
