<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = collect(['admin', 'project_manager', 'sales_manager', 'salesperson', 'viewer'])
            ->mapWithKeys(fn (string $role) => [$role => Role::query()->firstOrCreate(['name' => $role])]);

        Project::query()->firstOrCreate(
            ['name' => 'Tane Koru'],
            ['is_default' => true]
        );

        $adminUser = User::withTrashed()->firstOrNew(['email' => 'admin@tanekoru.com']);
        $adminUser->fill([
            'name' => 'Admin User',
            'password' => 'Admin123456!',
        ]);

        if ($adminUser->trashed()) {
            $adminUser->restore();
        }

        $adminUser->save();

        $adminProfile = Profile::withTrashed()->firstOrNew(['user_id' => $adminUser->id]);
        $adminProfile->full_name = 'Admin User';

        if ($adminProfile->trashed()) {
            $adminProfile->restore();
        }

        $adminProfile->save();

        $adminUser->roles()->syncWithoutDetaching([$roles['admin']->id]);
    }
}
