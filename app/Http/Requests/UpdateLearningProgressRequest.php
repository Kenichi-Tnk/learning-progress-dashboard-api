<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLearningProgressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:not_started,in_progress,completed'],
            'memo' => ['nullable', 'string'],
            'started_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => ':attributeは必須です。',
            'title.string' => ':attributeは文字列で入力してください。',
            'title.max' => ':attributeは255文字以内で入力してください。',
            'category.string' => ':attributeは文字列で入力してください。',
            'category.max' => ':attributeは255文字以内で入力してください。',
            'status.string' => ':attributeは文字列で入力してください。',
            'status.in' => ':attributeは未着手・進行中・完了のいずれかを指定してください。',
            'memo.string' => ':attributeは文字列で入力してください。',
            'started_at.date' => ':attributeは日時形式で入力してください。',
            'completed_at.date' => ':attributeは日時形式で入力してください。',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'タイトル',
            'category' => 'カテゴリ',
            'status' => '進捗ステータス',
            'memo' => 'メモ',
            'started_at' => '開始日時',
            'completed_at' => '完了日時',
        ];
    }
}
