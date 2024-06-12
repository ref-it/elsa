<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class CommitteesController extends Controller
{
    public function getCommitteeInfoText(Request $request)
    {
        $electionID = $request->election;
        $committeeID = $request->id;
    
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
            ->select('name', DB::raw('array_to_json(description) as description'))
            ->where('id', '=', $committeeID)
            ->get();
    
        $committee_array = json_decode($committee, true);
        $name = json_decode($committee_array[0]['name'], true);
        if(json_decode($committee_array[0]['description'], true) != null) {
            $description = json_decode($committee_array[0]['description'], true);
        } else {
            $description = ["", ""];
        }
        //$infotext_en = json_decode($infotext_array[0]['infotext'], true)[1];
    
        return view(
            'committee',
            [
                'electionID' => $electionID,
                'committeeID' => $committeeID,
                'committees' => $committees,
                'committeeName' => $name,
                'committeeDescription' => $description,
                'candidatesExist' => json_decode($electionState, true)[0]['candidates_exist'],
                'allVotesCounted' => json_decode($electionState, true)[0]['all_votes_counted'],
            ]
        );
    }
}
