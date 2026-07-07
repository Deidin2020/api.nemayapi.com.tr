<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRoleRequest;
use App\Http\Resources\UserSummaryResource;
use App\Models\Role;
use App\Models\User;
use App\Support\ApiError;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::query()->with(['profile', 'roles'])->orderBy('name')->get();

        return response()->json([
            'data' => UserSummaryResource::collection($users)->resolve(),
        ]);
    }

    public function storeRole(StoreUserRoleRequest $request, User $user): JsonResponse
    {
        $role = Role::query()->where('name', $request->string('role')->toString())->first();

        if (! $role) {
            return ApiError::notFound('Role not found.');
        }

        if ($user->roles()->where('roles.id', $role->id)->exists()) {
            return ApiError::conflict('User already has this role.');
        }

        $user->roles()->attach($role->id, ['created_at' => now()]);

        return response()->json([
            'user_id' => $user->id,
            'roles' => $user->fresh()->roles()->pluck('name')->values()->all(),
        ], 201);
    }

    public function destroyRole(User $user, string $role): JsonResponse
    {
        $roleModel = Role::query()->where('name', $role)->first();

        if (! $roleModel || ! $user->roles()->where('roles.id', $roleModel->id)->exists()) {
            return ApiError::notFound('Role assignment not found.');
        }

        if ($role === 'admin') {
            $adminCount = User::query()->whereHas('roles', fn ($query) => $query->where('name', 'admin'))->count();
            if ($adminCount <= 1) {
                return ApiError::businessRule('Cannot remove the last admin role.');
            }
        }

        $user->roles()->detach($roleModel->id);

        return response()->json([], 204);
    }
}
