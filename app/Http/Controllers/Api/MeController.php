<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DataTablesCollection;
use App\Http\Resources\TokenResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function createToken(Request $request): array {
        $token = $request->user()->createToken($request->token_name);
        return ['token' => $token->plainTextToken];
    }

    public function listTokens(Request $request) {
        $user = $request->user();
        return new DataTablesCollection(TokenResource::collection($user->tokens));
    }
}
