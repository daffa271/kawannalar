<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Alasan penolakan (mentor, modul, booking). Error disimpan di bag "moderation"
 * agar tidak bercampur dengan error form lain di halaman yang sama.
 */
class RejectionReasonRequest extends FormRequest
{
    protected $errorBag = 'moderation';

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'reason.required' => 'Alasan penolakan wajib diisi.',
            'reason.max' => 'Alasan penolakan maksimal 500 karakter.',
        ];
    }
}
