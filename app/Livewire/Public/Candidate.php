<?php

namespace App\Livewire\Public;

use App\Support\CandidatePicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class Candidate extends Component
{
    public function render(Request $request)
    {
        $electionID = $request->election;
        $candidateID = $request->id;
        $committeeID = $request->committee;

        $election = DB::table('elections')
            ->select('name', 'public', 'candidates_exist', 'all_votes_counted')
            ->where('id', $electionID)
            ->first();

        if (! $election->public) {
            abort('403');
        }

        $candidate = DB::table('candidates')
            ->select('lastname', 'firstname', 'picture', 'course', 'faculty', 'list', 'election', 'committee', 'approved')
            ->where('id', $candidateID)
            ->first();

        if (! $candidate->approved) {
            abort('403');
        }

        if ($electionID != $candidate->election || $committeeID != $candidate->committee) {
            abort('404');
        }

        $pictureUrl = CandidatePicture::temporaryUrl($candidate->picture);

        $courseName = DB::table('courses')
            ->select('name')
            ->where('id', $candidate->course)
            ->first();

        $facultyName = DB::table('faculties')
            ->select('name')
            ->where('id', $candidate->faculty)
            ->first();

        $committee = DB::table('committees')
            ->select('name', 'lists')
            ->where('id', $committeeID)
            ->first();

        if ($candidate->list) {
            $listName = DB::table('lists')
                ->select('name')
                ->where('id', $candidate->list)
                ->first();
        } else {
            $listName = '';
        }

        $committeeLists = DB::table('committees')
            ->select('lists')
            ->where('id', $candidate->committee)
            ->first();

        $questionsDB = DB::table('questions')
            ->select('questions')
            ->where('election', $electionID)
            ->where('committee', $candidate->committee)
            ->first();

        if ($questionsDB->questions != null) {
            $questions = json_decode($questionsDB->questions, true);
        } else {
            $questions = [];
        }

        $answersDB = DB::table('candidates')
            ->select('answers')
            ->where('id', $candidateID)
            ->first();

        if ($answersDB->answers != null) {
            $answers = json_decode($answersDB->answers, true);
        } else {
            $answers = [];
        }

        return view('livewire.public.candidate', [
            'electionID' => $electionID,
            'electionName' => json_decode($election->name),
            'committeeID' => $committeeID,
            'committeeName' => json_decode($committee->name, true),
            'candidate' => $candidate,
            'pictureUrl' => $pictureUrl,
            'courseName' => json_decode($courseName->name, true),
            'facultyName' => json_decode($facultyName->name, true),
            'listName' => $listName,
            'committeeHasLists' => $committeeLists->lists,
            'questions' => $questions,
            'answers' => $answers,
            'candidatesExist' => $election->candidates_exist,
            'allVotesCounted' => $election->all_votes_counted,
        ]);
    }
}
