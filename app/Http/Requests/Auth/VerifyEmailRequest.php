<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="VerifyEmailRequest",
 *      title="Verify Email Request",
 *      description="Request body for verifying user email address",
 *      type="object",
 *      required={"token"},
 *      @OA\Property(property="token", type="string", description="Verification token from email")
 * )
 */
class VerifyEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
        ];
    }
}