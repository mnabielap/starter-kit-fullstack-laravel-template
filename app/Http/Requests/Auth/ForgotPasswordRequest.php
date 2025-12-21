<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="ForgotPasswordRequest",
 *      title="Forgot Password Request",
 *      description="Request body for initiating password reset",
 *      type="object",
 *      required={"email"},
 *      @OA\Property(property="email", type="string", format="email", example="user@example.com")
 * )
 */
class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }
}