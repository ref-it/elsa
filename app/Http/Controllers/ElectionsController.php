<?php

namespace App\Http\Controllers;

use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElectionsController extends Controller
{
    public function forwardToCurrentElection() {
        $latestElectionID = DB::table('elections')
            ->select('id')
            ->orderByDesc('id')
            ->limit(1)
            ->get();
    
        return redirect('/infos?election=' . json_decode($latestElectionID, true)[0]['id']);
    }

    public function getInfosForElection(Request $request)
    {
        $electionID = $request->election;
    
        $electionState = DB::table('elections')
            ->select('candidates_exist', 'all_votes_counted')
            ->where('id', '=', $electionID)
            ->get();
    
        $committees = DB::table('committees')
            ->select('id','name')
            ->whereJsonContains('elections', (int)$electionID)
            ->orderBy('id', 'asc')
            ->get();
    
        $infotext = DB::table('elections')
            ->select(DB::raw('array_to_json(infotext) as infotext'))
            ->where('id', '=', $electionID)
            ->get();
    
        $infotext_array = json_decode($infotext, true);
    
        return view(
            'infos',
            [
                'electionID' => $electionID,
                'committees' => $committees,
                'infotext' => json_decode($infotext_array[0]['infotext'], true),
                'candidatesExist' => json_decode($electionState, true)[0]['candidates_exist'],
                'allVotesCounted' => json_decode($electionState, true)[0]['all_votes_counted'],
            ]
        );
    }

    public function getElectionData(Request $request)
    {
        $electionID = $request->election;
    
        $infotext = DB::table('elections')
            ->select(DB::raw('array_to_json(infotext) as infotext'))
            ->where('id', '=', $electionID)
            ->get();
    
        $infotext_array = json_decode($infotext, true);
    
        return view(
            'edit-election',
            [
                'electionID' => $electionID,
                'infotext' => json_decode($infotext_array[0]['infotext'], true),
            ]
        );
    }

    /*public function storeElectionData(Request $request)
    {
        $electionID = $request->election;

        $request->validate([
            'infotext_de' => 'string',
            'infotext_en' => 'string',
        ]);

        $infotext = array_combine($request->input('infotext_de'), $request->input('infotext_en'));
        $election = new Election();
        $election->infotext = $infotext;
        DB::table('elections')
            ->update($infotext)
            ->where('id', '=', $electionID);

        // Additional logic or redirection after successful data storage

        return redirect()->back()->with('success', 'Comment stored successfully!');
    }*/
}
