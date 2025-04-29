<?php

namespace App\Repositories;

use App\Models\ClassSession;

class ClassSessionRepository
{
    // 🔹 ClassSession CRUD

    public function all()
    {
        return ClassSession::all();
    }

    public function store(array $data)
    {
        return ClassSession::create($data);
    }

    public function update(ClassSession $classSession, array $data)
    {
        $classSession->update($data);
        return $classSession;
    }

    public function destroy(ClassSession $classSession)
    {
        return $classSession->delete();
    }

}