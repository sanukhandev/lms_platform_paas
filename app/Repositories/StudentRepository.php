<?php

namespace App\Repositories;

use App\Models\User;

class StudentRepository
{
    public function all()
    {
        return User::where('role', 'student')->get();
    }

    public function find($id)
    {
        return User::where('role', 'student')->findOrFail($id);
    }

    public function create(array $data)
    {
        $data['role'] = 'student'; // Force role as 'student'
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        return $user->update($data);
    }

    public function delete(User $user)
    {
        return $user->delete();
    }
}
