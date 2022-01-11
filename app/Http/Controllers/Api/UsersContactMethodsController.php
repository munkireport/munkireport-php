<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\UserContactMethod;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserContactMethods;
use App\Http\Resources\UserContactMethod as UserContactMethodResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

class UsersContactMethodsController extends Controller
{
    public function index(User $user): Responsable
    {
        return new UserContactMethods($user->contactMethods()->get());
    }

    public function store(Request $request, User $user): Responsable
    {
        $contactMethod = new UserContactMethod();
        $contactMethod->fill($request->json("data"));

        $user->contactMethods()->save($contactMethod);

        return new UserContactMethodResource($contactMethod);
    }

    public function show(int $userId, UserContactMethod $contactMethod): Responsable
    {
        return new UserContactMethodResource($contactMethod);
    }

    public function update(Request $request, int $userId, UserContactMethod $contactMethod): Responsable
    {
        $contactMethod->update($request->json("data"));
        $contactMethod->saveOrFail();

        return new UserContactMethodResource($contactMethod);
    }

    public function destroy(int $userId, UserContactMethod $contactMethod)
    {
        $contactMethod->delete();
        return response(null, 204);
    }
}
