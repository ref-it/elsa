<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CandidatesController extends Controller
{
    public function getCandidatesIndex(Request $request)
    {
        $electionID = $request->election;
        $committeeID = $request->committee;
    
        $electionState = DB::table('elections')
            ->select('candidates_exist', 'all_votes_counted')
            ->where('id', '=', $electionID)
            ->get();
    
        $committees = DB::table('committees')
            ->select('id','name')
            ->whereJsonContains('elections', (int)$electionID)
            ->orderBy('id', 'asc')
            ->get();
    
        $committee = DB::table('committees')
            ->select('name', 'lists', 'seats', 'seats_deputy')
            ->where('id', '=', $committeeID)
            ->get();
    
        $lists = DB::table('lists')
            ->select('id', 'name', 'seats', 'seats_deputy')
            ->where('committee', '=', $committeeID)
            ->get();
    
        $candidates = DB::table('candidates')
            ->join('courses', 'candidates.course', '=', 'courses.id')
            ->select('candidates.id','candidates.lastname','candidates.firstname','candidates.list','courses.name')
            ->where('candidates.election', '=', $electionID)
            ->where('candidates.committee', '=', $committeeID)
            ->orderBy('candidates.lastname', 'asc')
            ->get();
    
        return view(
            'candidates',
            [
                'electionID' => $electionID,
                'committeeID' => $committeeID,
                'committees' => $committees,
                'candidates' => $candidates,
                'lists' => $lists,
                'committeeName' => json_decode(json_decode($committee, true)[0]['name'], true),
                'committeeHasLists' => json_decode(json_decode($committee, true)[0]['lists'], true),
                'candidatesExist' => json_decode($electionState, true)[0]['candidates_exist'],
                'allVotesCounted' => json_decode($electionState, true)[0]['all_votes_counted'],
                'committeeSeats' => json_decode(json_decode($committee, true)[0]['seats'], true),
                'committeeSeatsDeputy' => json_decode(json_decode($committee, true)[0]['seats_deputy'], true),
            ]
        );
    }

    public function getCandidateInfo(Request $request)
    {
        $electionID = $request->election;
        $candidateID = $request->id;
        $committeeID = $request->committee;
    
        $electionState = DB::table('elections')
            ->select('candidates_exist', 'all_votes_counted')
            ->where('id', '=', $electionID)
            ->get();
    
        $committees = DB::table('committees')
            ->select('id','name')
            ->whereJsonContains('elections', (int)$electionID)
            ->orderBy('id', 'asc')
            ->get();
    
        $candidate = DB::table('candidates')
            ->select('lastname', 'firstname', 'picture', 'course', 'faculty', 'list', 'committee')
            ->where('id', '=', $candidateID)
            ->get();
    
        $courseName = DB::table('courses')
            ->select('name')
            ->where('id', '=', json_decode($candidate, true)[0]['course'])
            ->get();
    
        $facultyName = DB::table('faculties')
            ->select('name')
            ->where('id', '=', json_decode($candidate, true)[0]['faculty'])
            ->get();
        
        $committee = DB::table('committees')
            ->select('name', 'lists')
            ->where('id', '=', $committeeID)
            ->get();
    
        if(json_decode($candidate, true)[0]['list'] != null) {
            $listNameDB = DB::table('lists')
                ->select('name')
                ->where('id', '=', json_decode($candidate, true)[0]['list'])
                ->get();
            $listName = json_decode(json_decode($listNameDB, true)[0]['name'], true);
        } else {
            $listName = "";
        }
    
        $committeeLists = DB::table('committees')
            ->select('lists')
            ->where('id', '=', json_decode($candidate, true)[0]['committee'])
            ->get();
    
        $questionsDB = DB::table('questions')
            ->select(DB::raw('array_to_json(questions) as questions'))
            ->where('election', '=', $electionID)
            ->where('committee', '=', json_decode($candidate, true)[0]['committee'])
            ->get();
    
        if(json_decode($questionsDB, true)[0]['questions'] != null) {
            $questions = json_decode(json_decode($questionsDB, true)[0]['questions'], true);
        } else {
            $questions = [];
        }
    
        $answersDB = DB::table('candidates')
            ->select(DB::raw('array_to_json(answers) as answers'))
            ->where('id', '=', $candidateID)
            ->get();
    
        if(json_decode($answersDB, true)[0]['answers'] != null) {
            $answers = json_decode(json_decode($answersDB, true)[0]['answers'], true);
        } else {
            $answers = [];
        }
    
        return view(
            'candidate',
            [
                'electionID' => $electionID,
                'committeeID' => $committeeID,
                'committees' => $committees,
                'committeeName' => json_decode(json_decode($committee, true)[0]['name'], true),
                'candidate' => json_decode($candidate, true)[0],
                'courseName' => json_decode(json_decode($courseName, true)[0]['name'], true),
                'facultyName' => json_decode(json_decode($facultyName, true)[0]['name'], true),
                'listName' => $listName,
                'committeeHasLists' => json_decode($committeeLists, true)[0]['lists'],
                'questions' => $questions,
                'answers' => $answers,
                'candidatesExist' => json_decode($electionState, true)[0]['candidates_exist'],
                'allVotesCounted' => json_decode($electionState, true)[0]['all_votes_counted'],
            ]
        );
    }

    public function getCandidatesResults(Request $request)
    {
        $electionID = $request->election;
        $committeeID = $request->committee;
    
        $electionState = DB::table('elections')
            ->select('candidates_exist', 'all_votes_counted')
            ->where('id', '=', $electionID)
            ->get();
    
        $committees = DB::table('committees')
            ->select('id','name')
            ->whereJsonContains('elections', (int)$electionID)
            ->orderBy('id', 'asc')
            ->get();
    
        $committee = DB::table('committees')
            ->select('name', 'lists', 'seats', 'seats_deputy')
            ->where('id', '=', $committeeID)
            ->get();
    
        $lists = DB::table('lists')
            ->select('id', 'name', 'seats', 'seats_deputy')
            ->where('committee', '=', $committeeID)
            ->get();
    
        $candidates = DB::table('candidates')
            ->join('courses', 'candidates.course', '=', 'courses.id')
            ->select('candidates.id','candidates.lastname','candidates.firstname','candidates.list','candidates.votes','candidates.resigned','courses.name')
            ->where('candidates.election', '=', $electionID)
            ->where('candidates.committee', '=', $committeeID)
            ->orderBy('candidates.votes', 'desc')
            ->get();

        $results = DB::table('results')
            ->select('eligible_voters', 'ballots_cast', 'ballots_invalid')
            ->where('election', '=', $electionID)
            ->where('committee', '=', $committeeID)
            ->get();
    
        return view(
            'results',
            [
                'electionID' => $electionID,
                'committeeID' => $committeeID,
                'committees' => $committees,
                'candidates' => $candidates,
                'results' => json_decode($results, true)[0],
                'lists' => $lists,
                'committeeName' => json_decode(json_decode($committee, true)[0]['name'], true),
                'committeeHasLists' => json_decode(json_decode($committee, true)[0]['lists'], true),
                'candidatesExist' => json_decode($electionState, true)[0]['candidates_exist'],
                'allVotesCounted' => json_decode($electionState, true)[0]['all_votes_counted'],
                'committeeSeats' => json_decode(json_decode($committee, true)[0]['seats'], true),
                'committeeSeatsDeputy' => json_decode(json_decode($committee, true)[0]['seats_deputy'], true),
            ]
        );
    }
}
