<?php

namespace App\Http\Requests\Visit;

use App\Http\Requests\ApiFormRequest;

class UpdateProjectVisitRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'agency_id' => ['sometimes', 'integer', 'exists:agencies_companies,id'],
            'visit_date' => ['sometimes', 'date'],
            'contact_person' => ['sometimes', 'nullable', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'sales_rep_id' => ['sometimes', 'nullable', 'integer', 'exists:profiles,id'],
            'feedback' => ['sometimes', 'nullable', 'string'],
            'notes' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
