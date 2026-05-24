<div class="body">
    <p class="greeting">Halo, <strong>{{ $user->nama }}</strong> 👋</p>
    <p class="desc">
        Terima kasih sudah mendaftar di <strong>Dina Salon Muslimah</strong>.<br><br>
        Klik tombol di bawah untuk memverifikasi email kamu. Link berlaku selama <strong>60 menit</strong>.
    </p>

    <div style="text-align:center; margin-bottom: 32px;">
        <a href="{{ url('/email/verify/' . $user->email_verify_token) }}"
           style="display:inline-block; background: linear-gradient(135deg,#3d352f,#6b5b4d);
                  color:#fff; text-decoration:none; padding:14px 36px;
                  border-radius:12px; font-size:15px; font-weight:700; letter-spacing:0.5px;">
            ✅ Verifikasi Email Saya
        </a>
    </div>

    <p style="font-size:12px; color:#b08880; text-align:center; margin-bottom:32px;">
        Atau copy link ini ke browser:<br>
        <span style="color:#7a6d65;">{{ url('/email/verify/' . $user->email_verify_token) }}</span>
    </p>

    <div class="warning">
        ⚠️ <strong>Penting:</strong> Jangan bagikan link ini kepada siapapun.
        Jika kamu tidak merasa mendaftar, abaikan email ini.
    </div>
</div>