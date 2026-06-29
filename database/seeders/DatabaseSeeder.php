<?php

namespace Database\Seeders;

use App\Models\LoginLog;
use App\Models\OtpLog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            RoleSeeder::class,
        ]);

        User::factory()
            ->count(5)
            ->create()
            ->each(function (User $user, int $index) {
                $user->detail()->create([
                    'phone' => fake()->phoneNumber(),
                    'address_line1' => fake()->streetAddress(),
                    'address_line2' => fake()->secondaryAddress(),
                    'city' => fake()->city(),
                    'state' => fake()->state(),
                    'postal_code' => fake()->postcode(),
                    'country' => 'India',
                    'organization' => fake()->company(),
                    'designation' => fake()->jobTitle(),
                    'additional_info' => fake()->sentence(),
                ]);

                LoginLog::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip_address' => fake()->ipv4(),
                    'user_agent' => fake()->userAgent(),
                    'status' => $index % 3 === 0 ? 'failed' : 'success',
                    'action' => 'login_attempt',
                ]);

                OtpLog::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'otp_code' => fake()->numerify('####'),
                    'verified' => $index % 2 === 0,
                    'purpose' => 'login',
                    'ip_address' => fake()->ipv4(),
                    'user_agent' => fake()->userAgent(),
                ]);
            });

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role_id' => 8,
            'login_with_otp' => true,
            'email_verified_at' => now(),
            'password_created_at' => now(),
            ]);
    }
}
