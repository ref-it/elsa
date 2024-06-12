@include('html-head')
<div></div>

<div class="grid grid-rows-[4rem_1fr] w-full h-screen">
    @include('head')

    <main>
        <div class="px-4 sm:px-6 lg:px-8 dark:text-white">

<form action="{{ route('comment.store') }}" method="post">
    @csrf
    <table>
        <tr>
            <td>Deutsch</td>
            <td><textarea name="de" value="{{ $infotext[0] }}"></td>
        </tr>
        <tr>
            <td>Englisch</td>
            <td><textarea name="en" value="{{ $infotext[1] }}"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" />
            </td>
        </tr>
    </table>
</form>

        </div>
    </main>
</div>
@include('html-foot')