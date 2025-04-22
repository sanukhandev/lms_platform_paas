<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'make:admin-user';
    protected $description = 'Create a new admin user';

    public function handle()
    {
        $email = $this->ask('Enter admin email');
        $name = $this->ask('Enter admin name');
        $password = $this->secret('Enter admin password');

        $admin = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        $this->info("Admin user created with ID: {$admin->id}");
    }
}
