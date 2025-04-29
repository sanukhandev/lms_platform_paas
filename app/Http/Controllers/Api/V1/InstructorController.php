<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInstructorRequest;
use App\Http\Requests\UpdateInstructorRequest;
use App\Http\Resources\InstructorResource;
use App\Models\User;
use App\Repositories\InstructorRepository;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    protected $instructors;

    public function __construct(InstructorRepository $instructors)
    {
        $this->instructors = $instructors;
    }

    public function index()
    {
        $instructors = $this->instructors->all();
        return InstructorResource::collection($instructors);
    }

    public function store(StoreInstructorRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt('password'); // Default password, change as needed
        $data['role'] = 'instructor'; // Set role to instructor
        $instructor = $this->instructors->create($data);
        return new InstructorResource($instructor);
    }

    public function show($id)
    {
        $instructor = $this->instructors->find($id);
        return new InstructorResource($instructor);
    }

    public function update(UpdateInstructorRequest $request, User $instructor)
    {
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $this->instructors->update($instructor, $data);
        return new InstructorResource($instructor);
    }

    public function destroy(User $instructor)
    {
        $this->instructors->delete($instructor);
        return response()->json(['message' => 'Instructor deleted successfully']);
    }
}
