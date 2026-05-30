{{-- Inline video player with download affordances removed. Expects $url --}}
<video
    src="{{ $url }}"
    controls
    controlsList="nodownload noremoteplayback noplaybackrate"
    disablePictureInPicture
    oncontextmenu="return false"
    preload="metadata"
    class="w-full rounded-xl bg-black"
    style="max-height: 420px">
</video>
