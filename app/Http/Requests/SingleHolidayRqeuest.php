<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SingleHolidayRqeuest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        if ($this->has('single_holiday')) {
            return [
                'single_date' => 'required|date',
                'single_comment' => 'required|string',
            ];
        }

        if ($this->has('multiple_holidays')) {
            return [
                'st_date' => 'required|date',
                'ed_date' => 'required|date|after_or_equal:st_date',
                'day_of_week' => 'required|integer|min:1|max:7',
                'single_comment' => 'nullable|string',
            ];
        }

        return [];
    }
}
