@include('html-head')
@include('sidebar')

<div class="grid grid-rows-[4rem_1fr] w-full h-screen">
    @include('head')

    <main>
        <div class="px-4 sm:px-6 lg:px-8 dark:text-white">
            @if(app()->getLocale() == "en")
                <h1 class="mb-8 dark:text-white">{{ $committeeName[1] }}</h1>
                {!! Illuminate\Support\Str::markdown($committeeDescription[1]) !!}
            @else
                <h1 class="mb-8 dark:text-white">{{ $committeeName[0] }}</h1>
                {!! Illuminate\Support\Str::markdown($committeeDescription[0]) !!}
            @endif
        </div>
    </main>
</div>
@include('html-foot')