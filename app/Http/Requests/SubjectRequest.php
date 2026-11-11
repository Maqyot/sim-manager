<?php

namespace App\Http\Requests;

use App\Models\Subject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'meeting_days' => $this->input('meeting_days') ?: null,
            'start_time' => $this->input('start_time') ?: null,
            'end_time' => $this->input('end_time') ?: null,
        ]);
    }

    public function rules(): array
    {
        $subject = $this->route('subject');
        $id = $subject instanceof Subject ? $subject->id : $subject;

        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('subjects', 'code')->ignore($id)],
            'name' => ['required', 'string', 'max:150'],
            'units' => ['required', 'integer', 'between:1,6'],
            'meeting_days' => ['nullable', 'array', 'required_with:start_time,end_time'],
            'meeting_days.*' => ['string', Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])],
            'start_time' => ['nullable', 'required_with:meeting_days,end_time', 'date_format:H:i'],
            'end_time' => ['nullable', 'required_with:meeting_days,start_time', 'date_format:H:i', 'after:start_time'],
        ];
    }
}
