<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="UpdateUserRequest",
 *      title="Update User Request",
 *      description="Request body for updating user details",
 *      type="object",
 *      @OA\Property(property="name", type="string", example="John Doe Updated"),
 *      @OA\Property(property="email", type="string", format="email", example="john_updated@example.com"),
 *      @OA\Property(property="password", type="string", format="password", example="NewPassword123"),
 *      @OA\Property(property="role", type="string", enum={"user", "admin"}, example="admin")
 * )
 */
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'sometimes', 
                'email', 
                Rule::unique('users')->ignore($userId)
            ],
            'password' => [
                'nullable', 
                'string', 
                Password::min(8)->letters()->numbers()
            ],
            'role' => ['sometimes', 'in:user,admin'],
        ];
    }
}