<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ChallengeController extends Controller
{
    /**
     * Récupère tous les challenges.
     */
    public function index()
    {
        $challenges = Challenge::orderBy('starting_date', 'desc')->get();

        return response()->json([
            'data' => $challenges,
            'error' => null,
            'meta' => null
        ], 200);
    }


    /**
     * Récupère le challenge actuellement actif.
     */
    public function current()
    {
        $challenge = Challenge::where('actif', true)
            ->where('starting_date', '<=', now())
            ->where('finishing_date', '>=', now())
            ->first();

        if (!$challenge) {
            throw new ResourceNotFoundException();
        }

        return response()->json([
            'data' => [
                'challenge' => $challenge
            ],
            'error' => null,
            'meta' => null
        ]);
    }


    /**
     * Récupère un challenge précis.
     */
    public function show(Challenge $challenge)
    {
        return response()->json([
            'data' => [
                'challenge' => $challenge
            ],
            'error' => null,
            'meta' => null
        ], 200);
    }


    /**
     * Crée un nouveau challenge.
     */
    public function store(Request $request)
    {
        if ($request->user()->role() !== 'admin'){
            throw new AuthorizationException([
                'email' => 'Unauthorize access'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'theme' => 'required|string|max:255',
            'description' => 'required|string',
            'categorie' => 'required|string|max:255',
            'img_primary' => 'required|image|mimes:jpeg,png|max:10240',
            'starting_date' => 'required|date',
            'finishing_date' => 'required|date|after:starting_date',
            'actif' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages([
                'email' => "Request not validate"
            ]);
        }

        $imagePath = $request->file('img_primary')->store('challenges', 'public');

        $challenge = Challenge::create([
            'theme' => $request->theme,
            'description' => $request->description,
            'categorie' => $request->categorie,
            'img_primary' => $imagePath,
            'starting_date' => $request->starting_date,
            'finishing_date' => $request->finishing_date,
            'actif' => $request->actif,
        ]);

        return response()->json([
            'data' => $challenge
        ], 200);
    }
}