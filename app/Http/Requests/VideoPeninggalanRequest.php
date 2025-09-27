<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoPeninggalanRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
        ];

        // Make both video and thumbnail required for create, optional for update
        if ($this->isMethod('post')) {
            $rules['link'] = ['required', 'file', 'mimes:mp4,mov,avi,wmv,flv,webm', 'max:102400']; // Max 100MB
            $rules['thumbnail'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];
        } else {
            $rules['link'] = ['nullable', 'file', 'mimes:mp4,mov,avi,wmv,flv,webm', 'max:102400']; // Max 100MB
            $rules['thumbnail'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];
        }

        return $rules;
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul video harus diisi.',
            'deskripsi.required' => 'Deskripsi video harus diisi.',
            'link.required' => 'File video harus diupload.',
            'link.file' => 'Link harus berupa file.',
            'link.mimes' => 'File video harus berformat MP4, MOV, AVI, WMV, FLV, atau WEBM.',
            'link.max' => 'Ukuran file video maksimal 100MB.',
            'thumbnail.required' => 'Thumbnail harus diupload.',
            'thumbnail.image' => 'File thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Thumbnail harus berformat JPEG, PNG, JPG, atau GIF.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 2MB.',
        ];
    }
}
