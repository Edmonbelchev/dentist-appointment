<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Str;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // Get the first user

        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Generate 5 repeated EGNs
        $repeatedEgns = [
            '1234567890',
            '2345678901',
            '3456789012',
            '4567890123',
            '5678901234',
        ];


        for ($i = 0; $i < 50; $i++) {
            $egn = rand(1, 5) <= 5
                ? $repeatedEgns[array_rand($repeatedEgns)]
                : strval(rand(1000000000, 9999999999));

            Appointment::create([
                'user_id' => $user->id,
                'appointment_datetime' => now()->addDays(rand(1, 30))->setTime(rand(8, 17), 0),
                'client_name' => 'Пациент ' . ($i + 1),
                'egn' => $egn,
                'description' => Str::random(20),
                'notification_method' => collect(['sms', 'email'])->random(),
                'notification_method_value' => collect(['sms', 'email'])->random(),
            ]);
        }
    }
}
