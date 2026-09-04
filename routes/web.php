<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ElectionsController;
use App\Livewire\Admin\Candidates\CandidatesAdd;
use App\Livewire\Admin\Candidates\CandidatesEdit;
use App\Livewire\Admin\Candidates\CandidatesIndex;
use App\Livewire\Admin\Committees\CommitteesAdd;
use App\Livewire\Admin\Committees\CommitteesEdit;
use App\Livewire\Admin\Committees\CommitteesIndex;
use App\Livewire\Admin\Courses\CoursesAdd;
use App\Livewire\Admin\Courses\CoursesEdit;
use App\Livewire\Admin\Courses\CoursesIndex;
use App\Livewire\Admin\Elections\ElectionsAdd;
use App\Livewire\Admin\Elections\ElectionsEdit;
use App\Livewire\Admin\Elections\ElectionsIndex;
use App\Livewire\Admin\Faculties\FacultiesAdd;
use App\Livewire\Admin\Faculties\FacultiesEdit;
use App\Livewire\Admin\Faculties\FacultiesIndex;
use App\Livewire\Admin\Legal\LegalTextsEdit;
use App\Livewire\Admin\Lists\ListsAdd;
use App\Livewire\Admin\Lists\ListsEdit;
use App\Livewire\Admin\Lists\ListsIndex;
use App\Livewire\Admin\Questions\QuestionsAdd;
use App\Livewire\Admin\Questions\QuestionsEdit;
use App\Livewire\Admin\Questions\QuestionsIndex;
use App\Livewire\Admin\Results\ResultsAdd;
use App\Livewire\Admin\Results\ResultsEdit;
use App\Livewire\Admin\Results\ResultsIndex;
use App\Livewire\Candidacy\Candidacy;
use App\Livewire\Candidacy\CandidacyEdit;
use App\Livewire\Candidacy\CandidacyMy;
use App\Livewire\Dropdown\ElectionSwitch;
use App\Livewire\Dropdown\LanguageSwitch;
use App\Livewire\Public\Candidate;
use App\Livewire\Public\CandidateList;
use App\Livewire\Public\CommitteeInfo;
use App\Livewire\Public\ElectionInfo;
use App\Livewire\Public\Legal\Accessibility;
use App\Livewire\Public\Legal\Imprint;
use App\Livewire\Public\Legal\Privacy;
use App\Livewire\Public\Results;
use Illuminate\Support\Facades\Route;

$availableLanguages = Config::get('app.available_locales');
$lang = Request::getPreferredLanguage($availableLanguages);
if ($lang) {
    Config::set('app.locale', $lang);
}

// Public Routes
Route::get('/', [ElectionsController::class, 'forwardToCurrentElection']);
// Route::get('/infos', [ElectionsController::class, 'getInfosForElection']);
Route::livewire('/{election}/infos', ElectionInfo::class)->name('public-infos');
Route::livewire('/{election}/committee/{id}', CommitteeInfo::class)->name('public-committee');
Route::livewire('/{election}/committee/{committee}/candidates', CandidateList::class)->name('public-candidates');
Route::livewire('/{election}/committee/{committee}/candidate/{id}', Candidate::class)->name('public-candidate');
Route::livewire('/{election}/committee/{committee}/results', Results::class)->name('public-results');
Route::get('/candidacy', [ElectionsController::class, 'forwardToCurrentCandidacy']);

Route::livewire('/imprint', Imprint::class)->name('imprint');
Route::livewire('/privacy', Privacy::class)->name('privacy');
Route::livewire('/accessibility', Accessibility::class)->name('accessibility');

// Protected Routes
Route::group(['middleware' => ['auth']], function (): void {
    Route::livewire('/{election}/candidacy', Candidacy::class)->name('candidacy');

    Route::livewire('/{election}/candidacy/my', CandidacyMy::class)->name('candidacy-my');
    Route::livewire('/{election}/candidacy/{id}/edit', CandidacyEdit::class)->name('candidacy-edit');

    Route::get('/admin', [AdminController::class, 'forwardToElectionsIndex'])->name('admin-dashboard')->can('election-commission');

    Route::livewire('/admin/elections', ElectionsIndex::class)->name('admin-elections-index')->can('election-commission');
    Route::livewire('/admin/elections/new', ElectionsAdd::class)->name('admin-elections-add')->can('election-commission');
    Route::livewire('/admin/elections/{id}/edit', ElectionsEdit::class)->name('admin-elections-edit')->can('election-commission');

    Route::livewire('/admin/committees', CommitteesIndex::class)->name('admin-committees-index')->can('election-commission');
    Route::livewire('/admin/committees/new', CommitteesAdd::class)->name('admin-committees-add')->can('election-commission');
    Route::livewire('/admin/committees/{id}/edit', CommitteesEdit::class)->name('admin-committees-edit')->can('election-commission');

    Route::livewire('/admin/courses', CoursesIndex::class)->name('admin-courses-index')->can('election-commission');
    Route::livewire('/admin/courses/new', CoursesAdd::class)->name('admin-courses-add')->can('election-commission');
    Route::livewire('/admin/courses/{id}/edit', CoursesEdit::class)->name('admin-courses-edit')->can('election-commission');

    Route::livewire('/admin/faculties', FacultiesIndex::class)->name('admin-faculties-index')->can('election-commission');
    Route::livewire('/admin/faculties/new', FacultiesAdd::class)->name('admin-faculties-add')->can('election-commission');
    Route::livewire('/admin/faculties/{id}/edit', FacultiesEdit::class)->name('admin-faculties-edit')->can('election-commission');

    Route::livewire('/admin/lists', ListsIndex::class)->name('admin-lists-index')->can('election-commission');
    Route::livewire('/admin/lists/new', ListsAdd::class)->name('admin-lists-add')->can('election-commission');
    Route::livewire('/admin/lists/{id}/edit', ListsEdit::class)->name('admin-lists-edit')->can('election-commission');

    Route::livewire('/admin/questions', QuestionsIndex::class)->name('admin-questions-index')->can('election-commission');
    Route::livewire('/admin/questions/new', QuestionsAdd::class)->name('admin-questions-add')->can('election-commission');
    Route::livewire('/admin/questions/{id}/edit', QuestionsEdit::class)->name('admin-questions-edit')->can('election-commission');

    Route::livewire('/admin/candidates', CandidatesIndex::class)->name('admin-candidates-index')->can('election-commission');
    Route::livewire('/admin/candidates/new', CandidatesAdd::class)->name('admin-candidates-add')->can('election-commission');
    Route::livewire('/admin/candidate/{id}/edit', CandidatesEdit::class)->name('admin-candidates-edit')->can('election-commission');

    Route::livewire('/admin/results', ResultsIndex::class)->name('admin-results-index')->can('election-commission');
    Route::livewire('/admin/results/new', ResultsAdd::class)->name('admin-results-add')->can('election-commission');
    Route::livewire('/admin/results/{id}/edit', ResultsEdit::class)->name('admin-results-edit')->can('election-commission');

    Route::livewire('/admin/legal-texts', LegalTextsEdit::class)->name('admin-legal-texts-edit')->can('admin');
});

// Service Routes
Route::get('/language/{language}', [LanguageSwitch::class, 'switchLanguage'])->name('language');
Route::get('/election/{election}', [ElectionSwitch::class, 'switchElection'])->name('election');
