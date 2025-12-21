<?php

namespace App\Services;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;

class AuthService
{
    /**
     * Handle Login & Token Generation
     */
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Incorrect email or password.'],
            ]);
        }

        return $this->generateAuthTokens($user);
    }

    /**
     * Generate Access (Short-lived) and Refresh (Long-lived) Tokens
     */
    public function generateAuthTokens(User $user): array
    {
        // 1. Create Access Token (Expires in 30 minutes)
        $accessTokenExpiration = now()->addMinutes(30);
        $accessToken = $user->createToken('access_token', ['access'], $accessTokenExpiration);

        // 2. Create Refresh Token (Expires in 30 days)
        $refreshTokenExpiration = now()->addDays(30);
        $refreshToken = $user->createToken('refresh_token', ['refresh'], $refreshTokenExpiration);

        return [
            'user' => $user,
            'tokens' => [
                'access' => [
                    'token' => $accessToken->plainTextToken,
                    'expires' => $accessTokenExpiration->toIso8601String(),
                ],
                'refresh' => [
                    'token' => $refreshToken->plainTextToken,
                    'expires' => $refreshTokenExpiration->toIso8601String(),
                ]
            ]
        ];
    }

    /**
     * Logout: Revoke the token currently used to authenticate the request
     */
    public function logout(User $user): void
    {
        $token = $user->currentAccessToken();
        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }
    }

    /**
     * Refresh Auth: Validate Refresh Token string and generate new pair
     */
    public function refreshAuth(string $plainRefreshToken): array
    {
        // 1. Find Token in DB
        $tokenModel = PersonalAccessToken::findToken($plainRefreshToken);

        // 2. Validate Token exists, is correct type, and not expired
        if (!$tokenModel || 
            !$tokenModel->can('refresh') || 
            ($tokenModel->expires_at && $tokenModel->expires_at->isPast())) {
            
            throw ValidationException::withMessages([
                'refreshToken' => ['Invalid or expired refresh token.'],
            ]);
        }

        $user = $tokenModel->tokenable;

        if (!$user) {
             throw ValidationException::withMessages([
                'refreshToken' => ['User not found.'],
            ]);
        }

        // 3. Token Rotation: Delete the used refresh token
        $tokenModel->delete();

        // 4. Generate new pair
        return $this->generateAuthTokens($user);
    }
}