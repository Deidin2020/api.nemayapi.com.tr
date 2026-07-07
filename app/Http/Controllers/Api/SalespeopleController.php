<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;

class SalespeopleController extends Controller
{
    public function index(): JsonResponse
    {
        $rows = Profile::query()
            ->with('user')
            ->orderBy('full_name')
            ->get()
            ->map(fn (Profile $profile) => [
                'id' => $profile->id,
                'full_name' => $profile->full_name,
                'email' => $profile->user?->email,
            ])->values();

        return response()->json([
            'data' => $rows,
        ]);
    }
}
