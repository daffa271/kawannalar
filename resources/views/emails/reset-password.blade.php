<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Atur Ulang Kata Sandi — KawanNalar</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #F4F7FA;
            font-family: Arial, Helvetica, sans-serif;
            color: #1E293B;
        }

        .wrapper {
            width: 100%;
            padding: 40px 16px;
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.08);
        }

        .header {
            background-color: #0A52C4;
            padding: 28px 24px;
            text-align: center;
        }

        .content {
            padding: 40px 36px;
        }

        .title {
            margin: 0 0 14px;
            font-size: 26px;
            line-height: 1.3;
            font-weight: 700;
            color: #1E293B;
        }

        .greeting {
            margin: 0 0 20px;
            font-size: 15px;
            line-height: 1.7;
            color: #334155;
        }

        .text {
            margin: 0 0 18px;
            font-size: 15px;
            line-height: 1.8;
            color: #64748B;
        }

        .button-wrapper {
            text-align: center;
            margin: 32px 0;
        }

        .button {
            display: inline-block;
            background-color: #F28C28;
            color: #FFFFFF !important;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            padding: 14px 28px;
            border-radius: 12px;
        }

        .notice {
            background-color: #F4F7FA;
            border-radius: 12px;
            padding: 16px 18px;
            margin: 24px 0;
        }

        .notice p {
            margin: 0;
            font-size: 13px;
            line-height: 1.7;
            color: #64748B;
        }

        .fallback {
            margin-top: 28px;
        }

        .fallback-title {
            margin: 0 0 8px;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
        }

        .fallback-url {
            display: block;
            word-break: break-all;
            font-size: 12px;
            line-height: 1.7;
            color: #0A52C4;
            text-decoration: none;
        }

        .footer {
            border-top: 1px solid #E2E8F0;
            padding: 24px 36px 30px;
            text-align: center;
        }

        .footer-text {
            margin: 0;
            font-size: 12px;
            line-height: 1.7;
            color: #94A3B8;
        }

        .footer-brand {
            margin-top: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #0A52C4;
        }

        @media only screen and (max-width: 600px) {
            .wrapper {
                padding: 20px 10px;
            }

            .content {
                padding: 32px 24px;
            }

            .footer {
                padding: 20px 24px 28px;
            }

            .title {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

<div class="wrapper">

    <div class="container">

        {{-- Header --}}
        <div class="header">
            <a
                href="{{ config('app.url') }}"
                style="color:#FFFFFF; font-size:26px; font-weight:700; letter-spacing:-0.5px; text-decoration:none;"
            >
                Kawan<span style="color:#F28C28;">Nalar</span>
            </a>
        </div>

        {{-- Content --}}
        <div class="content">

            <p class="greeting">
                Halo, {{ $user->name ?? 'Kawan Nalar' }} 👋
            </p>

            <h1 class="title">
                Atur Ulang Kata Sandi
            </h1>

            <p class="text">
                Kami menerima permintaan untuk mengatur ulang kata sandi
                akun KawanNalar kamu.
            </p>

            <p class="text">
                Klik tombol di bawah untuk membuat kata sandi baru.
            </p>

            {{-- Reset Button --}}
            <div class="button-wrapper">
                <a
                    href="{{ $url }}"
                    class="button"
                    style="background-color:#F28C28; color:#FFFFFF !important; text-decoration:none; font-size:15px; font-weight:700; padding:14px 28px; border-radius:12px;"
                >
                    Atur Ulang Kata Sandi
                </a>
            </div>

            {{-- Expiration --}}
            <div class="notice">
                <p>
                    <strong>Catatan:</strong>
                    Link reset kata sandi ini hanya berlaku selama 60 menit.
                </p>
            </div>

            {{-- Security Notice --}}
            <p class="text">
                Jika kamu tidak merasa meminta pengaturan ulang kata sandi,
                kamu dapat mengabaikan email ini. Akun kamu tetap aman.
            </p>

            {{-- Fallback URL --}}
            <div class="fallback">
                <p class="fallback-title">
                    Tidak bisa menekan tombol?
                </p>

                <a
                    href="{{ $url }}"
                    class="fallback-url"
                    style="color:#0A52C4; text-decoration:none; word-break:break-all;"
                >
                    {{ $url }}
                </a>
            </div>

        </div>

        {{-- Footer --}}
        <div class="footer">

            <p class="footer-text">
                Email ini dikirim secara otomatis oleh KawanNalar.
                Mohon tidak membalas email ini.
            </p>

            <div class="footer-brand">
                KawanNalar — Belajar Bersama, Raih Impian
            </div>

        </div>

    </div>

</div>

</body>
</html>