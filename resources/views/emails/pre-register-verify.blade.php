<!doctype html>
<html>
<body style="font-family: Arial, sans-serif;">
    <p>Halo {{ $name }},</p>
    <p>Terima kasih sudah daftar di Donasikuy. Klik tombol di bawah untuk verifikasi email kamu.</p>

    <p>
        <a href="{{ $verifyUrl }}"
           style="display:inline-block;padding:10px 14px;background:#10b981;color:#fff;text-decoration:none;border-radius:8px;">
            Verifikasi Email
        </a>
    </p>

    <p>Link ini akan kadaluarsa dalam 30 menit.</p>
</body>
</html>
