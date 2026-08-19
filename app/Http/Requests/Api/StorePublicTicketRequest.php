<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePublicTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ruangan_id'     => ['required', 'integer', 'exists:ruangan,id'],
            'jenis'          => ['required', 'in:umum,it'],
            'deskripsi'      => ['required', 'string', 'min:10', 'max:1000'],
            'pelapor_nama'   => ['nullable', 'string', 'max:100'],
            'pelapor_kontak' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'ruangan_id.required' => 'Silakan pilih ruangan terlebih dahulu.',
            'ruangan_id.exists'   => 'Ruangan yang dipilih tidak valid.',
            'jenis.required'      => 'Silakan pilih jenis kerusakan (umum atau it).',
            'jenis.in'            => 'Jenis kerusakan harus bernilai umum atau it.',
            'deskripsi.required'  => 'Deskripsi kerusakan wajib diisi.',
            'deskripsi.min'       => 'Deskripsi minimal 10 karakter.',
            'deskripsi.max'       => 'Deskripsi maksimal 1000 karakter.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validasi gagal.',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
