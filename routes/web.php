<?php

use App\Http\Controllers\CandidatesController;
use App\Http\Controllers\CommitteesController;
use App\Http\Controllers\ElectionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;

Route::get('/', [ElectionsController::class, 'forwardToCurrentElection']);

Route::get('/login', function () {
    return view('login');
});

Route::get('/infos', [ElectionsController::class, 'getInfosForElection']);

Route::get('/committee', [CommitteesController::class, 'getCommitteeInfoText']);

Route::get('/candidates', [CandidatesController::class, 'getCandidatesIndex']);

Route::get('/candidate', [CandidatesController::class, 'getCandidateInfo']);

Route::get('/results', [CandidatesController::class, 'getCandidatesResults']);

// Route::get('/admin/elections/edit', [ElectionsController::class, 'getElectionData']);
// Route::post('/admin/elections/edit', [ElectionsController::class, 'storeElectionData']);

Route::post('/language-switch', [LanguageController::class, 'languageSwitch'])->name('language.switch');
