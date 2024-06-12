@include('html-head')
@include('sidebar')

<div class="grid grid-rows-[4rem_1fr] w-full h-screen">
    @include('head')

    <main>
        <div class="px-4 sm:px-6 lg:px-8 dark:text-white">
            <p class="mb-0 dark:text-white">{{ __('messages.results') }}</p>
            @if(app()->getLocale() == "en")
                <h1 class="mb-8 dark:text-white">{{ $committeeName[1] }}</h1>
            @else
                <h1 class="mb-8 dark:text-white">{{ $committeeName[0] }}</h1>
            @endif

            @if($committeeHasLists)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-4 mb-6">
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-4 mb-6">
                    <div>
                        <div class="results-info-box !border-2 !border-emerald-800">
                            <div><strong>{{ __('messages.seats_voting_rights') }}</strong></div>
                            <div>{{ $committeeSeats }}</div>
                        </div>
                        <div class="results-info-box !border-2 !border-lime-600">
                            <div><strong>{{ __('messages.seats_deputy') }}</strong></div>
                            <div>{{ $committeeSeatsDeputy }}</div>
                        </div>
                    </div>
            @endif
                <div>
                    <div class="results-info-box">
                        <div><strong>{{ __('messages.voters_eligible') }}</strong></div>
                        <div>{{ $results["eligible_voters"] }}</div>
                    </div>
                    <div class="results-info-box">
                        <div><strong>{{ __('messages.voters_participated') }}</strong></div>
                        <div>{{ $results["ballots_cast"] }}</div>
                    </div>
                    <div class="results-info-box">
                        <div><strong>{{ __('messages.voters_turnout') }}</strong></div>
                        <div>{{ round((($results["ballots_cast"] / $results["eligible_voters"]) * 100), 2) }} %</div>
                    </div>
                </div>
                <div>
                    <div class="results-info-box">
                        <div><strong>{{ __('messages.ballots_total') }}</strong></div>
                        <div>{{ $results["ballots_cast"] }}</div>
                    </div>
                    <div class="results-info-box">
                        <div><strong>{{ __('messages.ballots_valid') }}</strong></div>
                        <div>{{ $results["ballots_cast"] - $results["ballots_invalid"] }}</div>
                    </div>
                    <div class="results-info-box">
                        <div><strong>{{ __('messages.ballots_invalid') }}</strong></div>
                        <div>{{ $results["ballots_invalid"] }}</div>
                    </div>
                </div>
            </div>

            @if ($committeeHasLists)
                @foreach ($lists as $list)
                    @if(app()->getLocale() == "en")
                        <h2>{{ json_decode($list->name, true)[1] }}</h2>
                    @else
                        <h2>{{ json_decode($list->name, true)[0] }}</h2>
                    @endif
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-4">
                        <div>
                            <div class="results-info-box !border-2 !border-emerald-800">
                                <div><strong>{{ __('messages.seats_voting_rights') }}</strong></div>
                                <div>{{ $list->seats }}</div>
                            </div>
                        </div>
                        <div>
                            <div class="results-info-box !border-2 !border-lime-600">
                                <div><strong>{{ __('messages.seats_deputy') }}</strong></div>
                                <div>{{ $list->seats_deputy }}</div>
                            </div>
                        </div>
                    </div>
                    @php
                        $indexPeople = 0;
                        $resignedCandidates = false;
                    @endphp
                    @foreach ($candidates as $candidate)
                        @if ($candidate->list == $list->id)
                            @if (!$candidate->resigned)
                                @if ($indexPeople < $list->seats)
                                    <a class="candidate-box candidate-box-results !border-2 !border-emerald-800" href="{{ '/candidate?id=' . $candidate->id . '&election=' . $electionID . '&committee=' . $committeeID }}">
                                @elseif ($indexPeople < ($list->seats + $list->seats_deputy))
                                    <a class="candidate-box candidate-box-results !border-2 !border-lime-600" href="{{ '/candidate?id=' . $candidate->id . '&election=' . $electionID . '&committee=' . $committeeID }}">
                                @else
                                    <a class="candidate-box candidate-box-results" href="{{ '/candidate?id=' . $candidate->id . '&election=' . $electionID . '&committee=' . $committeeID }}">
                                @endif
                                    <div>
                                        <span class="candidate-number">{{ $indexPeople + 1 }}</span>
                                    </div>
                                    <div><strong>{{ trim($candidate->lastname) }}, {{ trim($candidate->firstname) }}</strong></div>
                                    <div>
                                        @if(app()->getLocale() == "en")
                                            {{ json_decode($candidate->name, true)[1] }}
                                        @else
                                            {{ json_decode($candidate->name, true)[0] }}
                                        @endif
                                    </div>
                                    <div class="text-right">{{ $candidate->votes }} {{ __('messages.votes') }}</div>
                                    <div class="text-right">{{ round($candidate->votes / ($results["ballots_cast"] - $results["ballots_invalid"]), 2)}} %</div>
                                </a>
                                @php
                                    $indexPeople++;
                                @endphp
                            @else
                                @php
                                    $resignedCandidates = true;
                                @endphp
                            @endif
                        @endif
                    @endforeach

                    @if ($indexPeople == 0)
                        <div class="px-6 py-3 bg-zinc-100 dark:bg-zinc-800 rounded-md shadow-sm dark:shadow-md border border-zinc-300 dark:border-zinc-600 overflow-hidden mb-4 dark:text-white">
                            {{ __('messages.no_candidates_list') }}
                        </div>
                    @endif

                    @if($resignedCandidates)
                        <div class="mt-8">
                            <p><strong>{{ __('messages.resigned') }}</strong></p>
                            @foreach ($candidates as $candidate)
                                @if ($candidate->resigned)
                                    <a class="candidate-box candidate-box-resigned" href="{{ '/candidate?id=' . $candidate->id . '&election=' . $electionID . '&committee=' . $committeeID }}">
                                        <div><strong>{{ trim($candidate->lastname) }}, {{ trim($candidate->firstname) }}</strong></div>
                                        <div>
                                            @if(app()->getLocale() == "en")
                                                {{ json_decode($candidate->name, true)[1] }}
                                            @else
                                                {{ json_decode($candidate->name, true)[0] }}
                                            @endif
                                        </div>
                                        <div class="text-right">{{ $candidate->votes }} {{ __('messages.votes') }}</div>
                                        <div class="text-right">{{ round($candidate->votes / ($results["ballots_cast"] - $results["ballots_invalid"]), 2)}} %</div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    
                @endforeach
            @else
                @php
                    $indexPeople = 0;
                @endphp
                @foreach ($candidates as $candidate)
                    @if (!$candidate->resigned)
                        @if ($indexPeople < $committeeSeats)
                            <a class="candidate-box candidate-box-results !border-2 !border-emerald-800" href="{{ '/candidate?id=' . $candidate->id . '&election=' . $electionID . '&committee=' . $committeeID }}">
                        @elseif ($indexPeople < ($committeeSeats + $committeeSeatsDeputy))
                            <a class="candidate-box candidate-box-results !border-2 !border-lime-600" href="{{ '/candidate?id=' . $candidate->id . '&election=' . $electionID . '&committee=' . $committeeID }}">
                        @else
                            <a class="candidate-box candidate-box-results" href="{{ '/candidate?id=' . $candidate->id . '&election=' . $electionID . '&committee=' . $committeeID }}">
                        @endif
                            <div>
                                <span class="candidate-number">{{ $indexPeople + 1 }}</span>
                            </div>
                            <div><strong>{{ trim($candidate->lastname) }}, {{ trim($candidate->firstname) }}</strong></div>
                            <div>
                                @if(app()->getLocale() == "en")
                                    {{ json_decode($candidate->name, true)[1] }}
                                @else
                                    {{ json_decode($candidate->name, true)[0] }}
                                @endif
                            </div>
                            <div class="text-right">{{ $candidate->votes }} {{ __('messages.votes') }}</div>
                            <div class="text-right">{{ round($candidate->votes / ($results["ballots_cast"] - $results["ballots_invalid"]), 2)}} %</div>
                        </a>
                        @php
                            $indexPeople++;
                        @endphp
                    @endif
                @endforeach

                @if($indexPeople < count($candidates))
                    <div class="mt-8">
                        <h2>{{ __('messages.resigned') }}</h2>
                        @foreach ($candidates as $candidate)
                            @if ($candidate->resigned)
                                <a class="candidate-box candidate-box-resigned" href="{{ '/candidate?id=' . $candidate->id . '&election=' . $electionID . '&committee=' . $committeeID }}">
                                    <div><strong>{{ trim($candidate->lastname) }}, {{ trim($candidate->firstname) }}</strong></div>
                                    <div>
                                        @if(app()->getLocale() == "en")
                                            {{ json_decode($candidate->name, true)[1] }}
                                        @else
                                            {{ json_decode($candidate->name, true)[0] }}
                                        @endif
                                    </div>
                                    <div class="text-right">{{ $candidate->votes }} {{ __('messages.votes') }}</div>
                                    <div class="text-right">{{ round($candidate->votes / ($results["ballots_cast"] - $results["ballots_invalid"]), 2)}} %</div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            @endif

        </div>
    </main>
</div>
@include('html-foot')