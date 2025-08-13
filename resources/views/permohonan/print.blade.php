<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permohonan PKL</title>
    <style>
        @page {
            size: A3;
            margin: 2.5cm 3.5cm 2.5cm 3.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 13pt;
            line-height: 1.8;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .surat-container {
            max-width: 32cm;
            margin: 0 auto;
            background: #fff;
            padding: 0 2.5cm 0 2.5cm;
        }
        
        .container {
            max-width: 21cm;
            margin: 0 auto;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 1.5cm;
            border-bottom: 3px solid #000;
            padding-bottom: 0.5cm;
        }
        
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 0.5cm;
        }
        
        .school-name {
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        
        .school-address {
            font-size: 10pt;
            margin: 0.2cm 0;
        }
        
        .letter-info {
            margin-bottom: 1cm;
        }
        
        .letter-number {
            float: left;
            margin-bottom: 1cm;
        }
        
        .letter-date {
            float: right;
            text-align: right;
            margin-bottom: 1cm;
        }
        
        .clear {
            clear: both;
        }
        
        .recipient {
            margin-bottom: 1cm;
        }
        
        .subject {
            text-align: center;
            font-weight: bold;
            margin: 1cm 0;
            text-decoration: underline;
        }
        
        .content {
            text-align: justify;
            margin-bottom: 1cm;
        }
        
        .content p {
            margin: 0.5cm 0;
            text-indent: 1cm;
        }
        
        .signature {
            float: right;
            width: 6cm;
            text-align: center;
            margin-top: 1cm;
        }
        
        .signature-name {
            font-weight: bold;
            margin-top: 2cm;
            border-top: 1px solid #000;
            padding-top: 0.2cm;
        }
        
        .footer {
            margin-top: 2cm;
            font-size: 10pt;
        }
        
        .student-info {
            margin-top: 1cm;
            float: left;
            width: 6cm;
        }
        
        .student-info p {
            margin: 0.2cm 0;
        }
        
        .student-name {
            font-weight: bold;
            margin-top: 2cm;
            border-top: 1px solid #000;
            padding-top: 0.2cm;
        }
        
        .print-info {
            position: fixed;
            bottom: 0;
            right: 0;
            font-size: 8pt;
            color: #999;
            padding: 0.2cm;
            background: #f9f9f9;
            border-top: 1px solid #ddd;
            border-left: 1px solid #ddd;
        }
        
        @media print {
            .print-info, .no-print {
                display: none;
            }
        }
        
        .print-button {
            position: fixed;
            top: 1cm;
            right: 1cm;
            padding: 0.5cm;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 0.2cm;
            cursor: pointer;
            font-size: 10pt;
        }
        
        .back-button {
            position: fixed;
            top: 1cm;
            left: 1cm;
            padding: 0.5cm;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 0.2cm;
            cursor: pointer;
            font-size: 10pt;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="surat-container">
        <table style="width:100%; border:0;">
            <tr>
                <td style="width:110px; text-align:center; vertical-align:top;">
                    <img src="{{ asset('1.png') }}" alt="Logo SMK BINA MANDIRI" style="height:90px; width:auto; display:block; margin:auto;">
                </td>
                <td style="vertical-align:middle; text-align:center; padding-left:0;">
                    <div style="font-family:'Times New Roman', Times, serif; color:#15397f; text-align:center;">
                        <div style="font-size:16pt; font-weight:700; letter-spacing:0.5px; text-transform:uppercase; line-height:1.2;">YAYASAN PENDIDIKAN BINA MANDIRI</div>
                        <div style="font-size:23pt; font-weight:900; letter-spacing:1px; text-transform:uppercase; line-height:1.1; margin-top:2px;">SMK BINA MANDIRI BEKASI</div>
                        <div style="font-size:13pt; font-weight:600; letter-spacing:0.5px; text-transform:uppercase; line-height:1.1; margin-top:2px;">KELOMPOK TERPADU</div>
                        <div style="font-size:18pt; font-weight:900; letter-spacing:0.5px; color:#15397f; text-transform:uppercase; line-height:1.1; margin-top:2px;">TERAKREDITASI "A"</div>
                    </div>
                </td>
            </tr>
        </table>
        <div style="width:100%; background:#17408B; color:#000; font-size:10pt; font-family:'Times New Roman', Times, serif; padding:4px 0 4px 0; margin-top:8px; margin-bottom:16px; text-align:center; border-radius:2px;">
            Jl. Bintara IX No.32 Bekasi Barat 17134 Telp: (021) 88860866, Fax : (021) 88860057 E-mail : smkbinamandiribks@gmail.com, Website : www.smkbinamandiribks.sch.id
        </div>
    <table style="width:100%; border:0; margin-bottom: 8px;">
        <tr>
            <td style="width:60%;">
                Nomor :
                @php
                    // Nomor surat otomatis: 423/PKL/{id}/{bulan}/{tahun}
                    $nomorSurat = $nomor_surat ?? ($permohonan->nomor_surat ?? null);
                    if (!$nomorSurat && isset($permohonan->id)) {
                        $bulan = \Carbon\Carbon::parse($permohonan->created_at ?? now())->format('m');
                        $tahun = \Carbon\Carbon::parse($permohonan->created_at ?? now())->format('Y');
                        $nomorSurat = '423/PKL/' . str_pad($permohonan->id, 3, '0', STR_PAD_LEFT) . '/' . $bulan . '/' . $tahun;
                    }
                @endphp
                {{ $nomorSurat ?? '-' }}
            </td>
            <td style="text-align:right;">Bekasi, {{ $tanggal_surat ?? (\Carbon\Carbon::now()->translatedFormat('d F Y')) }}</td>
        </tr>
        <tr>
            <td>Lamp : {{ $lampiran ?? '1 Lbr' }}</td>
            <td></td>
        </tr>
        <tr>
            <td>Perihal : <b>{{ $perihal ?? 'Permohonan PKL' }}</b></td>
            <td></td>
        </tr>
    </table>
    <div style="margin-bottom: 16px;">
        Kepada Yth :<br>
    Bpk/Ibu Pimpinan<br>
    <b>{{ $nama_perusahaan ?? 'PT. Bintang Teknologi Pratama' }}</b><br>
    {{ $alamat_perusahaan ?? 'Jl. Pulo Ribung No.06, Jakasetia, Bekasi Selatan, Kota Bekasi' }}
    </div>
    <div class="isi" style="margin-top: 20px; text-align:justify;">
        <p style="text-indent: 1.1cm; margin-bottom: 10px; font-size:11pt;">Dengan Hormat,</p>
        <p style="text-indent: 1.1cm; margin-bottom: 10px; font-size:11pt;">
            Dalam rangka pelaksanaan Pendidikan Sistem Ganda sesuai dengan UU <b>No.20 Tahun 2003</b> tentang <b>Pendidikan Nasional</b> dan <b>Peraturan Menteri Perindustrian Nomor 03/M-IND/PER/I/2017</b> tentang pedoman pembinaan dan pengembangan Sekolah Menengah Kejuruan Berbasis Kompetensi yang Link and Match dengan Industri dalam meningkatkan kompetensi tamatan Sekolah Menengah Kejuruan (SMK), dan sesuai Program Kurikulum serta Hubungan Industri SMK Bina Mandiri Bekasi, maka peserta didik SMK Bina Mandiri Bekasi wajib melaksanakan Praktik Kerja Lapangan (PKL) di Perusahaan/Instansi kurang lebih selama <b>3 (Tiga) Bulan</b>.
        </p>
        <p style="text-indent: 1.1cm; margin-bottom: 10px; font-size:11pt;">
            Sehubungan dengan hal tersebut kami mengajukan permohonan kepada Bapak/Ibu untuk dapat memberikan kesempatan kepada Peserta Didik kami melaksanakan Praktik Kerja Lapangan di Perusahaan/Instansi yang Bapak/Ibu Pimpin.
        </p>
        <p style="text-indent: 1.1cm; margin-bottom: 10px; font-size:11pt;">
            Pelaksanaan Praktik Kerja Lapangan tersebut kami rencanakan mulai tanggal <b>{{ $tanggal_mulai ?? ($permohonan->tanggal_mulai ? \Carbon\Carbon::parse($permohonan->tanggal_mulai)->translatedFormat('d F Y') : '01 Juli 2025') }}</b> sampai dengan <b>{{ $tanggal_selesai ?? ($permohonan->tanggal_selesai ? \Carbon\Carbon::parse($permohonan->tanggal_selesai)->translatedFormat('d F Y') : '30 September 2025') }}</b>. Adapun daftar peserta didik yang kami ajukan yaitu:
        </p>
        <div class="student-list" style="margin-left: 20px; margin-top: 10px; margin-bottom: 10px;">
            @php
                // Jika ada relasi siswa (banyak), gunakan, jika tidak, fallback ke user permohonan
                $daftarSiswa = [];
                if(isset($siswa)) {
                    $daftarSiswa = $siswa;
                } elseif(isset($permohonan->siswa) && is_iterable($permohonan->siswa)) {
                    $daftarSiswa = $permohonan->siswa;
                } elseif(isset($permohonan->users) && is_iterable($permohonan->users)) {
                    $daftarSiswa = $permohonan->users;
                } elseif(isset($permohonan->user)) {
                    $daftarSiswa = [$permohonan->user];
                }
            @endphp
            @foreach($daftarSiswa as $i => $item)
                {{ $i+1 }}. {{ $item->name }} (NIS/NISN: {{ $item->nis ?? $item->nisn }}) - {{ $item->kelas ?? '-' }} - {{ $item->konsentrasi_keahlian ?? $item->jurusan ?? '-' }}<br>
            @endforeach
        </div>
        <p style="text-indent: 1.2cm; margin-bottom: 12px;">
            Demikian surat permohonan ini kami sampaikan, atas perhatian dan kerjasama Bapak/Ibu kami ucapkan terima kasih.
        </p>
    </div>
    <div style="width:100%; margin-top:40px;">
        <div style="float:right; text-align:left; width:300px;">
            Kepala SMK Bina Mandiri Bekasi<br><br><br><br>
            <b>{{ $kepala_sekolah ?? 'Endah Sulistiani, S. Pd, M. Si.' }}</b>
        </div>
    </div>
    <div class="clear"></div>
    <div class="contact" style="margin-top:60px; font-size:10pt;">
        <b>Contact Person :</b><br>
        @if(isset($kontak) && is_array($kontak))
            @foreach($kontak as $cp)
                {{ $cp }}<br>
            @endforeach
        @else
            Rohman, S.Pd., MA : 0857 1884 8436<br>
            Indra Ridho P., S.T : 0812 8297 9669<br>
            Lulu Ani Asmaul H., S.Pd : 0822 6036 3190<br>
            Afifah Muthia I., A.Md., KA : 0877 8041 3599
        @endif
    </div>
    
    <!-- Print Button -->
    <button class="print-button no-print" onclick="window.print()">
        <i class="fas fa-print"></i> Cetak Surat
    </button>
    
    <!-- Back Button -->
    <a href="{{ route('permohonan.show', $permohonan) }}" class="back-button no-print">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
    </div>
</body>
</html>