<?php

namespace App\Repositories;

use App\Models\User;

class InstructorRepository
{
    public function all()
    {
        return User::where('role', 'instructor')->get();
    }

    public function find($id)
    {
        return User::where('role', 'instructor')->findOrFail($id);
    }

    public function create(array $data)
    {
        $data['role'] = 'instructor';
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
