<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="ResetPasswordRequest",
 *      title="Reset Password Request",
 *      description="Request body for finalizing password reset",
 *      type="object",
 *      required={"token", "email", "password"},
 *      @OA\Property(property="token", type="string", description="The reset token received via email"),
 *      @OA\Property(property="email", type="string", format="email"),
 *      @OA\Property(property="password", type="string", format="password", example="NewPassword123")
 * )
 */
class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => [
                'required', 
                'string', 
                Password::min(8)->letters()->numbers()
            ],
        ];
    }
}