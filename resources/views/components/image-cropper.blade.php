{{-- cropperjs v2 web components, driven by the `cropper` Alpine component in resources/js/app.js --}}
<cropper-canvas
    wire:key="image-cropper"
    x-ref="canvas"
    x-init="initCropper()"
    x-show="imageIsSelected"
    background
    {{ $attributes->class(['h-60 w-full']) }}
>
    <cropper-image x-ref="image" alt=""></cropper-image>
    <cropper-shade hidden></cropper-shade>
    <cropper-handle action="select" plain></cropper-handle>
    <cropper-selection x-ref="selection" movable resizable precise>
        <cropper-grid role="grid" bordered covered></cropper-grid>
        <cropper-crosshair centered></cropper-crosshair>
        <cropper-handle action="move" theme-color="rgba(255, 255, 255, 0.35)"></cropper-handle>
        <cropper-handle action="n-resize"></cropper-handle>
        <cropper-handle action="e-resize"></cropper-handle>
        <cropper-handle action="s-resize"></cropper-handle>
        <cropper-handle action="w-resize"></cropper-handle>
        <cropper-handle action="ne-resize"></cropper-handle>
        <cropper-handle action="nw-resize"></cropper-handle>
        <cropper-handle action="se-resize"></cropper-handle>
        <cropper-handle action="sw-resize"></cropper-handle>
    </cropper-selection>
</cropper-canvas>
