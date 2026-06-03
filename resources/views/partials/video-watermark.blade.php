{{-- Tiled per-user watermark overlaid on video players (leak deterrent).
     Shows the current user's name, email, and today's date repeated faintly
     across the whole frame. pointer-events:none so it never blocks controls. --}}
@php
    $wmUser = auth()->user();
    $wmText = $wmUser
        ? trim(($wmUser->name_ar ?? $wmUser->name ?? '') . '  ·  ' . ($wmUser->email ?? '') . '  ·  ' . now()->format('Y-m-d'))
        : null;
@endphp
@if($wmText)
<div style="position:absolute; inset:0; pointer-events:none; overflow:hidden; z-index:10">
    <div style="position:absolute; top:-50%; left:-50%; width:200%; height:200%;
                transform:rotate(-24deg); display:flex; flex-wrap:wrap;
                gap:30px 64px; align-content:flex-start; opacity:0.13">
        @for($i = 0; $i < 80; $i++)
        <span style="color:#fff; font-size:13px; font-weight:700; white-space:nowrap;
                     text-shadow:0 1px 2px rgba(0,0,0,0.7); font-family:sans-serif; letter-spacing:0.3px">{{ $wmText }}</span>
        @endfor
    </div>
</div>
@endif
