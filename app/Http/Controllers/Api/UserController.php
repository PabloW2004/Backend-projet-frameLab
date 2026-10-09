<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::create($request->all());
        return [
            'data' => $user,
            'error' => null,
            'meta' => null
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        return [
            'data' => $user->delete(),
            'error' => null,
            'meta' => null
        ];
    }

    public function login(Request $request){

        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where("email", $request->email)->first();
        
        if(!$user || !Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'email' => ['provided credentials are incorrect. '],
            ]);
        };

        $token = $user->createToken("validation_token", ['*'], now()->plus(minutes: 30))->plainTextToken;

        return [
            'data' => $token
        ];
    }

    public function checkUser(Request $request){
        return [
            'data' => $request->user()
        ];
    }
    
    public function logout(Request $request, User $user){
        $request->user()->currentAccesToken()->delete();
        return [
            'data' => 'Delete validated'
        ];
    }
}
