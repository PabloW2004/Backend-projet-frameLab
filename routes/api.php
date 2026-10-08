<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Authentification
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', function () {
    return NULL;
});

Route::get('/verify/{token}', function () {
    return NULL;
});

Route::post('/session', function () {
    return NULL;
});

Route::delete('/session', function () {
    return NULL;
});

//challenges
Route::get('/challenges/current', function () {
    return NULL;
});

Route::get('/challenges', function () {
    return NULL;
});

Route::get('/challenges/{id}', function () {
    return NULL;
});

Route::post('/admin/challenges', function () {
    return NULL;
});

//participations
Route::get('/challenges/current/participations', function () {
    return NULL;
});

Route::get('/challenges/{id}/participations', function () {
    return NULL;
});

Route::get('/participations/{id}', function () {
    return NULL;
});

Route::post('/participations', function () {
    return NULL;
});

//Votes
Route::get('/participations/{id}/comments', function () {
    return NULL;
});

Route::post('/participations/{id}/comments', function () {
    return NULL;
});

Route::patch('/comments/{id}', function () {
    return NULL;
});

Route::delete('/comments/{id}', function () {
    return NULL;
});


//historique personnel
Route::get('/user/participations', function () {
    return NULL;
});

Route::get('/user/statistics', function () {
    return NULL;
});

//signalements
Route::post('/participations/{id}/reports', function () {
    return NULL;
});

Route::post('/comments/{id}/reports', function () {
    return NULL;
});
