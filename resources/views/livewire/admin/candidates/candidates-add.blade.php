<div class="flex flex-col p-6 sm:p-8 pb-0! overflow-y-auto h-full">
    <div class="xl:flex xl:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold leading-6 text-zinc-800 dark:text-white">{{ __('admin.candidatesAddTitle') }}</h1>
        </div>
    </div>
    <div class="mt-6 mb-12">
        <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
            <flux:field>
                <flux:label>{{ __('admin.election') }}</flux:label>
                <flux:select
                    variant="listbox"
                    searchable
                    wire:model.live.change="election"
                    placeholder="{{ __('common.selectAnOption') }}"
                >
                    @foreach($elections as $election)
                        <flux:select.option value="{{ $election->id }}">{{ json_decode($election->name)[0] }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
            <flux:field>
                <flux:label>{{ __('admin.committee') }}</flux:label>
                <flux:select
                    variant="listbox"
                    searchable
                    wire:model.live.change="committee"
                    placeholder="{{ __('common.selectAnOption') }}"
                >
                    @foreach($committees as $committee)
                        <flux:select.option value="{{ $committee->id }}">{{ json_decode($committee->name)[0] }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
            @if(count($lists) > 0)
                <flux:field>
                    <flux:label>{{ __('admin.list') }}</flux:label>
                    <flux:select
                        variant="listbox"
                        searchable
                        wire:model.live.change="list"
                        placeholder="{{ __('common.selectAnOption') }}"
                    >
                        @foreach($lists as $list)
                            <flux:select.option value="{{ $list->id }}">{{ json_decode($list->name)[0] }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </flux:field>
            @endif
        </div>
        <div class="mt-10">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('admin.emailAddress') }}</flux:table.column>
                    <flux:table.column>{{ __('admin.faculty') }}</flux:table.column>
                    <flux:table.column>{{ __('admin.course') }}</flux:table.column>
                    <flux:table.column class="w-[3.4rem]"></flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                @foreach($candidates as $index => $candidate)
                    <flux:table.row>
                        <flux:table.cell>
                            <flux:input type="text" wire:model="candidates.{{ $index }}.email" placeholder="max.mustermann@tu-ilmenau.de" />
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:select
                                variant="listbox"
                                searchable wire:model="candidates.{{ $index }}.faculty"
                                placeholder="{{ __('common.selectAnOption') }}"
                            >
                                @foreach($faculties as $faculty)
                                    <flux:select.option value="{{ $faculty->id }}">{{ json_decode($faculty->name)[0] }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:select
                                variant="listbox"
                                searchable
                                wire:model="candidates.{{ $index }}.course"
                                placeholder="{{ __('common.selectAnOption') }}"
                            >
                                @foreach($courses as $course)
                                    <flux:select.option value="{{ $course->id }}">{{ json_decode($course->name)[0] }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </flux:table.cell>
                        <flux:table.cell class="flex">
                            <flux:button icon="minus" wire:click="removeCandidate({{ $index }})" title="{{ __('admin.removeCandidate') }}" />
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.cells>
            </flux:table>
            <div class="mt-2 md:mt-6">
                <flux:button icon="plus" wire:click="addCandidate">
                    {{ __('admin.addCandidate') }}
                </flux:button>
            </div>
        </div>
    </div>
    <div class="mt-auto py-6 -mx-8 px-8 flex items-center justify-end gap-x-4 border-t border-zinc-200 dark:border-zinc-900 bg-zinc-100 dark:bg-zinc-800">
        <flux:button icon="ban" wire:navigate href="{{ route('admin-candidates-index') }}">
            {{ __('common.cancel') }}
        </flux:button>
        <flux:button variant="primary" icon="save" wire:click="save">
            {{ __('common.add') }}
        </flux:button>
    </div>
</div>