<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            ]);
        }

        $userData = $request->all();
        $userData["password"] = bcrypt($userData['password']);
        $user = User::query()->create($userData);

        if (!is_null($user)) {
            return response()->json([
                "status" => 200,
                "success" => true,
                "message" => "User created successfully",
                "data" => $user]);
        } else {
            return response()->json([
                "status" => "failed",
                "success" => false,
                "message" => "Whoops! Failed to register. Please try again."]);
        }
    }
}
