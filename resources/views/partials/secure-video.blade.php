{{-- Inline video player with download affordances removed. Expects $url.
     Handles YouTube, Vimeo, Google Drive, and self-hosted files.
     A custom fullscreen button fullscreens the container itself, so it works
     for every source (Drive/YouTube preview players have unreliable native FS). --}}
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

@once
@push('scripts')
<style>
    .insep-fs-btn {
        position: absolute; top: 10px; left: 10px; z-index: 20;
        width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center;
        background: rgba(0,0,0,.55); color: #fff; border: 0; border-radius: 9px; cursor: pointer;
        opacity: .8; transition: opacity .15s ease, background .15s ease;
    }
    .insep-fs-btn:hover { opacity: 1; background: rgba(0,0,0,.75); }
    .insep-video:fullscreen, .insep-video:-webkit-full-screen {
        width: 100vw; height: 100vh; padding-top: 0 !important; border-radius: 0; background: #000;
    }
    .insep-video:fullscreen iframe, .insep-video:fullscreen video,
    .insep-video:-webkit-full-screen iframe, .insep-video:-webkit-full-screen video {
        position: absolute; inset: 0; width: 100%; height: 100%; max-height: 100vh;
    }
</style>
<script>
function insepToggleFs(btn){
    var box = btn.closest('.insep-video');
    if(!box) return;
    var active = document.fullscreenElement || document.webkitFullscreenElement;
    if(active){
        (document.exitFullscreen || document.webkitExitFullscreen).call(document);
    } else {
        (box.requestFullscreen || box.webkitRequestFullscreen || function(){}).call(box);
    }
}
</script>
@endpush
@endonce

@php
    $fsButton = '<button type="button" onclick="insepToggleFs(this)" class="insep-fs-btn" title="ملء الشاشة / Fullscreen" aria-label="Fullscreen">'
        . '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">'
        . '<path stroke-linecap="round" stroke-linejoin="round" d="M4 9V5a1 1 0 011-1h4M15 4h4a1 1 0 011 1v4M20 15v4a1 1 0 01-1 1h-4M9 20H5a1 1 0 01-1-1v-4"/></svg></button>';
@endphp

@if($embed)
{{-- YouTube / Vimeo --}}
<div class="insep-video relative w-full rounded-xl overflow-hidden bg-black" style="padding-top: 56.25%">
    <iframe
        src="{{ $embed }}"
        class="absolute inset-0 w-full h-full"
        title="video"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"
        allowfullscreen></iframe>
    {!! $fsButton !!}
    @include('partials.video-watermark')
</div>
@elseif($driveId)
{{-- Google Drive video: embed inline; overlay covers the top-right pop-out / download button --}}
<div class="insep-video relative w-full rounded-xl overflow-hidden bg-black" style="padding-top: 56.25%">
    <iframe
        src="https://drive.google.com/file/d/{{ $driveId }}/preview"
        class="absolute inset-0 w-full h-full"
        allow="autoplay; fullscreen"
        allowfullscreen
        frameborder="0"
        oncontextmenu="return false"></iframe>
    {{-- transparent shield over Drive's pop-out/download control (top-right) --}}
    <div class="absolute top-0 right-0" style="width: 130px; height: 56px; background: transparent; z-index: 5"></div>
    {!! $fsButton !!}
    @include('partials.video-watermark')
</div>
@else
{{-- Self-hosted file: native player (its own controls include fullscreen) --}}
<div class="insep-video relative w-full rounded-xl overflow-hidden bg-black">
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
