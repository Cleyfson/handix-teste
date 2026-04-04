<?php

namespace App\Http\Controllers;

use App\Application\UseCases\Auth\LoginUserUseCase;
use App\Application\UseCases\Auth\RegisterUserUseCase;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterUserUseCase $registerUser,
        private readonly LoginUserUseCase $loginUser,
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $payload = $this->registerUser->execute(
            $request->validated('name'),
            $request->validated('email'),
            $request->validated('password'),
        );

        return response()->json($payload, 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $payload = $this->loginUser->execute(
            $request->validated('email'),
            $request->validated('password'),
        );

        return response()->json($payload);
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }

    public function me(): JsonResponse
    {
        return response()->json(auth('api')->user());
    }
}
