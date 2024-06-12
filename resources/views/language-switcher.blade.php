<div class="ml-auto">
    <form action="{{ route('language.switch') }}" method="POST">
        @csrf
        <select name="language" onchange="this.form.submit()" aria-label="{{ __('messages.select_site_language') }}" class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 bg-zinc-800 text-white ring-1 ring-inset ring-zinc-700 focus:ring-2 focus:ring-yellow-400 sm:text-sm sm:leading-6">
            <option value="de" {{ app()->getLocale() === 'de' ? 'selected' : '' }} aria-label="{{ __('messages.german') }}">&#127465;&#127466;</option>
            <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }} aria-label="{{ __('messages.english') }}">&#127482;&#127480;</option>
        </select>
    </form>
</div>