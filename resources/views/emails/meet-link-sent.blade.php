<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Link Konsultasi</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div
        style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; background-color: #f9f9f9;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: #28a745;">🌿 DiaKawan</h2>
            <p style="font-size: 16px;">Konsultasi Gizi Sehat & Profesional</p>
        </div>

        <h3>Halo, {{ $booking->name }}!</h3>

        <p>Terima kasih telah melakukan booking konsultasi dengan kami.</p>

        <p>Berikut adalah detail sesi konsultasi online Anda:</p>

        <ul>
            <li><strong>Tanggal:</strong>
                {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}</li>
            <li><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }} WIB</li>
            <li><strong>Link Meeting:</strong>
                <a href="{{ $booking->meet_link }}" target="_blank" style="color: #28a745; text-decoration: underline;">
                    {{ $booking->meet_link }}
                </a>
            </li>
        </ul>

        <p>Mohon hadir tepat waktu. Jika ada kendala, silakan hubungi kami via WhatsApp.</p>

        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

        <p style="font-size: 12px; color: #777;">
            © {{ date('Y') }} DiaKawan. All rights reserved.<br>
            Jl. Sehat No. 123, Jakarta Selatan
        </p>
    </div>
</body>

</html>
