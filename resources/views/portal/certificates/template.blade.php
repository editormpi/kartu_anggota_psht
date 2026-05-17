<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat {{ $certificate->jenis }} — {{ $member->full_name }}</title>
    <style>
        @page { size: A4 landscape; margin: 0; }
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Times New Roman', Georgia, serif;
            color: #1a202c;
            background: #fff;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
        }

        .frame-outer {
            width: 100%;
            height: 100%;
            padding: 12mm;
            background:
                radial-gradient(circle at 20% 20%, rgba(245, 158, 11, 0.08), transparent 60%),
                radial-gradient(circle at 80% 80%, rgba(220, 38, 38, 0.08), transparent 60%),
                #fffdf6;
            border: 6px double #92400e;
        }

        .frame-inner {
            width: 100%;
            height: 100%;
            border: 2px solid #92400e;
            padding: 14mm 18mm;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .watermark {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.06;
            pointer-events: none;
        }

        .watermark .seal {
            width: 140mm;
            height: 140mm;
            border-radius: 50%;
            border: 8px solid #92400e;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32mm;
            font-weight: 900;
            color: #92400e;
            letter-spacing: 4mm;
        }

        .corner {
            position: absolute;
            width: 30mm;
            height: 30mm;
            border-color: #d97706;
            border-style: solid;
            border-width: 0;
        }
        .corner.tl { top: 4mm; left: 4mm; border-top-width: 3px; border-left-width: 3px; }
        .corner.tr { top: 4mm; right: 4mm; border-top-width: 3px; border-right-width: 3px; }
        .corner.bl { bottom: 4mm; left: 4mm; border-bottom-width: 3px; border-left-width: 3px; }
        .corner.br { bottom: 4mm; right: 4mm; border-bottom-width: 3px; border-right-width: 3px; }

        .header { z-index: 2; position: relative; margin-top: 4mm; }

        .org-mark {
            display: inline-flex;
            align-items: center;
            gap: 4mm;
            margin-bottom: 6mm;
        }

        .org-mark .logo {
            width: 16mm; height: 16mm;
            border-radius: 50%;
            background: #92400e;
            color: #fffdf6;
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 6mm; letter-spacing: 0.5mm;
        }

        .org-mark .org-name {
            text-align: left;
        }

        .org-mark .org-name .l1 {
            font-size: 3.5mm;
            text-transform: uppercase;
            letter-spacing: 1.5mm;
            color: #92400e;
            font-weight: 700;
        }

        .org-mark .org-name .l2 {
            font-size: 4.5mm;
            font-weight: 800;
            color: #1f2937;
        }

        h1.title {
            font-size: 18mm;
            font-weight: 900;
            color: #92400e;
            letter-spacing: 2mm;
            margin-top: 2mm;
            font-family: 'Georgia', serif;
        }

        .subtitle {
            font-size: 5mm;
            color: #6b7280;
            margin-top: 2mm;
            font-style: italic;
        }

        .content {
            margin-top: 12mm;
            position: relative;
            z-index: 2;
            line-height: 1.6;
        }

        .awarded-to {
            font-size: 4mm;
            color: #4b5563;
            margin-bottom: 4mm;
            letter-spacing: 1mm;
            text-transform: uppercase;
        }

        .name {
            font-size: 14mm;
            font-weight: 800;
            color: #1f2937;
            font-family: 'Georgia', serif;
            border-bottom: 1px solid #d1d5db;
            padding: 0 20mm 4mm;
            display: inline-block;
        }

        .description {
            font-size: 4.5mm;
            color: #374151;
            margin-top: 8mm;
            max-width: 200mm;
        }

        .description strong {
            color: #92400e;
            font-weight: 700;
        }

        .footer {
            margin-top: auto;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding: 0 10mm;
            position: relative;
            z-index: 2;
        }

        .meta {
            text-align: left;
            font-size: 3.5mm;
            color: #4b5563;
        }

        .meta .row { margin-bottom: 1.5mm; }
        .meta .row .label { color: #92400e; font-weight: 700; }

        .signature {
            text-align: center;
            font-size: 3.8mm;
        }

        .signature .place { color: #4b5563; margin-bottom: 1mm; }
        .signature .role { color: #4b5563; }
        .signature .line {
            width: 60mm;
            border-bottom: 1px solid #1f2937;
            margin: 16mm auto 1.5mm;
        }
        .signature .name-sign {
            font-weight: 700;
            color: #1f2937;
        }
    </style>
</head>
<body>
    <div class="frame-outer">
        <div class="frame-inner">
            <div class="corner tl"></div>
            <div class="corner tr"></div>
            <div class="corner bl"></div>
            <div class="corner br"></div>

            <div class="watermark">
                <div class="seal">PSHT</div>
            </div>

            <div class="header">
                <div class="org-mark">
                    <div class="logo">PSHT</div>
                    <div class="org-name">
                        <div class="l1">Persaudaraan Setia Hati Terate</div>
                        <div class="l2">Cabang Jember</div>
                    </div>
                </div>
                <h1 class="title">SERTIFIKAT</h1>
                <div class="subtitle">Certificate of Achievement</div>
            </div>

            <div class="content">
                <div class="awarded-to">Diberikan kepada</div>
                <div class="name">{{ $member->full_name }}</div>
                <p class="description">
                    atas keberhasilan menyelesaikan ujian kenaikan tingkat
                    <strong>{{ $certificate->jenis }}</strong>
                    @if ($member->tempat_lahir)
                        ({{ $member->tempat_lahir }}@if ($member->tanggal_lahir), {{ $member->tanggal_lahir->translatedFormat('d F Y') }}@endif)
                    @endif
                    sebagai anggota terdaftar PSHT Cabang Jember.
                </p>
            </div>

            <div class="footer">
                <div class="meta">
                    <div class="row"><span class="label">No. Sertifikat:</span> {{ $certificate->nomor }}</div>
                    <div class="row"><span class="label">Tanggal:</span> {{ $certificate->tanggal->translatedFormat('d F Y') }}</div>
                    @if ($member->ranting)
                        <div class="row"><span class="label">Ranting:</span> {{ $member->ranting }}</div>
                    @endif
                    @if ($member->rayon)
                        <div class="row"><span class="label">Rayon:</span> {{ $member->rayon }}</div>
                    @endif
                </div>

                <div class="signature">
                    <div class="place">Jember, {{ $certificate->tanggal->translatedFormat('d F Y') }}</div>
                    <div class="role">Ketua Cabang Jember</div>
                    <div class="line"></div>
                    <div class="name-sign">( .................................... )</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
