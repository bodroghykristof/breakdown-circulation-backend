<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                "name" => "required",
                "email" => "required|email",
                "password" => "required|min:5",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "message" => "Bad request",
                    "validation_errors" => $validator->errors()
                ], 400);
            }

            $userData = $request->all();
            $userData["password"] = bcrypt($userData['password']);
            $user = User::query()->create($userData);

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
        } catch (ValidationException $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()], 400);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json([
                    "success" => false,
                    "message" => "Email already used"
                ], 409);
            }
            return response()->json([
                "success" => false,
                "message" => "Could not register. Server Error."
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()], 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                    "email" => "required|email",
                    "password" => "required|min:5",
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
                    "message" => "Invalid email or password"], 401);
            }

            $token = $user->createToken("token")->plainTextToken;
            return response()->json([
                "token" => $token,
                "data" => $user], 200);
        } catch (ValidationException $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()], 400);
        } catch (QueryException $e) {
            return response()->json([
                "success" => false,
                "message" => "Database error"], 500);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()], 500);
        }

    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "token" => "Token deleted successfully"
        ], 200);
    }
}
