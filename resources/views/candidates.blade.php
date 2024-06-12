@include('html-head')
@include('sidebar')

<div class="grid grid-rows-[4rem_1fr] w-full h-screen">
    @include('head')

    <main>
        <div class="px-4 sm:px-6 lg:px-8 dark:text-white">
            <div class="grid grid-cols-[1fr_auto] gap-x-4">
                <div>
                    <p class="mb-0 dark:text-white">{{ __('messages.candidates') }}</p>
                    @if(app()->getLocale() == "en")
                        @if ($committeeHasLists)
                            <h1 class="mb-1 dark:text-white">{{ $committeeName[1] }}</h1>
                        @else
                            <h1 class="mb-8 dark:text-white">{{ $committeeName[1] }}</h1>
                        @endif
                    @else
                        @if ($committeeHasLists)
                            <h1 class="mb-1 dark:text-white">{{ $committeeName[0] }}</h1>
                        @else
                            <h1 class="mb-8 dark:text-white">{{ $committeeName[0] }}</h1>
                        @endif
                    @endif
                </div>
                <div class="pt-4">
                    <a href="{{ '/committee?id=' . $committeeID . '&election=' . $electionID }}" title="{{ __('messages.info_on_this_committee')}}">
                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                    </a>
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
                    @endphp
                    @foreach ($candidates as $candidate)
                        @if ($candidate->list == $list->id)
                            <a class="candidate-box" href="{{ '/candidate?id=' . $candidate->id . '&election=' . $electionID . '&committee=' . $committeeID }}">
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
                            </a>
                            @php
                                $indexPeople++;
                            @endphp
                        @endif
                    @endforeach
                    @if ($indexPeople == 0)
                        <div class="px-6 py-3 bg-zinc-100 dark:bg-zinc-800 rounded-md shadow-sm dark:shadow-md border border-zinc-300 dark:border-zinc-600 overflow-hidden mb-4 dark:text-white">
                            {{ __('messages.no_candidates_list') }}
                        </div>
                    @endif
                @endforeach
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-4 mb-6">
                    <div>
                        <div class="results-info-box !border-2 !border-emerald-800">
                            <div><strong>{{ __('messages.seats_voting_rights') }}</strong></div>
                            <div>{{ $committeeSeats }}</div>
                        </div>
                    </div>
                    <div>
                        <div class="results-info-box !border-2 !border-lime-600">
                            <div><strong>{{ __('messages.seats_deputy') }}</strong></div>
                            <div>{{ $committeeSeatsDeputy }}</div>
                        </div>
                    </div>
                </div>
                @foreach ($candidates as $candidate)
                    <a class="candidate-box" href="{{ '/candidate?id=' . $candidate->id . '&election=' . $electionID . '&committee=' . $committeeID }}">
                        <div>
                            <span class="candidate-number">{{ $loop->iteration }}</span>
                        </div>
                        <div><strong>{{ trim($candidate->lastname) }}, {{ trim($candidate->firstname) }}</strong></div>
                        <div>
                            @if(app()->getLocale() == "en")
                                {{ json_decode($candidate->name, true)[1] }}
                            @else
                                {{ json_decode($candidate->name, true)[0] }}
                            @endif
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </main>
</div>
@include('html-foot')