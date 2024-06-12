<div class="grid grid-rows-[auto_1fr] h-screen grow bg-zinc-50 dark:bg-zinc-800">
    <a class="flex h-16 px-6 shrink-0 items-center bg-zinc-800 border-b border-zinc-900 shadow-sm shadow-black/20" href="{{ '/infos?election=' . $electionID }}">
        <img class="h-8 w-auto" src="{{ asset('logo.svg') }}" alt="Wahlen an der TU Ilmenau">
    </a>
    <nav class="flex flex-1 flex-col px-6 py-4 h-full overflow-y-auto border-r border-zinc-300 dark:border-zinc-900">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    @if(str_contains(Route::getFacadeRoot()->current()->uri(), 'infos'))
                        <li class="active">
                    @else
                        <li>
                    @endif
                        <a href="{{ '/infos?election=' . $electionID }}" class="bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 dark:text-white group flex gap-x-3 rounded-md p-2 leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                            </svg>
                            {{ __('messages.election_infos') }}
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                @if($allVotesCounted)
                    <div class="mb-2 text-sm font-semibold leading-6 text-zinc-700 dark:text-zinc-400">{{ __('messages.results') }}</div>
                @elseif($candidatesExist)
                    <div class="mb-2 text-sm font-semibold leading-6 text-zinc-700 dark:text-zinc-400">{{ __('messages.candidates') }}</div>
                @else
                    <div class="mb-2 text-sm font-semibold leading-6 text-zinc-700 dark:text-zinc-400">{{ __('messages.committee_infos') }}</div>
                @endif
                <ul role="list" class="-mx-2 space-y-1">
                    @foreach ($committees as $committee)
                        @if(!empty($committeeID) && $committeeID == $committee->id)
                            <li class="active">
                        @else
                            <li>
                        @endif
                            @if($allVotesCounted)
                                <a href="{{ '/results?election=' . $electionID . '&committee=' . $committee->id }}" class="hover:bg-zinc-200 dark:hover:bg-zinc-700 dark:text-white group flex gap-x-3 rounded-md p-2 leading-6 font-semibold">
                                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                    @if(app()->getLocale() == "en")
                                        {{ json_decode($committee->name, true)[1] }}
                                    @else
                                        {{ json_decode($committee->name, true)[0] }}
                                    @endif
                                </a>
                            @elseif($candidatesExist)
                                <a href="{{ '/candidates?election=' . $electionID . '&committee=' . $committee->id }}" class="hover:bg-zinc-200 dark:hover:bg-zinc-700 dark:text-white group flex gap-x-3 rounded-md p-2 leading-6 font-semibold">
                                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                    @if(app()->getLocale() == "en")
                                        {{ json_decode($committee->name, true)[1] }}
                                    @else
                                        {{ json_decode($committee->name, true)[0] }}
                                    @endif
                                </a>
                            @else
                                <a href="{{ '/committee?id=' . $committee->id . '&election=' . $electionID }}" class="hover:bg-zinc-200 dark:hover:bg-zinc-700 dark:text-white group flex gap-x-3 rounded-md p-2 leading-6 font-semibold">
                                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                    @if(app()->getLocale() == "en")
                                        {{ json_decode($committee->name, true)[1] }}
                                    @else
                                        {{ json_decode($committee->name, true)[0] }}
                                    @endif
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
                <ul role="list" class="mt-8 -mx-2 space-y-1">
                    <li>
                        <a href="https://www.stura-ilmenau.de/impressum" target="_blank" aria-label="{{ __('messages.imprint') . ' (' . __('messages.link_new_window') . ')'}}" class="bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 dark:text-white group flex gap-x-3 rounded-md p-2 leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                            </svg>                              
                            {{ __('messages.imprint') }}
                        </a>
                    </li>
                    <li>
                        <a href="https://www.stura-ilmenau.de/datenschutz" target="_blank" aria-label="{{ __('messages.privacy_statement') . ' (' . __('messages.link_new_window') . ')'}}" class="bg-zinc-50 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 dark:text-white group flex gap-x-3 rounded-md p-2 leading-6 font-semibold">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>
                            {{ __('messages.privacy_statement') }}
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>