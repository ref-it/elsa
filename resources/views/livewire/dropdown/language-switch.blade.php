<flux:dropdown>
    <flux:button icon:trailing="chevron-down">
        @foreach(config('app.locales') as $key => $locale)
            @if(app()->getLocale() === $key)
                <flux:flag :country="$locale['flag']" size="xs" />
                <span class="sr-only">{{ $locale['name'] }}</span>
            @endif
        @endforeach
    </flux:button>
    <flux:menu>
        <flux:menu.radio.group>
            @foreach(config('app.locales') as $key => $locale)
                <flux:menu.radio
                    wire:navigate
                    href="{{ route('language', ['language' => $key]) }}"
                    class="flex w-full items-center"
                    :checked="app()->getLocale() === $key"
                >
                    <flux:flag :country="$locale['flag']" size="xs" class="mr-3" />
                    {{ $locale['name'] }}
                </flux:menu.radio>
            @endforeach
        </flux:menu.radio.group>
    </flux:menu>
</flux:dropdown>
