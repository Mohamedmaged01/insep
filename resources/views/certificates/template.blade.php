<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'DejaVu Sans', sans-serif;
    background: #fff;
    width: 297mm;
    height: 210mm;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .cert {
    width: 100%;
    height: 100%;
    border: 12px double #1a3a5c;
    padding: 30px 50px;
    text-align: center;
    position: relative;
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 50%, #f8f9ff 100%);
  }
  .cert::before {
    content: '';
    position: absolute;
    inset: 20px;
    border: 2px solid #1a3a5c;
    pointer-events: none;
  }
  .logo-area { margin-bottom: 18px; }
  .logo-area h1 { font-size: 22px; color: #1a3a5c; font-weight: bold; letter-spacing: 1px; }
  .logo-area p  { font-size: 12px; color: #666; margin-top: 4px; }
  .divider {
    height: 2px;
    background: linear-gradient(to right, transparent, #1a3a5c, transparent);
    margin: 14px auto;
    width: 70%;
  }
  .cert-label {
    font-size: 13px;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 3px;
    margin-bottom: 8px;
  }
  .cert-title {
    font-size: 32px;
    font-weight: bold;
    color: #1a3a5c;
    margin-bottom: 16px;
  }
  .cert-text { font-size: 14px; color: #444; line-height: 1.8; margin-bottom: 10px; }
  .student-name {
    font-size: 28px;
    font-weight: bold;
    color: #c9993a;
    margin: 10px 0;
    border-bottom: 1px solid #c9993a;
    display: inline-block;
    padding: 0 20px 4px;
  }
  .course-name { font-size: 18px; font-weight: bold; color: #1a3a5c; margin: 8px 0; }
  .meta {
    display: flex;
    justify-content: space-between;
    margin-top: 28px;
    padding: 0 40px;
    font-size: 12px;
    color: #555;
  }
  .meta div { text-align: center; }
  .meta .label { font-size: 10px; color: #999; margin-bottom: 2px; }
  .serial { font-size: 13px; font-family: monospace; color: #1a3a5c; letter-spacing: 1px; }
  .seal {
    width: 70px; height: 70px;
    border-radius: 50%;
    border: 3px solid #1a3a5c;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 10px;
    color: #1a3a5c;
    font-weight: bold;
  }
</style>
</head>
<body>
<div class="cert">
  <div class="logo-area">
    <h1>INSEP — International Institute for Training</h1>
    <p>International Institute for Training &amp; Professional Development</p>
  </div>
  <div class="divider"></div>
  <div class="cert-label">Certificate of Completion</div>
  <div class="cert-title">{{ $certificate->title ?? 'Course Completion Certificate' }}</div>
  <div class="cert-text">This is to certify that</div>
  <div class="student-name">
    {{ $student?->name ?? $certificate->student_name ?? '—' }}
  </div>
  <div class="cert-text" style="margin-top:10px;">has successfully completed all requirements of the training program</div>
  <div class="course-name">{{ $course?->title ?? $certificate->title ?? '—' }}</div>
  @if($batch)
  <div class="cert-text" style="font-size:12px;color:#888;">Group: {{ $batch->name }}</div>
  @endif
  @if($certificate->grade)
  <div class="cert-text" style="margin-top:6px;">Grade: <strong>{{ $certificate->grade }}</strong></div>
  @endif
  <div class="divider"></div>
  <div class="meta">
    <div>
      <div class="label">Serial No.</div>
      <span class="serial">{{ $certificate->serial_number }}</span>
    </div>
    <div>
      <div class="seal">INSEP</div>
    </div>
    <div>
      <div class="label">Issue Date</div>
      <strong>{{ $certificate->issue_date ?? now()->toDateString() }}</strong>
    </div>
  </div>
</div>
</body>
</html>
