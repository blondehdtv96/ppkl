<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permohonan PKL - {{ $permohonan->siswa->name }}</title>
    <style>
        @page {
            margin: 2.5cm 2.5cm 2.5cm 2.5cm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
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
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('img/logo-sekolah.png') }}" alt="Logo Sekolah" class="logo">
            <h1 class="school-name">SMK NEGERI 1 CONTOH</h1>
            <p class="school-address">
                Jalan Pendidikan No. 123, Kecamatan Contoh, Kota Contoh, Provinsi Contoh<br>
                Telepon: (021) 1234567, Email: info@smkn1contoh.sch.id, Website: www.smkn1contoh.sch.id
            </p>
        </div>
        
        <!-- Letter Info -->
        <div class="letter-info">
            <div class="letter-number">
                <p>Nomor: {{ $permohonan->nomor_surat ?? 'PKL/' . str_pad($permohonan->id, 3, '0', STR_PAD_LEFT) . '/' . date('m') . '/' . date('Y') }}</p>
                <p>Lampiran: -</p>
                <p>Hal: Permohonan Praktik Kerja Lapangan</p>
            </div>
            
            <div class="letter-date">
                <p>{{ $permohonan->tanggal_surat ? $permohonan->tanggal_surat->format('d F Y') : now()->format('d F Y') }}</p>
            </div>
            
            <div class="clear"></div>
        </div>
        
        <!-- Recipient -->
        <div class="recipient">
            <p>Kepada Yth.</p>
            <p>Pimpinan {{ $permohonan->nama_perusahaan }}</p>
            <p>{{ $permohonan->alamat_perusahaan }}</p>
        </div>
        
        <!-- Subject -->
        <div class="subject">
            <p>PERMOHONAN PRAKTIK KERJA LAPANGAN (PKL)</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <p>Dengan hormat,</p>
            
            <p>Dalam rangka melaksanakan kurikulum SMK Negeri 1 Contoh, maka kami bermaksud mengajukan permohonan Praktik Kerja Lapangan (PKL) untuk siswa kami pada perusahaan yang Bapak/Ibu pimpin. Adapun pelaksanaan Praktik Kerja Lapangan tersebut rencananya akan dilaksanakan pada:</p>
            
            <table style="margin-left: 1cm; margin-bottom: 0.5cm;">
                <tr>
                    <td width="150">Tanggal</td>
                    <td>: {{ $permohonan->tanggal_mulai->format('d F Y') }} s/d {{ $permohonan->tanggal_selesai->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Durasi</td>
                    <td>: {{ $permohonan->tanggal_mulai->diffInDays($permohonan->tanggal_selesai) }} hari</td>
                </tr>
                <tr>
                    <td>Jurusan</td>
                    <td>: {{ $permohonan->siswa->jurusan }}</td>
                </tr>
            </table>
            
            <p>Untuk keperluan tersebut, kami menugaskan siswa kami:</p>
            
            <table style="margin-left: 1cm; margin-bottom: 0.5cm;">
                <tr>
                    <td width="150">Nama</td>
                    <td>: {{ $permohonan->siswa->name }}</td>
                </tr>
                <tr>
                    <td>NIS</td>
                    <td>: {{ $permohonan->siswa->nis }}</td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>: {{ $permohonan->siswa->kelas }}</td>
                </tr>
            </table>
            
            <p>Demikian permohonan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
        </div>
        
        <!-- Signature -->
        <div class="student-info">
            <p>Siswa yang bersangkutan,</p>
            <p class="student-name">{{ $permohonan->siswa->name }}</p>
            <p>NIS. {{ $permohonan->siswa->nis }}</p>
        </div>
        
        <div class="signature">
            <p>Kepala SMK Negeri 1 Contoh</p>
            <p class="signature-name">Drs. Nama Kepala Sekolah, M.Pd.</p>
            <p>NIP. 196012121980031001</p>
        </div>
        
        <div class="clear"></div>
        
        <!-- Footer -->
        <div class="footer">
            <p><em>Catatan: Surat ini sah tanpa stempel dan tanda tangan karena dicetak secara elektronik melalui Sistem Manajemen PKL.</em></p>
        </div>
        
        <!-- Print Info -->
        <div class="print-info">
            Dicetak oleh: {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})<br>
            Tanggal: {{ now()->format('d-m-Y H:i:s') }}
        </div>
    </div>
    
    <!-- Print Button -->
    <button class="print-button no-print" onclick="window.print()">
        <i class="fas fa-print"></i> Cetak Surat
    </button>
    
    <!-- Back Button -->
    <a href="{{ route('permohonan.show', $permohonan) }}" class="back-button no-print">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</body>
</html>