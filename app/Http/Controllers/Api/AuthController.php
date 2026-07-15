<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\Support\ApiError;
use App\Support\ApiTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        private readonly ApiTokenService $tokens,
    ) {}

    public function register(): JsonResponse
    {
        return ApiError::make(
            403,
            'REGISTRATION_DISABLED',
            'Public registration is disabled. Contact an administrator.'
        )->toResponse(request());
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::query()
            ->with(['profile.user', 'roles'])
            ->where('email', $request->string('email')->toString())
            ->first();

        if (! $user || ! $user->is_active || ! Hash::check($request->string('password')->toString(), $user->password)) {
            return ApiError::unauthorized('Invalid credentials.');
        }

        return response()->json([
            'access_token' => $this->tokens->issue($user),
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $payload = $request->attributes->get('api_token_payload');

        if (is_array($payload)) {
            $this->tokens->revoke($payload);
        }

        return response()->json([], 204);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user()->loadMissing(['profile.user', 'roles']);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
            ],
            'profile' => $user->profile ? (new ProfileResource($user->profile->loadMissing('user')))->resolve() : null,
            'roles' => $user->roles->pluck('name')->values()->all(),
        ]);
    }
}
