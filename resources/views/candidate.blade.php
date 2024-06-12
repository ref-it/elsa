@include('html-head')
@include('sidebar')

<div class="grid grid-rows-[4rem_1fr] w-full h-screen">
    @include('head')

    <main>
        <div class="px-4 sm:px-6 lg:px-8 dark:text-white">
            <div>
                <p class="mb-0 dark:text-white">{{ __('messages.candidates') }} &rarr; <a class="text-black dark:text-white" href="{{ '/candidates?election=' . $electionID . '&committee=' . $committeeID }}">@if(app()->getLocale() == "en"){{ $committeeName[1] }}@else{{ $committeeName[0] }}@endif</a></p>
                <h1 class="mb-6 dark:text-white">{{ trim($candidate['lastname']) }}, {{ trim($candidate['firstname']) }}</h1>
            </div>

            @if($candidate['picture'] != null)
                <div class="xl:grid xl:grid-cols-[1fr_16rem] gap-x-8">
            @else
                <div>
            @endif
                <div>
                    <div class="candidate-data-boxes">
                        <div class="box-left">{{ __('messages.course') }}</div><div class="box-right">
                            @if(app()->getLocale() == "en")
                                {{ $courseName[1] }}
                            @else
                                {{ $courseName[0] }}
                            @endif
                        </div>
                        <div class="box-left">{{ __('messages.faculty') }}</div><div class="box-right">
                            @if(app()->getLocale() == "en")
                                {{ $facultyName[1] }}
                            @else
                                {{ $facultyName[0] }}
                            @endif
                        </div>
                        @if($committeeHasLists)
                            <div class="box-left">{{ __('messages.list') }}</div><div class="box-right">
                                @if(app()->getLocale() == "en")
                                    {{ $listName[1] }}
                                @else
                                    {{ $listName[0] }}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                @if($candidate['picture'] != null)
                    <div class="row-span-2 text-center w-full">
                        <img class="border border-zinc-300 shadow-sm dark:shadow-md dark:border-zinc-600 rounded-md max-h-72 xl:max-h-none mb-8 xl:mb-0 mx-auto" src="{{ $candidate['picture'] }}" alt="{{ __('messages.pictureOf') . ' ' . $candidate['firstname'] . ' ' . $candidate['lastname'] }}" />
                    </div>
                @endif
                <div class="grid grid-cols-1 gap-4">
                    @if(app()->getLocale() == "en")
                        @for($i = 0; $i < count($questions[1]); $i++)
                            @if ($answers != [] && $answers[1][$i] != null && $answers[1][$i] != "'")
                                <div class="question-answer-box">
                                    <div>{{ $questions[1][$i] }}</div>
                                    <div>{!! Illuminate\Support\Str::markdown($answers[1][$i]) !!}</div>
                                </div>
                            @endif
                        @endfor
                    @else
                        @for($i = 0; $i < count($questions[0]); $i++)
                            @if ($answers != [] && $answers[0][$i] != null && $answers[0][$i] != "'")
                                <div class="question-answer-box">
                                    <div>{{ $questions[0][$i] }}</div>
                                    <div>{!! Illuminate\Support\Str::markdown($answers[0][$i]) !!}</div>
                                </div>
                            @endif
                        @endfor
                    @endif
                </div>
                @if($candidate['picture'] != null)
                    <div class="mt-8 px-4 py-2 border-2 border-emerald-800 shadow-sm dark:shadow-md rounded-md col-span-2 dark:text-white">{{ __('messages.candidate_notice_other_language') }}</div>
                    <div class="mt-4 px-4 py-2 border-2 border-red-700 shadow-sm dark:shadow-md rounded-md col-span-2 dark:text-white">{{ __('messages.candidate_notice_content') }}</div>
                @else
                    <div class="mt-8 px-4 py-2 border-2 border-emerald-800 shadow-sm dark:shadow-md rounded-md col-span-2 dark:text-white">{{ __('messages.candidate_notice_other_language') }}</div>
                    <div class="mt-4 px-4 py-2 border-2 border-red-700 shadow-sm dark:shadow-md rounded-md dark:text-white">{{ __('messages.candidate_notice_content') }}</div>
                @endif
            </div>
        </div>
    </main>
</div>
@include('html-foot')