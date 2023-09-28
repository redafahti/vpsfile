<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UsersControllers extends Controller
{

    /**
     * @OA\Get(
     * path="/api/v1/users/get/me",
     * operationId="UserMeShow",
     * tags={"Users"},
     * summary="Get My User Profile",
     *      @OA\Response(
     *          response=200,
     *          description="Successful Response",
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(  
     *             example={
     *                 "message": "Unauthenticated",
     *             }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404, 
     *          description="Not Found",
     *          @OA\JsonContent(
     *             example={
     *                 "message": "The route {link} could not be found.",
     *             }
     *          )
     *      ),
     * )
     */

    public function show()
    {
        if (Auth::guard('api')->check()) {

            $user = Auth::guard('api')->user();
            return response()->json([
                'user' => $user,
            ], 200);

        }
        return response()->json([
            'message' => 'Unauthenticated',
        ], 401);
    }

    /**
     * @OA\Put(
     * path="/api/v1/users/update/me",
     * operationId="UserMeUpdate",
     * tags={"Users"},
     * summary="Update My User Profil",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="password", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful Response",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(  
     *             example={
     *                 "message": "Unauthenticated",
     *             }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404, 
     *          description="Not Found",
     *          @OA\JsonContent(
     *             example={
     *                 "message": "The route {link} could not be found.",
     *             }
     *          )
     *      ),
     * )
     */

    public function update(Request $request)
    {
        if (Auth::guard('api')->check()) {

            $id = Auth::guard('api')->user()->id;
            return Response(['id' => $id], 200);

        }
        return response()->json([
            'message' => 'Failed to authenticate',
        ], 401);
    }
}
