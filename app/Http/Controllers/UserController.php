<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email",
            "password" => "required|min:5"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Bad request",
                "validation_errors" => $validator->errors()
            ], 400);
        }

        $userData = $request->all();
        $userData["password"] = bcrypt($userData['password']);

        try {
            $user = User::query()->create($userData);
        }
        catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062){
                return response()->json([
                    "success" => false,
                    "message" => "Email already used"
                ], 409);
            }
            return response()->json([
                "success" => false,
                "message" => "Could not register. Server Error."
            ], 500);
        }

        if (!is_null($user)) {
            return response()->json([
                "success" => true,
                "message" => "User created successfully",
                "data" => $user], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Whoops! Failed to register. Please try again."], 400);
        }
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(),
            [
                "email" => "required|email",
                "password" => "required|min:5"
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                "validation_errors" => $validator->errors()
            ], 400);
        }

        $user = User::query()->where("email", $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "success" => false,
                "message" => "Whoops! Invalid email or password"], 401);
        }

        $token = $user->createToken("token")->plainTextToken;

        return response()->json([
            "token" => $token,
            "data" => $user
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
//        Auth::user()->tokens()->where("tokenable_id", $request->user()->id)->delete();
        return response()->json([
            "token" => "Token deleted successfully"
        ], 200);
    }


}
