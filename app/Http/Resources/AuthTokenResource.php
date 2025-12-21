<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="AuthTokenResource",
 *      title="Auth Token Resource",
 *      description="Token structure only",
 *      type="object",
 *      @OA\Property(
 *          property="access",
 *          type="object",
 *          @OA\Property(property="token", type="string"),
 *          @OA\Property(property="expires", type="string", format="date-time")
 *      ),
 *      @OA\Property(
 *          property="refresh",
 *          type="object",
 *          @OA\Property(property="token", type="string"),
 *          @OA\Property(property="expires", type="string", format="date-time")
 *      )
 * )
 */
class AuthTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'access' => [
                'token' => $this['access']['token'],
                'expires' => $this['access']['expires'], 
            ],
            'refresh' => [
                'token' => $this['refresh']['token'],
                'expires' => $this['refresh']['expires'],
            ],
        ];
    }
}