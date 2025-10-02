<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RangeStatsRequest extends FormRequest
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
            'from' => 'nullable|date',
            'to'   => 'nullable|date|after_or_equal:from',
            'limit' => 'nullable|integer|min:1|max:100',
        ];
    }

    public function range(): array
    {
        $from = $this->query('from') ? \Carbon\Carbon::parse($this->query('from'))->startOfDay() : now()->subDays(30)->startOfDay();
        $to   = $this->query('to')   ? \Carbon\Carbon::parse($this->query('to'))->endOfDay()   : now()->endOfDay();

        $limit = (int) $this->query('limit', 10);

        return [$from, $to, $limit];
    }
}
