<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>TraKerja - Interview Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7fafc; color: #2d3748; }
        .container { max-width: 480px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #e2e8f0; padding: 32px; }
        .title { font-size: 1.25rem; font-weight: 600; margin-bottom: 12px; }
        .desc { font-size: 1rem; margin-bottom: 18px; }
        .footer { font-size: 0.9rem; color: #718096; margin-top: 32px; text-align: center; }
        .interview { background: #f3f0ff; padding: 12px; border-radius: 6px; margin: 18px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Interview Reminder</div>
        <div class="desc">Halo {{ $jobApplication->user->name }},<br>
            Kamu punya jadwal interview berikut:
        </div>
        <div class="interview">
            <strong>Posisi:</strong> {{ $jobApplication->position }}<br>
            <strong>Perusahaan:</strong> {{ $jobApplication->company_name }}<br>
            <strong>Tanggal:</strong> {{ $jobApplication->getFormattedInterviewDateAttribute() }}<br>
            @if($jobApplication->interview_location)
                <strong>Lokasi:</strong> {{ $jobApplication->interview_location }}<br>
            @endif
            @if($jobApplication->interview_notes)
                <strong>Catatan:</strong> {{ $jobApplication->interview_notes }}<br>
            @endif
        </div>
        <div class="desc">Semoga sukses dalam interview kamu!<br>Jangan lupa persiapkan diri dengan baik.</div>
        <div class="footer">TraKerja Team</div>
    </div>
</body>
</html>
