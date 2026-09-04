<div class="p-6 sm:p-8 overflow-y-auto h-full">
    <div class="xl:flex xl:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold leading-6 text-zinc-800 dark:text-white">{{ __('admin.candidates') }}</h1>
        </div>
        <div class="mt-4 xl:ml-16 xl:mt-0 flex flex-col sm:flex-row whitespace-nowrap">
            <flux:button
                variant="primary"
                icon="plus"
                wire:navigate
                href="{{ route('admin-candidates-add') }}"
            >
                {{ __('admin.addCandidates') }}
            </flux:button>
        </div>
    </div>
    <div class="mt-6 mb-8 grid sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-6">
        <flux:field>
            <flux:label for="filter-lastname">{{ __('admin.lastname') }}</flux:label>
            <flux:input type="text" wire:model.live="lastname" />
        </flux:field>
        <flux:field>
            <flux:label for="filter-firstname">{{ __('admin.firstname') }}</flux:label>
            <flux:input type="text" wire:model.live="firstname" />
        </flux:field>
        <div>
            <flux:field>
                <flux:label for="filter-election">{{ __('admin.election') }}</flux:label>
                <flux:select
                    variant="listbox"
                    searchable
                    wire:model.live.change="election"
                    placeholder="{{ __('common.selectAnOption') }}"
                >
                    @foreach($elections as $election)
                        <flux:select.option value="{{ $election->id }}">
                            @if(app()->getLocale() == "de")
                                {{ json_decode($election->name)[0] }}
                            @elseif(app()->getLocale() == "en")
                                {{ json_decode($election->name)[1] }}
                            @endif
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
        </div>
        <div>
            <flux:field>
                <flux:label for="filter-faculty">{{ __('admin.faculty') }}</flux:label>
                <flux:select
                    variant="listbox"
                    searchable
                    wire:model.live.change="faculty"
                    placeholder="{{ __('common.selectAnOption') }}"
                >
                    @foreach($faculties as $faculty)
                        <flux:select.option value="{{ $faculty->id }}">
                            @if(app()->getLocale() == "de")
                                {{ json_decode($faculty->name)[0] }}
                            @elseif(app()->getLocale() == "en")
                                {{ json_decode($faculty->name)[1] }}
                            @endif
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
        </div>
        <div>
            <flux:field>
                <flux:label for="filter-committee">{{ __('admin.committee') }}</flux:label>
                <flux:select
                    variant="listbox"
                    searchable
                    wire:model.live.change="committee"
                    placeholder="{{ __('common.selectAnOption') }}"
                >
                    @foreach($committees as $committee)
                        <flux:select.option value="{{ $committee->id }}">
                            @if(app()->getLocale() == "de")
                                {{ json_decode($committee->name)[0] }}
                            @elseif(app()->getLocale() == "en")
                                {{ json_decode($committee->name)[1] }}
                            @endif
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
        </div>
    </div>
    <div class="mt-6">
                @if (count($candidates) > 0)
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column class="min-w-48">{{ __('admin.lastname') }}</flux:table.column>
                            <flux:table.column class="min-w-48">{{ __('admin.firstname') }}</flux:table.column>
                            <flux:table.column class="min-w-36">{{ __('admin.election') }}</flux:table.column>
                            <flux:table.column class="min-w-56">{{ __('admin.faculty') }}</flux:table.column>
                            <flux:table.column class="min-w-56">{{ __('admin.committee') }}</flux:table.column>
                            <flux:table.column class="min-w-32">{{ __('admin.approvedShort') }}</flux:table.column>
                            <flux:table.column><span class="sr-only">{{ __('admin.options') }}</span></flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach($candidates as $candidate)
                                <flux:table.row>
                                    <flux:table.cell>{{ $candidate->lastname }}</flux:table.cell>
                                    <flux:table.cell>{{ $candidate->firstname }}</flux:table.cell>
                                    <flux:table.cell>
                                        @if(app()->getLocale() == "de")
                                            {{ json_decode($candidate->election)[0] }}
                                        @elseif(app()->getLocale() == "en")
                                            {{ json_decode($candidate->election)[1] }}
                                        @endif
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        @if(app()->getLocale() == "de")
                                            {{ json_decode($candidate->faculty)[0] }}
                                        @elseif(app()->getLocale() == "en")
                                            {{ json_decode($candidate->faculty)[1] }}
                                        @endif
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        @if(app()->getLocale() == "de")
                                            {{ json_decode($candidate->committee)[0] }}
                                        @elseif(app()->getLocale() == "en")
                                            {{ json_decode($candidate->committee)[1] }}
                                        @endif
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        @if($candidate->approved)
                                            <flux:icon.check class="size-6 text-green-600 dark:text-green-500" aria-hidden="true" />
                                            <span class="sr-only">{{ __('admin.yes') }}</span>
                                        @else
                                            <flux:icon.x class="size-6 text-red-600 dark:text-red-300" aria-hidden="true" />
                                            <span class="sr-only">{{ __('admin.no') }}</span>
                                        @endif
                                    </flux:table.cell>
                                    <flux:table.cell class="whitespace-nowrap text-right">
                                        <flux:dropdown>
                                            <flux:button size="sm" icon="ellipsis-vertical" />
                                            <flux:menu>
                                                <flux:menu.item
                                                    wire:navigate
                                                    href="{{ route('admin-candidates-edit', ['id' => $candidate->id]) }}"
                                                    icon="pencil"
                                                >
                                                    {{ __('common.edit') }}
                                                </flux:menu.item>
                                            </flux:menu>
                                        </flux:dropdown>
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>

                    <div class="pagination">
                        <flux:pagination :paginator="$candidates" />
                    </div>
                @else
                <div>
                    <p class="text-zinc-800 dark:text-white">{{ __('admin.noResults') }}</p>
                </div>
                @endif
    </div>
</div>