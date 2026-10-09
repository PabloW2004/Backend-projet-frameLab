<?php

use App\Http\Controllers\Api\ChallengeController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ParticipationController;
use App\Http\Controllers\Api\SignalementController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Authentification
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Inscription d'un nouvel utilisateur
Route::post('/users', [UserController::class, "store"]
);

// Validation du compte utilisateur via l'URL de validation
Route::get('/verify/{token}', [UserController::class, "validation"]);

// Connexion de l'utilisateur et récupération du token d'authentification
Route::post('/sessions', [UserController::class, "login"]);
Route::get('/sessions', [UserController::class, "checkUser"])->middleware('auth:sanctum');

// Déconnexion de l'utilisateur
Route::delete('/sessions', [UserController::class, "logout"])->middleware('auth:sanctum');


// Récupération du challenge actuellement actif
Route::get('/challenges/current', [ChallengeController::class, 'current']);

// Récupération de tous les challenges passés et actuels
Route::get('/challenges', [ChallengeController::class, 'index']);

// Récupération d'un challenge précis
Route::get('/challenges/{challenge}', [ChallengeController::class, 'show']);

// Publication d'un nouveau challenge par un administrateur
Route::post('/admin/challenges', [ChallengeController::class, 'store'])->middleware('auth:sanctum');;



//participations
// Récupération de toutes les participations du challenge actuellement actif
Route::get('/challenges/current/participations', [ParticipationController::class, "allCurrent"]);

// Récupération des participations d'un ancien challenge
Route::get('/challenges/{id}/participations', [ParticipationController::class, "fetchOld"]);

// Récupération du détail d'une participation : photo, auteur, tags, notes moyennes et nombre de commentaires
Route::get('/participations/{id}', [ParticipationController::class, "fetchOne"]);

// Soumission d'une participation avec l'image retouchée et les tags éventuels
Route::post('/participations', [ParticipationController::class, "store"]);



//Votes
// Ajout d'un vote sur une participation avec les notes créativité, technique et respect du thème
Route::get('/participations/{id}/votes', [VoteController::class, "store"]);



//commentaires
// Récupération chronologique des commentaires d'une participation
Route::get('/participations/{id}/comments', [CommentController::class, "fetchAll"]);

// Ajout d'un commentaire sur une participation
Route::post('/participations/{id}/comments', [CommentController::class, "store"]);

// Modification de son propre commentaire
Route::patch('/comments/{id}', [CommentController::class, "updateMine"]);

// Suppression de son propre commentaire
Route::delete('/comments/{id}', [CommentController::class, "destroyMine"]);



//historique personnel
// Récupération des participations passées et actuelles de l'utilisateur connecté,
// avec les notes et commentaires reçus
Route::get('/user/participations', [UserController::class, "fetchParticipation"]);

// Récupération des statistiques personnelles : nombre de participations et note moyenne globale
Route::get('/user/statistics', [UserController::class, "fetchStatisctics"]);



//signalements
// Signalement d'une participation avec un motif
Route::post('/participations/{id}/reports', [SignalementController::class, "storeForParticipation"]);

// Signalement d'un commentaire avec un motif
Route::post('/comments/{id}/reports', [SignalementController::class, "storeForComment"]);
