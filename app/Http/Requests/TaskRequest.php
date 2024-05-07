<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $isComplete = [
            'status' => 'required|integer',
        ];

        $isUpdate = [
            'task_name' => 'required|max:200',
            'date' => 'required|date_format:Y-m-d H:i:s',
        ];

        if ($this->isChangeStatus) {
            return $isComplete;
        }

        return $isUpdate;
    }
}
