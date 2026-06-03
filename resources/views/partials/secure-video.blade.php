{{-- Inline video player with download affordances removed. Expects $url --}}
@php
    $videoUrl = $url ?? '';
    $driveId = null;
    if (str_contains(strtolower($videoUrl), 'drive.google.com')) {
        if (preg_match('~/d/([a-zA-Z0-9_-]+)~', $videoUrl, $m))        $driveId = $m[1];
        elseif (preg_match('~[?&]id=([a-zA-Z0-9_-]+)~', $videoUrl, $m)) $driveId = $m[1];
    }
@endphp

@if($driveId)
{{-- Google Drive video: embed inline; overlay covers the top-right pop-out / download button --}}
<div class="relative w-full rounded-xl overflow-hidden bg-black" style="padding-top: 56.25%">
    <iframe
        src="https://drive.google.com/file/d/{{ $driveId }}/preview"
        class="absolute inset-0 w-full h-full"
        allow="autoplay"
        frameborder="0"
        oncontextmenu="return false"></iframe>
    {{-- transparent shield over Drive's pop-out/download control (top-right) --}}
    <div class="absolute top-0 right-0" style="width: 130px; height: 56px; background: transparent; z-index: 5"></div>
    @include('partials.video-watermark')
</div>
@else
{{-- Self-hosted file: native player with download menu/right-click disabled --}}
<div class="relative w-full rounded-xl overflow-hidden bg-black">
    <video
        src="{{ $videoUrl }}"
        controls
        controlsList="nodownload noremoteplayback noplaybackrate"
        disablePictureInPicture
        oncontextmenu="return false"
        preload="metadata"
        class="w-full bg-black"
        style="max-height: 420px; display: block">
    </video>
    @include('partials.video-watermark')
</div>
@endif
