<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Post(
     *     path="/v1/users",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     summary="Create a user",
     *     description="Create a new user (Admin only)",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreUserRequest")
     *     ),
     *     @OA\Response(
     *         response=201, 
     *         description="Created",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validation Error")
     * )
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $user = $this->userService->createUser($request->validated());
        
        return response()->json(new UserResource($user), 201);
    }

    /**
     * @OA\Get(
     *     path="/v1/users",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     summary="Get all users",
     *     description="Retrieve a paginated list of users (Admin only)",
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="limit", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="role", in="query", @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200, 
     *         description="List of users",
     *         @OA\JsonContent(
     *             @OA\Property(property="results", type="array", @OA\Items(ref="#/components/schemas/UserResource")),
     *             @OA\Property(property="page", type="integer"),
     *             @OA\Property(property="limit", type="integer"),
     *             @OA\Property(property="totalPages", type="integer"),
     *             @OA\Property(property="totalResults", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $filters = $request->only(['limit', 'page', 'sortBy', 'search', 'role', 'scope']);
        
        $result = $this->userService->queryUsers($filters);

        // Manually constructing response to match the specific project requirement structure
        // utilizing UserResource::collection to transform the items inside 'results'
        return response()->json([
            'results' => UserResource::collection($result->items()), 
            'page' => $result->currentPage(),
            'limit' => $result->perPage(),
            'totalPages' => $result->lastPage(),
            'totalResults' => $result->total(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/v1/users/{id}",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     summary="Get single user",
     *     description="Retrieve details of a specific user",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200, 
     *         description="User details",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function show(Request $request, User $user): JsonResponse
    {
        $this->authorize('view', $user);
        
        return response()->json(new UserResource($user));
    }

    /**
     * @OA\Patch(
     *     path="/v1/users/{id}",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     summary="Update user",
     *     description="Update user details",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest")
     *     ),
     *     @OA\Response(
     *         response=200, 
     *         description="Updated",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validation Error")
     * )
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $updatedUser = $this->userService->updateUser($user, $request->validated());
        
        return response()->json(new UserResource($updatedUser));
    }

    /**
     * @OA\Delete(
     *     path="/v1/users/{id}",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     summary="Delete user",
     *     description="Delete a user permanently (Admin only)",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Deleted"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $this->userService->deleteUser($user);
        
        return response()->json(null, 204);
    }
}