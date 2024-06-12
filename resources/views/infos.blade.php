@include('html-head')
@include('sidebar')

<div class="grid grid-rows-[4rem_1fr] w-full h-screen">
    @include('head')

    <main>
        <div class="px-4 sm:px-6 lg:px-8 dark:text-white">
            @if(app()->getLocale() == "en")
                {!! Illuminate\Support\Str::markdown($infotext[1]) !!}
            @else
                {!! Illuminate\Support\Str::markdown($infotext[0]) !!}
            @endif
        </div>
    </main>
</div>
@include('html-foot')