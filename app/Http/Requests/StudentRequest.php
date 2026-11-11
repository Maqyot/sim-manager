<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $student = $this->route('student');
        $id = $student instanceof Student ? $student->id : $student;

        return [
            'student_number' => ['required', 'string', 'max:20', Rule::unique('students', 'student_number')->ignore($id)],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($id)],
            'course' => ['required', 'string', 'max:100'],
            'year_level' => ['required', 'integer', 'between:1,6'],
        ];
    }
}
