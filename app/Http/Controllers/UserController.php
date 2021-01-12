<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
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
                "status" => 400,
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
                    "status" => 409,
                    "success" => false,
                    "message" => "Email already used"
                ], 409);
            }
            return response()->json([
                "status" => 500,
                "success" => false,
                "message" => "Could not register. Server Error."
            ], 500);
        }

        if (!is_null($user)) {
            return response()->json([
                "status" => 200,
                "success" => true,
                "message" => "User created successfully",
                "data" => $user], 200);
        } else {
            return response()->json([
                "status" => "failed",
                "success" => false,
                "message" => "Whoops! Failed to register. Please try again."], 400);
        }
    }
}
