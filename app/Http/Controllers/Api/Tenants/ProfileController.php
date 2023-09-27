<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Repositories\Options\UsersRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends BaseController
{
    private mixed $usersRepository;

    public function __construct()
    {
        parent::__construct();
        $this->usersRepository = app(UsersRepository::class);
    }

    final public function getAllUserTokens(Request $request): JsonResponse
    {
        $result = $request->user()->tokens->map(function ($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'token' => $token->token,
            ];
        });

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }


    final public function updateToken(int $id, Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $result = DB::table('personal_access_tokens')
            ->where('id', $id)
            ->where('tokenable_id', $userId)
            ->update(['name' => $request->get('name')]);

        return $result
            ? $this->returnResponse(['success' => true, 'result' => $result])
            : $this->returnResponse(['success' => false]);
    }

    final public function createToken(Request $request): JsonResponse
    {
        $result = $request
            ->user()
            ->createToken($request->get('name'))
            ->plainTextToken;

        return $result
            ? $this->returnResponse(['success' => true, 'result' => $result])
            : $this->returnResponse(['success' => false]);
    }


    final public function getTokenById(int $id, Request $request)
    {
        $userId = $request->user()->id;

        $result = DB::table('personal_access_tokens')
            ->where('id', $id)
            ->where('tokenable_id', $userId)
            ->first();

        return $result
            ? $this->returnResponse(['success' => true, 'result' => $result])
            : $this->returnResponse(['success' => false]);
    }

    final public function destroyTokenById(int $id, Request $request)
    {
        $userId = $request->user()->id;

        $result = DB::table('personal_access_tokens')
            ->where('id', $id)
            ->where('tokenable_id', $userId)
            ->delete();

        return $result
            ? $this->returnResponse(['success' => true, 'result' => $result])
            : $this->returnResponse(['success' => false]);
    }
}
