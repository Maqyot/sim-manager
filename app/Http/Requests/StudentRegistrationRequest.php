<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StudentRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $existing = Student::where('student_number', $this->input('student_number'))
            ->where('email', $this->input('email'))
            ->first();

        $studentNumber = Rule::unique('students', 'student_number');
        $studentEmail = Rule::unique('students', 'email');
        if ($existing) {
            $studentNumber->ignore($existing->id);
            $studentEmail->ignore($existing->id);
        }

        return [
            'student_number' => ['required', 'string', 'max:20', $studentNumber],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', $studentEmail, Rule::unique('users', 'email')],
            'course' => ['required', 'string', 'max:100'],
            'year_level' => ['required', 'integer', 'between:1,6'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            $student = Student::where('student_number', $this->input('student_number'))->first();
            if (! $student) {
                return;
            }

            if ($student->user()->exists()) {
                $validator->errors()->add('student_number', 'A portal account already exists for this student. Contact the system administrator if you need help.');
                return;
            }

            foreach (['email', 'first_name', 'last_name', 'course', 'year_level'] as $field) {
                if ((string) $student->{$field} !== (string) $this->input($field)) {
                    $validator->errors()->add($field, 'These details do not match the existing student record. Please contact the system administrator.');
                }
            }
        }];
    }
}
