<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="RefreshTokenRequest",
 *      title="Refresh Token Request",
 *      description="Request body for refreshing access token",
 *      type="object",
 *      required={"refreshToken"},
 *      @OA\Property(property="refreshToken", type="string", description="The valid refresh token string")
 * )
 */
class RefreshTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'refreshToken' => ['required', 'string'],
        ];
    }
}