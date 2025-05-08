<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()?->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $categoryId = $this->route('course_category');
        
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => [
                'nullable',
                'exists:course_categories,id',
                Rule::notIn([$categoryId]), // Prevent self-reference
            ],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('parent_id') && $this->parent_id) {
                $this->validateNoCyclicReference($validator);
            }
        });
    }

    /**
     * Validate that the parent_id does not create a cyclic reference.
     */
    protected function validateNoCyclicReference($validator)
    {
        $categoryId = $this->route('course_category');
        $parentId = $this->parent_id;
        
        // Check if any descendant of this category is being set as its parent
        $currentParentId = $parentId;
        $visited = [];
        
        while ($currentParentId) {
            if ($currentParentId == $categoryId) {
                $validator->errors()->add(
                    'parent_id', 
                    'Cannot set a descendant as the parent (cyclic reference).'
                );
                break;
            }
            
            // Prevent infinite loop
            if (in_array($currentParentId, $visited)) {
                break;
            }
            
            $visited[] = $currentParentId;
            
            // Get the parent of the current parent
            $parent = \App\Models\CourseCategory::find($currentParentId);
            $currentParentId = $parent ? $parent->parent_id : null;
        }
    }
}

