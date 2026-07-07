<?php

namespace App\Http\Requests\Visit;

use App\Http\Requests\ApiFormRequest;

class StoreProjectVisitRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'agency_id' => ['required', 'integer', 'exists:agencies_companies,id'],
            'visit_date' => ['required', 'date'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'sales_rep_id' => ['nullable', 'integer', 'exists:profiles,id'],
            'feedback' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
