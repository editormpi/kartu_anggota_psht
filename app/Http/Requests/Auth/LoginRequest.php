<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nik' => ['required', 'string', 'regex:/^\d{16}$/'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'nik.required' => 'NIK wajib diisi',
            'nik.regex' => 'NIK harus 16 digit angka',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('nik')) {
            $this->merge([
                'nik' => preg_replace('/\D/', '', (string) $this->input('nik')),
            ]);
        }
    }
}
