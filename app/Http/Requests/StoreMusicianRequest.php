<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMusicianRequest extends FormRequest
{
    private const VALID_INSTRUMENTS = ['Vocals', 'Guitar', 'Bass', 'Drums', 'Keys', 'Other'];

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'instruments' => 'required|array',
            'instruments.*' => 'required|string|in:' . implode(',', self::VALID_INSTRUMENTS),
            'other' => 'nullable|string',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (!$this->has('instruments')) {
            $this->merge(['instruments' => []]);
        }
    }

    public function messages(): array
    {
        return [
            'instruments.*.in' => 'Invalid instrument selected. Valid options are:' .
                implode(',', self::VALID_INSTRUMENTS)
        ];
    }
}
