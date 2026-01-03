<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\AuthTokenResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\UserService;
use App\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    protected $authService;
    protected $userService;

    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    /**
     * @OA\Post(
     *     path="/v1/auth/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     description="Creates a new user and returns access/refresh tokens",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=201, 
     *         description="Registered successfully",
     *         @OA\JsonContent(ref="#/components/schemas/AuthTokenResource")
     *     ),
     *     @OA\Response(response=422, description="Validation Error")
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $validatedData['role'] = Role::USER;

        $user = $this->userService->createUser($validatedData);
        $data = $this->authService->generateAuthTokens($user);
        
        return response()->json([
            'user' => new UserResource($user),
            'tokens' => new AuthTokenResource($data['tokens'])
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/v1/auth/login",
     *     tags={"Auth"},
     *     summary="Login user",
     *     description="Authenticates user and returns access/refresh tokens",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200, 
     *         description="Login successful",
     *         @OA\JsonContent(ref="#/components/schemas/AuthTokenResource")
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials"),
     *     @OA\Response(response=422, description="Validation Error")
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->authService->login($request->email, $request->password);
        
        return response()->json([
            'user' => new UserResource($data['user']),
            'tokens' => new AuthTokenResource($data['tokens'])
        ]);
    }

    /**
     * @OA\Post(
     *     path="/v1/auth/logout",
     *     tags={"Auth"},
     *     security={{"sanctum":{}}},
     *     summary="Logout user",
     *     description="Revokes the Access Token used in Authorization header",
     *     @OA\Response(response=204, description="Logged out")
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());
        
        return response()->json(null, 204);
    }

    /**
     * @OA\Post(
     *     path="/v1/auth/refresh-tokens",
     *     tags={"Auth"},
     *     summary="Refresh auth tokens",
     *     description="Send a valid refresh token string to get a new access/refresh pair",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RefreshTokenRequest")
     *     ),
     *     @OA\Response(
     *         response=200, 
     *         description="Tokens refreshed",
     *         @OA\JsonContent(ref="#/components/schemas/AuthTokenResource")
     *     ),
     *     @OA\Response(response=401, description="Invalid or expired token"),
     *     @OA\Response(response=422, description="Validation Error")
     * )
     */
    public function refreshTokens(RefreshTokenRequest $request): JsonResponse
    {
        try {
            // Service expects a string, validator ensures 'refreshToken' exists
            $data = $this->authService->refreshAuth($request->validated('refreshToken'));
            
            return response()->json(new AuthTokenResource($data));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    /**
     * @OA\Post(
     *     path="/v1/auth/forgot-password",
     *     tags={"Auth"},
     *     summary="Request password reset link",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ForgotPasswordRequest")
     *     ),
     *     @OA\Response(response=200, description="Reset link sent"),
     *     @OA\Response(response=422, description="Validation Error")
     * )
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        // Logic handled by request validation. 
        // In a real app, call AuthService::sendResetLink($request->email) here.
        
        return response()->json(['message' => 'If the email exists, a reset link has been sent.'], 200);
    }

    /**
     * @OA\Post(
     *     path="/v1/auth/reset-password",
     *     tags={"Auth"},
     *     summary="Reset password",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ResetPasswordRequest")
     *     ),
     *     @OA\Response(response=200, description="Password reset successful"),
     *     @OA\Response(response=422, description="Validation Error")
     * )
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        // Logic handled by request validation.
        // In a real app, call AuthService::resetPassword(...) here.
        
        return response()->json(['message' => 'Password reset functionality placeholder'], 200);
    }
}