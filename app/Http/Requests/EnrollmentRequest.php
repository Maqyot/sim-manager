<?php

namespace App\Http\Requests;

use App\Models\Enrollment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $enrollment = $this->route('enrollment');
        $id = $enrollment instanceof Enrollment ? $enrollment->id : $enrollment;

        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'subject_id' => [
                'required', 'integer', 'exists:subjects,id',
                Rule::unique('enrollments')->where(fn ($q) => $q
                    ->where('student_id', $this->input('student_id'))
                    ->where('school_year', $this->input('school_year'))
                    ->where('semester', $this->input('semester')))->ignore($id),
            ],
            'school_year' => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'semester' => ['required', Rule::in(Enrollment::SEMESTERS)],
            'status' => ['required', Rule::in(Enrollment::STATUSES)],
            'grade' => ['nullable', 'numeric', 'between:1,5'],
        ];
    }

    public function messages(): array
    {
        return [
            'subject_id.unique' => 'This student is already enrolled in that subject for the selected term.',
            'school_year.regex' => 'School year must look like 2026-2027.',
        ];
    }
}
