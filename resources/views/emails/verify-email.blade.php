<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email — Dina Salon Muslimah</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Georgia, 'Times New Roman', serif; background: #f5eeeb; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 24px rgba(61,53,47,0.10); }
        .header { background: linear-gradient(135deg, #3d352f 0%, #6b5b4d 100%); padding: 40px 40px 32px; text-align: center; }
        .header h1 { color: #fff; font-size: 26px; font-weight: 700; letter-spacing: 0.5px; }
        .header p { color: rgba(255,255,255,0.65); font-size: 13px; margin-top: 6px; }
        .body { padding: 40px; }
        .greeting { font-size: 17px; color: #3d352f; margin-bottom: 14px; }
        .desc { font-size: 14px; color: #7a6d65; line-height: 1.75; margin-bottom: 32px; }
        .otp-block { background: #fdf5f3; border: 1.5px dashed #c4a89a; border-radius: 18px; padding: 28px; text-align: center; margin-bottom: 32px; }
        .otp-label { font-size: 12px; color: #a0918a; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; }
        .otp-code { font-size: 46px; font-weight: 700; color: #3d352f; letter-spacing: 10px; font-family: 'Courier New', monospace; }
        .otp-expire { font-size: 12px; color: #b08880; margin-top: 12px; }
        .warning { background: #fff8f0; border-left: 3px solid #e8a87c; border-radius: 8px; padding: 14px 16px; font-size: 13px; color: #7a6455; line-height: 1.65; margin-bottom: 32px; }
        .footer { border-top: 1px solid #f0e8e4; padding: 24px 40px; text-align: center; }
        .footer p { font-size: 12px; color: #b0a09a; line-height: 1.7; }
        .footer strong { color: #3d352f; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Dina Salon Muslimah</h1>
            <p>Verifikasi Email Akun Baru</p>
        </div>
        <div class="body">
            <p class="greeting">Halo, <strong>{{ $user->nama }}</strong> 👋</p>
            <p class="desc">
                Terima kasih sudah mendaftar di <strong>Dina Salon Muslimah</strong>.<br><br>
                Gunakan kode OTP di bawah ini untuk memverifikasi email kamu.
                Kode ini hanya berlaku selama <strong>60 menit</strong>.
            </p>

            <div class="otp-block">
                <div class="otp-label">Kode OTP Verifikasi Email</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-expire">⏱ Berlaku hingga {{ now()->addMinutes(60)->format('H:i') }} WIB</div>
            </div>

            <div class="warning">
                ⚠️ <strong>Penting:</strong> Jangan bagikan kode ini kepada siapapun, termasuk tim Dina Salon Muslimah.
                Jika kamu tidak merasa mendaftar, abaikan email ini — akun tidak akan aktif tanpa verifikasi.
            </div>
        </div>
        <div class="footer">
            <p>
                Email ini dikirim otomatis dari sistem <strong>Dina Salon Muslimah</strong>.<br>
                Jl. Tuasan No.76, Medan Tembung · Jangan balas email ini.
            </p>
        </div>
    </div>
</body>
</html>