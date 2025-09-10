<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Diverifikasi</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        /* General Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #eef2f7; /* Latar belakang lebih lembut */
            color: #51545e;
        }

        .email-wrapper {
            /* Latar belakang dengan gradien halus */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
        }

        /* Container - Efek Glassmorphism */
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            
            /* Glassmorphism Effect */
            background: rgba(255, 255, 255, 0.15); /* Transparansi */
            backdrop-filter: blur(25px); /* Efek blur */
            -webkit-backdrop-filter: blur(25px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);

            overflow: hidden;
            text-align: center;
        }

        /* Header */
        .header {
            padding: 40px 20px 20px;
        }

        .header img {
            max-width: 150px;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
        }

        /* Content */
        .content {
            padding: 10px 40px 30px;
            color: #fff; /* Teks putih agar kontras dengan latar belakang */
        }

        .content h1 {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .content p {
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 30px;
        }
        
        .content strong {
            font-weight: 600;
        }

        /* Button */
        .button {
            display: inline-block;
            background: #ffffff;
            color: #667eea;
            padding: 14px 30px;
            border-radius: 50px; /* Bentuk pil */
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        /* Footer */
        .footer {
            padding: 25px;
            text-align: center;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="container">
            <!-- 1. Header dengan Logo -->
            <div class="header">
                <!-- Ganti dengan URL logo Anda, pastikan logo memiliki latar transparan (PNG) -->
                <img src="https://placehold.co/200x50/ffffff/667EEA?text=LogoAnda&font=poppins" alt="Logo Perusahaan">
            </div>

            <!-- 2. Konten Utama -->
            <div class="content">
                <img src="https://placehold.co/100x100/ffffff/667EEA?text=✓&font=poppins" style="border-radius:50%; margin-bottom: 20px; border: 3px solid rgba(255,255,255,0.5);" alt="Verified Icon">
                
                <h1>Verifikasi Berhasil!</h1>
                <p>Halo, <strong>{{ $userName }}</strong>!</p>
                <p>Akun Anda telah disetujui oleh administrator. Selamat datang! Anda sekarang dapat masuk dan mulai menggunakan layanan kami.</p>
                
                <!-- 3. Tombol Call to Action -->
                <a href="{{ $loginUrl }}" class="button">Masuk ke Akun Anda</a>
            </div>

            <!-- 4. Footer -->
            <div class="footer">
                <p>&copy; {{ date('Y') }} Nama Perusahaan Anda. <br>Semua Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </div>
</body>
</html>

