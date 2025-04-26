<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\User;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $students;

    public function __construct(StudentRepository $students)
    {
        $this->students = $students;
    }

    public function index()
    {
        $students = $this->students->all();
        return StudentResource::collection($students);
    }

    public function store(StoreStudentRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $student = $this->students->create($data);
        return new StudentResource($student);
    }

    public function show($id)
    {
        $student = $this->students->find($id);
        return new StudentResource($student);
    }

    public function update(UpdateStudentRequest $request, User $student)
    {
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $this->students->update($student, $data);
        return new StudentResource($student);
    }

    public function destroy(User $student)
    {
        $this->students->delete($student);
        return response()->json(['message' => 'Student deleted successfully']);
    }
}
