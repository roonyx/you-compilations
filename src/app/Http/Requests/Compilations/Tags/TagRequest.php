<?php

declare(strict_types=1);

namespace App\Http\Requests\Compilations\Tags;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class TagRequest
 * @package App\Http\Requests\Compilations\Tags
 */
class TagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'names' => ['array'],
            'names.*' => ['string']
        ];
    }
}
