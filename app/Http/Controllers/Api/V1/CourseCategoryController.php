<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\CourseCategory;
use App\Services\CourseCategoryService;
use Illuminate\Support\Facades\Log;

class CourseCategoryController extends Controller
{
    public function __construct(protected CourseCategoryService $service) {}

    public function index()
    {
        return response()->json([
            'data' => $this->service->list(),
            'message' => 'Categories retrieved successfully'
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->service->create($request->validated());
        return response()->json([
            'data' => $category,
            'message' => 'Category created successfully'
        ], 201);
    }
    
    public function show($id)
    {
        $category = $this->service->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json([
            'data' => $category,
            'message' => 'Category retrieved successfully'
        ]);
    }

    public function update(UpdateCategoryRequest $request, CourseCategory $courseCategory)
    {
        $updated = $this->service->update($courseCategory, $request->validated());
        return response()->json([
            'data' => $updated,
            'message' => 'Category updated successfully'
        ]);
    }

    public function destroy(CourseCategory $courseCategory)
    {
        $this->service->delete($courseCategory);
        return response()->json(['message' => 'Category deleted successfully']);
    }
}

