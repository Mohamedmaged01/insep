<!DOCTYPE html>
<html lang="ar" dir="rtl">
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
  .logo-area {
    margin-bottom: 18px;
  }
  .logo-area h1 {
    font-size: 22px;
    color: #1a3a5c;
    font-weight: bold;
    letter-spacing: 1px;
  }
  .logo-area p {
    font-size: 12px;
    color: #666;
    margin-top: 4px;
  }
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
    font-size: 36px;
    font-weight: bold;
    color: #1a3a5c;
    margin-bottom: 16px;
  }
  .cert-text {
    font-size: 14px;
    color: #444;
    line-height: 1.8;
    margin-bottom: 10px;
  }
  .student-name {
    font-size: 28px;
    font-weight: bold;
    color: #c9993a;
    margin: 10px 0;
    border-bottom: 1px solid #c9993a;
    display: inline-block;
    padding: 0 20px 4px;
  }
  .course-name {
    font-size: 18px;
    font-weight: bold;
    color: #1a3a5c;
    margin: 8px 0;
  }
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
  .seal {
    width: 70px;
    height: 70px;
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
    <h1>INSEP — المعهد الدولي للتدريب</h1>
    <p>International Institute for Training &amp; Professional Development</p>
  </div>
  <div class="divider"></div>
  <div class="cert-label">Certificate of Completion — شهادة إتمام</div>
  <div class="cert-title">{{ $certificate->title ?? 'شهادة إتمام الدورة' }}</div>
  <div class="cert-text">يُشهد بأن المتدرب / المتدربة</div>
  <div class="student-name">
    {{ $student?->name_en ?? $student?->name_ar ?? $student?->name ?? '—' }}
  </div>
  <div class="cert-text" style="margin-top:10px;">قد أتم/أتمت بنجاح متطلبات الدورة التدريبية</div>
  <div class="course-name">{{ $course?->title ?? '—' }}</div>
  @if($batch)
  <div class="cert-text" style="font-size:12px;color:#888;">المجموعة: {{ $batch->name }}</div>
  @endif
  @if($certificate->grade)
  <div class="cert-text" style="margin-top:6px;">بتقدير: <strong>{{ $certificate->grade }}</strong></div>
  @endif
  <div class="divider"></div>
  <div class="meta">
    <div>
      <div class="label">رقم الشهادة / Serial No.</div>
      <strong>{{ $certificate->serial_number }}</strong>
    </div>
    <div>
      <div class="seal">INSEP</div>
    </div>
    <div>
      <div class="label">تاريخ الإصدار / Issue Date</div>
      <strong>{{ $certificate->issue_date ?? now()->toDateString() }}</strong>
    </div>
  </div>
</div>
</body>
</html>
