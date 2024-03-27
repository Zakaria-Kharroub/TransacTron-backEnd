<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;






class AuthController extends Controller
{


    /**
        * 
        * 
        * 
        * @OA\Info(
        * title="Mon API",
        *     version="1.0.0",
        *     description="La documentation de l'API pour mon application",
        *     @OA\Contact(
        *         email="support@monapplication.com"
        *     ),
        * )
 
        * @OA\Post(
        *     path="/api/register",
        *     tags={"auth"},
        *     summary="Register a new user",
        *     @OA\RequestBody(
        *         required=true,
        *        @OA\RequestBody(
        required=true,
        @OA\JsonContent(
            required={"name","email","password"},
            @OA\Property(property="name", type="string", format="text", example="John Doe"),
            @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
            @OA\Property(property="password", type="string", format="password", example="Passw0rd"),
        )
    ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User registration failed"),
     *             @OA\Property(property="error", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    



    public function register (Request $request) {
        $validator= Validator::make($request->all(), [
            'name' =>'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        try {
            User::create([
                'name' => $request->name,
                'email'=>$request->email,
                'password' => Hash::make($request->password),
            ]);
    
            return response()->json([
                'message' => 'User created successfully',
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User registration failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }





    



    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        try {
            if(!Auth::attempt($request->only('email','password'))){
                return response([
                    'message' => 'errror auth !'
                ], Response::HTTP_UNAUTHORIZED);
            }
    
            $user = Auth::user();
    
            $token = $user->createToken('token')->plainTextToken;
    
            return response(['authentification valid' => $token]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }





    








    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'logout success',
        ], 200);
    }
}