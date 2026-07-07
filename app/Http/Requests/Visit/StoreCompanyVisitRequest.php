<?php

namespace App\Http\Requests\Visit;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyVisitRequest extends ApiFormRequest
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
            'category' => ['nullable', Rule::in(['large_company', 'medium_company', 'small_agency', 'individual_agent'])],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'sales_rep_id' => ['nullable', 'integer', 'exists:profiles,id'],
            'feedback' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
