<?php

namespace App\Http\Requests\Visit;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyVisitRequest extends ApiFormRequest
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
            'category' => ['sometimes', 'nullable', Rule::in(['large_company', 'medium_company', 'small_agency', 'individual_agent'])],
            'contact_person' => ['sometimes', 'nullable', 'string', 'max:255'],
            'address' => ['sometimes', 'nullable', 'string'],
            'sales_rep_id' => ['sometimes', 'nullable', 'integer', 'exists:profiles,id'],
            'feedback' => ['sometimes', 'nullable', 'string'],
            'notes' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
