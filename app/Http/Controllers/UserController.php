<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
/**
 * @OA\Info(title="API", version="1.0")
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 */

class UserController extends Controller
{
    /**
         * @OA\Get(
         *     path="/api/me",
         *     tags={"Users"},
         *     summary="Lista o usuário",
         *     security={{"bearerAuth":{}}},
         *     @OA\Response(response="200", description="Lista usuário")
         * )
         */
    public function me(Request $request)
    {
        return response()->json($request->user('sanctum'));
    }

}
