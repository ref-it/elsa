{{-- Keeps everything but the general information pages out of search
     indexes; the list of exceptions lives in config/seo.php. --}}
@if($directive = \App\Support\Robots::currentDirective())
    <meta name="robots" content="{{ $directive }}">
@endif
