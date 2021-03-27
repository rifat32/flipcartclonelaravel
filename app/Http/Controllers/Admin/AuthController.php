<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function signIn(Request $request)
    {
        // $request->validate([
        //     'email' => ['required', 'email'],
        //     'password' => ['required']
        // ]);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    "status" => 200,
                    "token" => $user->createToken('Token Name')->accessToken,
                    "user" => [
                        "_id" => $user->id,
                        "firstName" => $user->firstName,
                        "lastName" => $user->lastName,
                        "email" => $user->email,
                        "role" => $user->role
                    ]
                ]);
            } else {
                return response()->json([
                    "status" => 400,
                    "message" => "Password does not match"
                ]);
            }
        } else {
            return response()->json([
                "status" => 400,
                "message" => "No admin is found"
            ]);
        }
    }
    public function signUp(Request $request)
    {

        if (DB::table('users')->where([
            'email' => $request->email
        ])->exists()) {
            return response()->json([
                "status" => 400,
                "message" => "Admin already registered"
            ]);
        }
        $user = new User;
        $user->firstName = $request->firstName;
        $user->lastName = $request->lastName;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->username = Str::random(15);
        $user->role = "admin";
        $success = $user->save();
        if (!$success) {
            return response()->json([
                "status" => 400,
                "message" => "Something went wrong"
            ]);
        }
        return  response()->json([
            "status" => 201,
            "message" => "User has been created successfully"
        ]);
    }
}
