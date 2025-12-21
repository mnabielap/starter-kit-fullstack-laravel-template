<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="StoreUserRequest",
 *      title="Store User Request",
 *      description="Request body for creating a new user",
 *      type="object",
 *      required={"name", "email", "password", "role"},
 *      @OA\Property(property="name", type="string", example="John Doe"),
 *      @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *      @OA\Property(property="password", type="string", format="password", example="Password123", description="Min 8 chars, 1 letter, 1 number"),
 *      @OA\Property(property="role", type="string", enum={"user", "admin"}, example="user")
 * )
 */
class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => [
                'required', 
                'string', 
                Password::min(8)->letters()->numbers()
            ],
            'role' => ['required', 'in:user,admin'],
        ];
    }
}