<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Auth"},
     *     summary="Login do usuário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Login realizado com sucesso"),
     *     @OA\Response(response="401", description="Credenciais inválidas")
     * )
     */
    public function login(Request $request)
    {
        $user = auth()->attempt($request->only('email', 'password'));
        if (!$user) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }


        return $this->generateToken(auth()->user());
    }

    /**
     * Gera um token para o usuário autenticado
     */
    public function generateToken($user, $tokenName = 'default')
    {
        $token = $user->createToken($tokenName);
        return ['token' => $token->plainTextToken];
    }

    /**
     * Login de FTO
     * @OA\Post(
     *     path="/api/auth/login-fto",
     *     tags={"Auth"},
     *     summary="Login do usuário via FTO",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fto_id"},
     *             @OA\Property(property="fto_id", type="string")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Login FTO realizado com sucesso"),
     *     @OA\Response(response="401", description="FTO inválido")
     * )
     */
    public function loginFto(Request $request)
    {
        $user = User::where('fto_id', $request->fto_id)->first();
        if (!$user) {
            return response()->json(['message' => 'FTO inválido'], 401);
        }
        return $this->generateToken($user, $request->token_name ?? 'fto');
    }
    /**
         * Registro de novo usuário
         * @OA\Post(
         *     path="/api/auth/register",
         *     tags={"Auth"},
         *     summary="Registrar novo usuário",
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             required={"name", "email", "password"},
         *             @OA\Property(property="name", type="string"),
         *             @OA\Property(property="email", type="string", format="email"),
         *             @OA\Property(property="password", type="string")
         *         )
         *     ),
         *     @OA\Response(response="201", description="Usuário registrado com sucesso"),
         *     @OA\Response(response="400", description="Dados inválidos")
         * )
         */
        public function register(Request $request)
        {
            $data = $request->only(['name', 'email', 'password']);
            $data['password'] = bcrypt($data['password']);

            $user = \App\Models\User::create($data);

            return response()->json(['user' => $user], 201);
        }
}
