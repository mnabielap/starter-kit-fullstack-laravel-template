<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="LoginRequest",
 *      title="Login Request",
 *      description="Request body for user login",
 *      type="object",
 *      required={"email", "password"},
 *      @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
 *      @OA\Property(property="password", type="string", format="password", example="Password123")
 * )
 */
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}