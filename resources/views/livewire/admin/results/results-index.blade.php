<div class="p-6 sm:p-8 overflow-y-auto h-full">
    <div class="xl:flex xl:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold leading-6 text-zinc-800 dark:text-white">{{ __('admin.results') }}</h1>
        </div>
        <div class="mt-4 xl:ml-16 xl:mt-0 flex flex-col sm:flex-row whitespace-nowrap">
            <flux:button variant="primary" icon="plus" wire:navigate href="{{ route('admin-results-add') }}">
                {{ __('admin.addResult') }}
            </flux:button>
        </div>
    </div>
    <div class="mt-6 mb-8 grid sm:grid-cols-2 gap-6">
        <div>
            <flux:field>
                <flux:label>{{ __('admin.election') }}</flux:label>
                <flux:select variant="listbox" searchable wire:model.live.change="election" placeholder="{{ __('common.selectAnOption') }}">
                    @foreach ($elections as $election)
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
                <flux:label>{{ __('admin.committee') }}</flux:label>
                <flux:select variant="listbox" searchable wire:model.live.change="committee" placeholder="{{ __('common.selectAnOption') }}">
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
        @if(count($results) > 0)
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="min-w-40">{{ __('admin.election') }}</flux:table.column>
                    <flux:table.column class="min-w-40">{{ __('admin.committee') }}</flux:table.column>
                    <flux:table.column><span class="sr-only">{{ __('admin.options') }}</span></flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach($results as $result)
                        <flux:table.row>
                            <flux:table.cell>@if(app()->getLocale() == "de"){{ json_decode($result->election)[0] }}@elseif(app()->getLocale() == "en"){{ json_decode($result->election)[1] }}@endif</flux:table.cell>
                            <flux:table.cell>@if(app()->getLocale() == "de"){{ json_decode($result->committee)[0] }}@elseif(app()->getLocale() == "en"){{ json_decode($result->committee)[1] }}@endif</flux:table.cell>
                            <flux:table.cell class="whitespace-nowrap text-right">
                                <flux:dropdown>
                                    <flux:button size="sm" icon="ellipsis-vertical" />
                                    <flux:menu>
                                        <flux:menu.item
                                            wire:navigate
                                            href="{{ route('admin-results-edit', ['id' => $result->id]) }}"
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
                <flux:pagination :paginator="$results" />
            </div>
        @else
            <div>
                <p class="text-zinc-800 dark:text-white">{{ __('admin.noResults') }}</p>
            </div>
        @endif
    </div>
</div>