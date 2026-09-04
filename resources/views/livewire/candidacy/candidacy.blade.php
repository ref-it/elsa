<div class="p-6 sm:p-8 pb-0!">
    <div class="xl:flex xl:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold leading-6 text-zinc-800 dark:text-white">{{ __('candidacy.candidacy') }}</h1>
            <p class="dark:text-white">{{ json_decode($election->name)[0] }}</p>
        </div>
        <div class="mt-4 xl:ml-16 xl:mt-0 flex flex-col sm:flex-row whitespace-nowrap">
            <flux:button
                icon="file-text"
                href="https://ordnungen.stura.eu/satzung/wahlordnung.html"
                target="_blank"
            >
                {{ __('candidacy.electionRegulations') }}
            </flux:button>
        </div>
    </div>
    
    <h2 class="dark:text-white">{{ __('candidacy.personAndStudies') }}</h2>
    <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">
        <flux:field>
            <flux:label>{{ __('candidacy.firstname') }}</flux:label>
            <flux:input type="text" wire:model="firstname" disabled />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('candidacy.lastname') }}</flux:label>
            <flux:input type="text" wire:model="lastname" disabled />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('candidacy.emailAddress') }}</flux:label>
            <flux:input type="email" wire:model="email" disabled />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('candidacy.faculty') }}</flux:label>
            <flux:select variant="listbox" searchable wire:model.live.change="faculty" placeholder="{{ __('common.selectAnOption') }}">
                @foreach($faculties as $faculty)
                    <flux:select.option value="{{ $faculty->id }}">
                        @if(app()->getLocale() === 'de')
                            {{ json_decode($faculty->name)[0] }}
                        @elseif(app()->getLocale() === 'en')
                            {{ json_decode($faculty->name)[1] }}
                        @endif
                    </flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="faculty" />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('candidacy.course') }}</flux:label>
            <flux:select variant="listbox" searchable wire:model.live.change="course" placeholder="{{ __('common.selectAnOption') }}">
                @foreach($courses as $course)
                    <flux:select.option value="{{ $course->id }}">
                        @if(app()->getLocale() === 'de')
                            {{ json_decode($course->name)[0] }}
                        @elseif(app()->getLocale() === 'en')
                            {{ json_decode($course->name)[1] }}
                        @endif
                    </flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="course" />
        </flux:field>
    </div>

    <h2 class="mt-10 dark:text-white">{{ __('candidacy.committee') }}</h2>
    {{--<flux:callout
        variant="warning"
        icon="circle-alert"
        heading="{{ __('candidacy.committeeHint') }}"
    />--}}
        
    <div class="mt-6 grid sm:grid-cols-2 gap-6">
        <flux:field>
            <flux:label>{{ __('candidacy.committee') }}</flux:label>
            <flux:select variant="listbox" searchable wire:model.live.change="committee" placeholder="{{ __('common.selectAnOption') }}">
                @foreach($committees as $committee)
                    <flux:select.option value="{{ $committee->id }}">
                        @if(app()->getLocale() === 'de')
                            {{ json_decode($committee->name)[0] }}
                        @elseif(app()->getLocale() === 'en')
                            {{ json_decode($committee->name)[1] }}
                        @endif
                    </flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="committee" />
        </flux:field>

        @if(count($lists) > 0)
            <flux:field>
                <flux:label>{{ __('candidacy.list') }}</flux:label>
                <flux:select variant="listbox" searchable placeholder="{{ __('common.selectAnOption') }}">
                    @foreach ($lists as $list)
                        <flux:select.option value="{{ $list->id }}">
                            @if(app()->getLocale() === 'de')
                                {{ json_decode($list->name)[0] }}
                            @elseif(app()->getLocale() === 'en')
                                {{ json_decode($list->name)[1] }}
                            @endif
                        </flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="list" />
            </flux:field>
        @endif
    </div>

    <hr class="my-8 dark:border-zinc-700">

    <p class="dark:text-white">{{ __('candidacy.candidacyHint') }}</p>
    <p class="mb-10 dark:text-white">{{ __('candidacy.privacyHint') }}</p>

    <div class="mt-auto py-6 -mx-8 px-8 flex items-center justify-end gap-x-4 border-t border-zinc-200 dark:border-zinc-900 bg-zinc-100 dark:bg-zinc-800">
        <flux:button variant="primary" icon="send" wire:click="save">
            {{ __('candidacy.send') }}
        </flux:button>
    </div>
</div>