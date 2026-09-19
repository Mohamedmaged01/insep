{{-- Inline video player with download affordances removed. Expects $url.
     Handles YouTube, Vimeo, Google Drive, and self-hosted files. --}}
@php
    $videoUrl = trim($url ?? '');
    $embed    = null;   // iframe src for YouTube / Vimeo
    $driveId  = null;   // Google Drive file id

    if ($videoUrl !== '') {
        // YouTube (watch?v=, youtu.be, /embed/, /shorts/)
        if      (preg_match('~youtube\.com/embed/([^?&/\s]+)~i',  $videoUrl, $m)) $embed = "https://www.youtube.com/embed/{$m[1]}";
        elseif  (preg_match('~[?&]v=([^&\s]+)~i',                 $videoUrl, $m)) $embed = "https://www.youtube.com/embed/{$m[1]}";
        elseif  (preg_match('~youtu\.be/([^?&/\s]+)~i',           $videoUrl, $m)) $embed = "https://www.youtube.com/embed/{$m[1]}";
        elseif  (preg_match('~youtube\.com/shorts/([^?&/\s]+)~i', $videoUrl, $m)) $embed = "https://www.youtube.com/embed/{$m[1]}";
        // Vimeo
        elseif  (preg_match('~vimeo\.com/(?:video/)?(\d+)~i',     $videoUrl, $m)) $embed = "https://player.vimeo.com/video/{$m[1]}";
        // Google Drive
        elseif  (str_contains(strtolower($videoUrl), 'drive.google.com')) {
            if      (preg_match('~/d/([a-zA-Z0-9_-]+)~',    $videoUrl, $m)) $driveId = $m[1];
            elseif  (preg_match('~[?&]id=([a-zA-Z0-9_-]+)~', $videoUrl, $m)) $driveId = $m[1];
        }
    }
@endphp

@if($embed)
{{-- YouTube / Vimeo: embed inline with fullscreen enabled --}}
<div class="relative w-full rounded-xl overflow-hidden bg-black" style="padding-top: 56.25%">
    <iframe
        src="{{ $embed }}"
        class="absolute inset-0 w-full h-full"
        title="video"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"
        allowfullscreen></iframe>
    @include('partials.video-watermark')
</div>
@elseif($driveId)
{{-- Google Drive video: embed inline; overlay covers the top-right pop-out / download button --}}
<div class="relative w-full rounded-xl overflow-hidden bg-black" style="padding-top: 56.25%">
    <iframe
        src="https://drive.google.com/file/d/{{ $driveId }}/preview"
        class="absolute inset-0 w-full h-full"
        allow="autoplay; fullscreen"
        allowfullscreen
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
        playsinline
        class="w-full bg-black"
        style="max-height: 420px; display: block">
    </video>
    @include('partials.video-watermark')
</div>
@endif
