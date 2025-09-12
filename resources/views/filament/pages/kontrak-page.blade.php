<!DOCTYPE html>
<html>
<head>
    <title>KONTRAK {{ strtoupper($kontrak->nama_pk_kda) }} DIBUAT PADA {{ strtoupper(Carbon\Carbon::createFromFormat('Y-m-d', $kontrak->tgl_dibuat)->translatedFormat('j F Y')) }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @page {
            margin: 0cm; /* remove all margins */
            line-height: 1;
          }
          
        body {
            font-family: "Times", serif;
            font-size: 12pt;
            font-weight: 400;
            -webkit-font-smoothing: subpixel-antialiased;
            text-align: justify;
        }
        
        .w-full { width: 100%;}
        .w-8-5 { width: 8.5in; max-width: 8.5in; }
        .w-16 { width: 16pt; }
        .w-120 { width: 120pt; }
        .w-70 { width: 70pt; }
        .w-20 { width: 20pt; }
        .w-94 { width: 94pt; }
        .w-100 { width: 100pt; }
        .w-24 { width: 24pt; }
        
        .min-h-11 { min-height: 11in; max-height: 11in; }
        .h-20 { height: 20pt; }
        
        .bg-white { background-color: white; }
        
        .flex { display: block; }
        .flex-col { margin-bottom: 0px; }
        
        .mt-6 { margin-top: -6pt; } 
        .mt-20 { margin-top: 20pt; }
        .mt-12 { margin-top: 12pt; }
        .mt-10 { margin-top: 10pt; }
        .mt-70 { margin-top: 70pt; }
        .mx-70 { margin-left: 70pt; margin-right: 70pt; }
        .ml-10 { margin-left: 10pt; }
        .ml-12 { margin-left: 12pt; }
        .mb-60 { margin-bottom: 60pt; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        
        .px-20 { padding-left: 20pt; padding-right: 20pt; }
        .py-4 { padding-left: 1rem; padding-right: 1rem; }
        
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .font-bold { font-weight: 700; }
        
        .underline {text-decoration-line: underline !important;}
        .underline-offset-2-64 { text-underline-offset: 2.64pt; }
        .underline-offset-2-7 { text-underline-offset: 2.7pt; }
        .leading-tight { line-height: 1.25; }
        
        .table-auto { table-layout: auto; }
        .table-fixed { table-layout: fixed; }
        .table-row { display: table-row; }
        
        .border-collapse { border-collapse: collapse; }
        .border {border: 1px solid black;}
        .border-slate-600 { border: 1px solid #475569; }
        
        img { height: 4.02cm; width: 19.7cm; }
        p {margin: 0;}
        td {line-height: 1.15;}
    </style>
</head>
<body>
    @php
        $carbon = Carbon\Carbon::createFromFormat('Y-m-d', $kontrak->tgl_dibuat);
        $hari = $carbon->translatedFormat('l');
        $tgl = $carbon->translatedFormat('j');
        $bln = $carbon->translatedFormat('F');
        $year = $carbon->translatedFormat('Y');
        $strKontrak = Carbon\Carbon::createFromFormat('Y-m-d', $kontrak->tgl_mulai_kontrak)->translatedFormat('j F Y');
        $endKontrak = Carbon\Carbon::createFromFormat('Y-m-d', $kontrak->tgl_selesai_kontrak)->translatedFormat('j F Y');
        $jangkaKontrak = Carbon\Carbon::createFromFormat('Y-m-d', $kontrak->tgl_mulai_kontrak)->diffInMonths(Carbon\Carbon::createFromFormat('Y-m-d', $kontrak->tgl_selesai_kontrak))
    @endphp
     <!--Hal 1-->
        <div class="w-8-5 min-h-11 bg-white flex flex-col">
          <img src={{ public_path('assets/header.png') }} alt="header" class="mt-6 px-20" />
          <div class="mx-70 leading-tight">
            <p
              style="text-align: center; font-weight: bold; text-decoration: underline; text-underline-offset: 2.64px; text-decoration-thickness: 1.5pt;"
            >
              SURAT PERJANJIAN KERJA WAKTU <br /> TERTENTU (KONTRAK)
            </p>
            <p class="text-center">Nomor : {{ $kontrak->no_srt }}</p>
            <div class="text-justify">
              <p class="mt-20">
                Pada hari ini, {{ $hari }} tanggal {{ $tgl }} bulan {{ $bln }} tahun {{ $year }}, telah
                dibuat dan disepakati perjanjian kerja antara:
              </p>
              <div class="ml-10">
                <table class="table-auto">
                  <tbody>
                    <tr class="table-row">
                      <td class="w-16">I.</td>
                      <td class="w-120">Nama</td>
                      <td>: {{ ucwords(strtolower($kontrak->nama_pk_ptm)) }}</td>
                    </tr>
                    <tr class="table-row">
                      <td></td>
                      <td>Alamat</td>
                      <td>: {{ $kontrak->alamat_pk_ptm }}</td>
                    </tr>
                    <tr class="table-row">
                      <td></td>
                      <td>Jabatan</td>
                      <td>: {{ $kontrak->jabatan_pk_ptm }}</td>
                    </tr>
                    <tr>
                      <td></td>
                      <td colspan="2">
                        <p class="mt-12">
                          Dalam hal ini bertindak untuk dan atas nama 
                          <b>PT Surya Amanah Cendekia Ponorogo</b> yang
                          selanjutnya disebut sebagai <b> PIHAK PERTAMA.</b>
                        </p>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table class=" table-auto w-full mt-12">
                  <tbody>
                    <tr class="table-row">
                      <td class="w-16">II.</td>
                      <td class="w-120">Nama</td>
                      <td>: {{ ucwords(strtolower($kontrak->nama_pk_kda)) }}</td>
                    </tr>
                    <tr class="table-row">
                      <td></td>
                      <td>Tempat/ Tgl Lahir</td>
                      <td>: {{ $kontrak->tempat_lahir_pk_kda }}, {{ $kontrak->tgl_lahir_pk_kda }}</td>
                    </tr>
                    <tr class="table-row">
                      <td></td>
                      <td>NIK</td>
                      <td>: {{ $kontrak->nik_pk_kda }}</td>
                    </tr>
                    <tr class="table-row">
                      <td></td>
                      <td>Alamat</td>
                      <td>
                        : {{ $kontrak->alamat_pk_kda }}
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td colspan="2">
                        <p class="mt-12">
                          Dalam hal ini bertindak untuk dan atas nama diri
                          sendiri. Selanjutnya dalam Perjanjian ini disebut 
                          <b>PIHAK KEDUA.</b>
                        </p>
                        <p class="mt-12 text-justify">
                          PIHAK PERTAMA dan PIHAK KEDUA secara bersama-sama
                          disebut KEDUA PIHAK.
                        </p>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <p class="font-bold text-center mt-10">
                Pasal 1 <br />
                LATAR BELAKANG
              </p>
              <table style="width: 100%; border-collapse: collapse;">
                <tbody>
                  <tr>
                    <td style="vertical-align: top; width: 22pt;">1.1</td>
                    <td style="text-align: justify; word-break: normal; white-space: normal;">
                      PIHAK PETAMA merupakan perusahan yang bergerak dibidang
                      perdagangan dan pelayanan umum
                    </td>
                  </tr>
                  <tr>
                    <td style="vertical-align: top;">1.2</td>
                    <td>
                      Bahwa setelah diadakan penilaian oleh PIHAK PERTAMA, PIHAK
                      KEDUA dinyatakan memenuhi persyaratan dan bersedia bekerja
                      dalam jangka waktu tertentu.
                    </td>
                  </tr>
                  <tr>
                    <td style="vertical-align: top;">1.3</td>
                    <td>
                      PIHAK PERTAMA dengan ini memperkerjakan PIHAK KEDUA untuk
                      jangka waktu tertentu.
                    </td>
                  </tr>
                  <tr>
                    <td style="vertical-align: top;">1.4</td>
                    <td>
                      Bahwa PIHAK KEDUA menyetujui bekerja selama jangka waktu
                      tertentu sebagaimana dimaksud dalam perjanjian ini untuk
                      PIHAK PERTAMA.
                    </td>
                  </tr>
                </tbody>
              </table>
              <p>
                Setelah memperhatikan pertimbangan tersebut di atas dengan ini
                dicapai kata sepakat antara KEDUA PIHAK untuk mengikatkan diri
                dalam mengadakan Perjanjian Kerja Waktu Tertentu (Selanjutnya
                disebut dengan Perjanjian) dengan ketentuan dan syarat - syarat
                sebagai berikut:
              </p>
            </div>
          </div>
          <footer style="position: absolute; bottom: 0; width: 100%; text-align: center; padding: 1rem 0;">
            <table class="table-auto mx-auto border-collapse border">
                <thead>
                    <tr>
                        <th class="w-70 border border-slate-600">Pihak 1</th>
                        <th class="w-70 border border-slate-600">Pihak 2</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="h-20 border border-slate-600"></td>
                        <td class="h-20 border border-slate-600"></td>
                    </tr>
                </tbody>
            </table>
        </footer>

        </div>
        <!--{/* Hal 2 */}-->
        <div class="w-8-5 min-h-11 bg-white flex flex-col">
          <img src={{ public_path('assets/header.png') }} alt="header" class="mt-6 px-20" />
          <div class="mx-70 leading-tight flex-grow">
            <p class="font-bold text-center">
              Pasal 2 <br />
              RUANG LINGKUP PEKERJAAN DAN PENEMPATAN
            </p>
            <table style="width: 100%; border-collapse: collapse;">
              <tbody>
                <tr>
                  <td style="vertical-align: top; width: 22pt;">2.1</td>
                  <td style="text-align: justify; word-break: normal; white-space: normal;">
                    PIHAK PERTAMA menetapkan PIHAK KEDUA untuk bekerja di PT
                    Surya Amanah Cendekia Ponorogo sebagai:
                    <table>
                      <tbody>
                        <tr>
                          <td class="w-20">a.</td>
                          <td class="w-94">Jabatan</td>
                          <td>: {{ ucwords(strtolower($kontrak->jabatan_pk_kda)) }}</td>
                        </tr>
                        <tr>
                          <td>b.</td>
                          <td>Status</td>
                          <td>: {{ $kontrak->status_pk_kda }}</td>
                        </tr>
                        <tr>
                          <td>c.</td>
                          <td>Unit Kerja</td>
                          <td>: {{ $kontrak->unit_pk_kda }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">2.2</td>
                  <td>
                    Perjanjian ini dibuat untuk jangka waktu yang dimulai pada tanggal {{ $strKontrak }} dan berakhir pada tanggal {{ $endKontrak }}. Perjanjian ini dapat diperpanjang kembali
                    sesuai dengan ketentuan yang berlaku.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">2.3</td>
                  <td>
                    PIHAK KEDUA menyadari sepenuhnya dan menyetujui untuk
                    dikaryakan pada PERUSAHAAN tersebut di atas sesuai dengan
                    kondisi dan ketentuan dalam perjanjian ini
                  </td>
                </tr>
              </tbody>
            </table>
            <p class="font-bold text-center">
              Pasal 3 <br />
              TUGAS DAN TANGGUNGJAWAB
            </p>
            <table style="width: 100%; border-collapse: collapse;">
              <tbody>
                <tr>
                  <td style="vertical-align: top; width: 22pt;">3.1.</td>
                  <td style="text-align: justify; word-break: normal; white-space: normal;">
                    PIHAK PERTAMA menempatkan PIHAK KEDUA sebagai tenaga
                    PERUSAHAAN dengan rincian tugas sebagaimana yang diatur oleh
                    PERUSAHAAN.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">3.2.</td>
                  <td>
                    PIHAK KEDUA wajib bertanggungjawab atas segala sikap dan
                    perilaku dalam menjalankan tugas, serta wajib mentaati semua
                    peraturan dan tata tertib yang berlaku di PERUSAHAAN.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">3.3.</td>
                  <td>
                    PIHAK KEDUA bersedia tunduk dan melaksanakan seluruh
                    ketentuan yang telah diatur dalam Peraturan Perusahaan dan
                    tata tertib perusahaan maupun ketentuan lain yang menjadi
                    keputusan Direksi dan Manajemen Perusahaan. PIHAK KEDUA
                    bersedia menyimpan dan menjaga kerahasiaan baik dokumen
                    maupun informasi milik PIHAK PERTAMA dan tidak dibenarkan
                    memberikan dokumen atau informasi yang diketahui baik secara
                    lisan maupun tertulis kepada pihak lain.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">3.4.</td>
                  <td>
                    PIHAK PERTAMA berhak memutasikan PIHAK KEDUA untuk bekerja
                    di tempat dan posisi yang lain selain yang tercantum di
                    dalam perjanjian ini selama masa perjanjian ini berlangsung
                    dianggap perlu.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">3.5.</td>
                  <td>
                    PIHAK KEDUA bertanggungjawab penuh terhadap peralatan kerja
                    milik PIHAK PERTAMA.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">3.6.</td>
                  <td>
                    PIHAK KEDUA wajib membaca, mengerti, memahami, mematuhi dan
                    menaati sepenuhnya setiap ketentuan dan peraturan yang
                    berlaku pada PIHAK PERTAMA dan ketentuan yang berlaku pada
                    PERUSAHAAN.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">3.7.</td>
                  <td>
                    PIHAK KEDUA berkewajiban menyelesaikan masa kontrak yang
                    telah di tandatangani.
                  </td>
                </tr>
              </tbody>
            </table>
            <p class="font-bold text-center">
              Pasal 4 <br />
              KOMPENSASI
            </p>
            <table style="width: 100%; border-collapse: collapse;">
              <tbody>
                <tr>
                  <td style="vertical-align: top; width: 22pt">4.1.</td>
                  <td style="text-align: justify; word-break: normal; white-space: normal;">
                    Atas pekerjaan ini PIHAK KEDUA berhak untuk memperoleh
                    Kompensasi sebagai berikut:
                    <table class="ml-12">
                      <tbody>
                        <tr>
                          <td class="w-20">a.</td>
                          <td class="{{ $kontrak->tj_hadir == 0 || $kontrak->kinerja == 0 ? 'w-100' : '' }}">Gaji Pokok</td>
                          <td class="{{ $kontrak->tj_hadir == 0 || $kontrak->kinerja == 0 ? 'w-24' : '' }}">: Rp</td>
                          <td class="{{ $kontrak->tj_hadir == 0 || $kontrak->kinerja == 0 ? 'text-start' : 'text-end' }}"> {{ $kontrak->g_pok }},- / bulan</td>
                        </tr>
                        @if($kontrak->tj_hadir == 0 || $kontrak->kinerja == 0)
                        <tr>
                          <td style="vertical-align: top;">b.</td>
                          <td colspan="3">Lembur dan insentif dengan perhitungan mengikuti ketentuan yang ditetapkan perusahaan.</td>
                        </tr>
                        @else
                            @if($kontrak->tj_hadir)
                            <tr>
                              <td>b.</td>
                              <td>Tunjangan Kehadiran</td>
                              <td>: Rp</td>
                              <td class="text-end"> {{ $kontrak->tj_hadir }},- / bulan</td>
                            </tr>
                            @endif
                            @if($kontrak->kinerja)
                            <tr>
                              <td>c.</td>
                              <td>Kinerja</td>
                              <td>: Rp</td>
                              <td class="text-end"> {{ $kontrak->kinerja }},- / bulan</td>
                            </tr>
                            @endif
                        @endif
                        @if($kontrak->lain_lain)
                        <tr>
                          <td>{{ ($kontrak->tj_hadir == 0 || $kontrak->kinerja == 0) ? 'b.' : 'd.' }}</td>
                          <td>Lain - lain</td>
                          <td>: Rp</td>
                          <td class="text-end"> {{ $kontrak->lain_lain }},- / bulan</td>
                        </tr>
                        @endif
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">4.2.</td>
                  <td>
                    Pembayaran kompensasi dilakukan setiap tanggal 1 (satu) pada
                    bulan berjalan, yang apabila jatuh pada hari libur maka akan
                    dimajukan pada hari kerja terdekat.(per/bulan)
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <footer style="position: absolute; bottom: 0; width: 100%; text-align: center; padding: 1rem 0;">
            <table class="table-auto mx-auto border-collapse border">
                <thead>
                    <tr>
                        <th class="w-70 border border-slate-600">Pihak 1</th>
                        <th class="w-70 border border-slate-600">Pihak 2</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="h-20 border border-slate-600"></td>
                        <td class="h-20 border border-slate-600"></td>
                    </tr>
                </tbody>
            </table>
        </footer>

        </div>
        <!--{/* Hal 3 */}-->
        <div class="w-8-5 min-h-11 bg-white flex flex-col">
          <img src={{ public_path('assets/header.png') }} alt="header" class="mt-6 px-20" />
          <div class="mx-70 leading-tight flex-grow">
            <p class="font-bold text-center">
              Pasal 5 <br />
              TUNJANGAN - TUNJANGAN
            </p>
            <table style="width: 100%; border-collapse: collapse;">
              <tbody>
                <tr>
                  <td style="vertical-align: top; width: 22pt;" }}>5.1.</td>
                  <td style="text-align: justify; word-break: normal; white-space: normal;">
                    Tunjangan Hari Raya (THR) <br />
                    PIHAK KEDUA berhak atas THR sebesar 1 (satu) bulan Gaji
                    Pokok apabila telah bekerja selama 12 (dua belas) bulan
                    berturut –turut. Apabila kurang dari 12 (dua belas) bulan
                    setelah masa percobaan <i>(training)</i> maka perhitungan
                    THR dilakukan secara prorata.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">5.2.</td>
                  <td>
                    Jaminan Sosial <br />
                    Program jaminan sosial yang diberikan sebagai fasilitas tenaga
                    kerja adalah jaminan sosial yang diberikan oleh Badan
                    Penyelenggara Jaminan Sosial (BPJS) Kesehatan dan/atau BPJS
                    Ketenagakerjaan.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">5.3.</td>
                  <td>
                    Dana Sosial <br />
                    Dana sosial yang diberikan sesuai dengan peraturan
                    perusahaan.
                  </td>
                </tr>
              </tbody>
            </table>
            <p class="font-bold text-center">
              Pasal 6 <br />
              WAKTU KERJA DAN LEMBUR
            </p>
            <table style="width: 100%; border-collapse: collapse;">
              <tbody>
                <tr>
                  <td style="vertical-align: top; width: 22pt;">6.1.</td>
                  <td style="text-align: justify; word-break: normal; white-space: normal;">
                    Jam kerja karyawan yaitu berdasarkan jadwal yang telah
                    ditetapkan oleh PERUSAHAAN
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">6.2.</td>
                  <td>
                    Jika dipandang perlu PERUSAHAAN dapat menugaskan PIHAK KEDUA
                    untuk bekerja di luar jam kerja dengan ketentuan yang
                    berlaku.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">6.3.</td>
                  <td>
                    Jika PIHAK KEDUA melaksanakan kerja lembur, maka PIHAK KEDUA
                    berhak atas upah lembur berdasarkan aturan dan ketentuan
                    pada PERUSAHAAN. Pelaksanaan lembur harus berdasarkan
                    perintah lembur dari atasan yang berwenang di PERUSAHAAN.
                  </td>
                </tr>
              </tbody>
            </table>
            <p class="font-bold text-center">
              Pasal 7 <br />
              SANKSI DAN EVALUASI
            </p>
            <table style="width: 100%; border-collapse: collapse;">
              <tbody>
                <tr>
                  <td style="vertical-align: top; width: 22pt;">7.1.</td>
                  <td style="text-align: justify; word-break: normal; white-space: normal;">
                    PIHAK PERTAMA melakukan evaluasi kinerja PIHAK KEDUA setiap
                    3 (tiga) bulan sekali.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">7.2.</td>
                  <td>
                    PIHAK PERTAMA dapat melakukan evaluasi secara mendadak
                    apabila terjadi beberapa kondisi sebagai berikut :
                    <table>
                      <tbody>
                        <tr>
                          <td
                            style="vertical-align: top;"
                            class="w-20"
                          >
                            a.
                          </td>
                          <td>
                            Mangkir selama 2 (dua) hari berturut-turut tanpa
                            memberikan keterangan yang sah kepada PERUSAHAAN
                            dan/atau PIHAK PERTAMA.
                          </td>
                        </tr>
                        <tr>
                          <td style="vertical-align: top;">b.</td>
                          <td>
                            PIHAK KEDUA tidak dapat menjalankan tugas yang
                            berkaitan dengan tanggung jawabnya atau melalaikan
                            tanggung jawabnya.
                          </td>
                        </tr>
                        <tr>
                          <td style="vertical-align: top;">c.</td>
                          <td>
                            Kondisi lain yang dapat merugikan PIHAK PERTAMA. 
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top; width: 22pt;">7.3.</td>
                  <td>
                    PIHAK PERTAMA akan memberikan surat peringatan (SP I/II/III)
                    kepada PIHAK KEDUA dengan melihat jenis pelanggaran sesuai
                    dengan Peraturan Perusahaan
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top; width: 22pt;">7.4.</td>
                  <td>
                    PIHAK PERTAMA dapat memberikan sanksi Pengakhiran Hubungan
                    Kerja kepada PIHAK KEDUA tanpa peringatan (SP I/II/III)
                    terlebih dahulu apabila terbukti PIHAK KEDUA telah melakukan
                    kesalahan berat dan/atau membahayakan perusahaan sebagaimana
                    dimaksud dalam Undang-undang dan peraturan Ketenagakerjaan
                    yang berlaku
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top; width: 22pt;">7.5.</td>
                  <td>
                    Dalam Hal apabila PIHAK KEDUA mengakhiri kontrak secara
                    sepihak sebelum masa kerja selesai, maka PIHAK KEDUA akan
                    dikenakan denda dengan mengganti seluruh kompensasi senilai
                    masa kontrak yang tersisa.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <footer style="position: absolute; bottom: 0; width: 100%; text-align: center; padding: 1rem 0;">
            <table class="table-auto mx-auto border-collapse border">
                <thead>
                    <tr>
                        <th class="w-70 border border-slate-600">Pihak 1</th>
                        <th class="w-70 border border-slate-600">Pihak 2</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="h-20 border border-slate-600"></td>
                        <td class="h-20 border border-slate-600"></td>
                    </tr>
                </tbody>
            </table>
        </footer>

        </div>
        <!--{/* Hal 4 */}-->
        <div class="w-8-5 min-h-11 bg-white flex flex-col">
          <img src={{ public_path('assets/header.png') }} alt="header" class="mt-6 px-20" />
          <div class="mx-70 leading-tight flex-grow">
            <p class="font-bold text-center">
              Pasal 8 <br />
              PENGAKHIRAN DAN PERPANJANGAN MASA KERJA
            </p>
            <table style="width: 100%; border-collapse: collapse;">
              <tbody>
                <tr>
                  <td style="vertical-align: top; width: 22pt;">8.1.</td>
                  <td style="text-align: justify; word-break: normal; white-space: normal;">
                    Pengakhiran masa kerja anatar KEDUA PIHAK terjadi apabila :
                    <table>
                      <tbody>
                        <tr>
                          <td style="vertical-align: top; width: 20pt;">
                            a.
                          </td>
                          <td>PIHAK KEDUA Meninggal dunia</td>
                        </tr>
                        <tr>
                          <td style="vertical-align: top; width: 20pt;">
                            b.
                          </td>
                          <td>Masa perjanjian telah berakhir</td>
                        </tr>
                        <tr>
                          <td style="vertical-align: top; width: 20pt;">
                            c.
                          </td>
                          <td>
                            Adanya putusan pengadilan dan/atau putusan atau
                            penetapan lembaga penyelesaian perselisihan hubungan
                            industrial yang telah mempunyai kekuatan hukum tetap
                          </td>
                        </tr>
                        <tr>
                          <td style="vertical-align: top; width: 20pt;">
                            d.
                          </td>
                          <td>
                            Terjadi kondisi darurat <i>(Force Majure)</i>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top; width: 22pt;">8.2.</td>
                  <td>
                    PIHAK KEDUA dapat mengakhiri kontrak lebih awal dengan
                    melakukan kewajiban sebagai berikut:
                    <table>
                      <tbody>
                        <tr>
                          <td style="vertical-align: top; width: 20pt;">
                            a.
                          </td>
                          <td>
                            Mengajukan permohonan pengunduran diri tertulis
                            minimal 15 (lima belas) hari sebelumnya
                          </td>
                        </tr>
                        <tr>
                          <td style="vertical-align: top; width: 20pt;">
                            b.
                          </td>
                          <td>
                            Mengembalikan seluruh seragam yang telah diterima
                          </td>
                        </tr>
                        <tr>
                          <td style="vertical-align: top; width: 20pt;">
                            c.
                          </td>
                          <td>Mendapatkan persetujuan pimpinan</td>
                        </tr>
                        <tr>
                          <td style="vertical-align: top; width: 20pt;">
                            d.
                          </td>
                          <td>
                            Menyelesaikan semua kewajiban tertulis diluar
                            perjanjian ini bilamana ada
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top; width: 22pt;">8.3.</td>
                  <td>
                    Bilamana PIHAK KEDUA akan memperpanjang Perjanjian Kerja,
                    maka PIHAK KEDUA diwajibkan mengajukan permohonan
                    perpanjangan kepada PIHAK PERTAMA paling lambat 7 (tujuh)
                    hari sebelum Perjanjian Kerja ini berakhir dan dengan
                    kesepakatan kedua belah pihak dibuatkan perpanjangan
                    Perjanjian Kerja.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top; width: 22pt;">8.4.</td>
                  <td>
                    Dalam hal Perjanjian Kerja ini tidak diperpanjang maka
                    sesuai kesepakatan antara PIHAK PERTAMA dan PIHAK KEDUA,
                    maka Perjanjian Kerja ini akan putus demi hukum pada tanggal
                    yang telah disepakati, sehingga kedua belah pihak berakhir
                    dengan sendirinya.
                  </td>
                </tr>
              </tbody>
            </table>
            <p class="font-bold text-center">
              Pasal 9 <br />
              <i>FORCE MAJURE</i>
            </p>
            <table style="width: 100%; border-collapse: collapse;">
              <tbody>
                <tr>
                  <td style="vertical-align: top; width: 22pt;">9.1.</td>
                  <td style="text-align: justify; word-break: normal; white-space: normal;">
                    Kegagalan salah satu pihak untuk melaksanakan perjanjian
                    kerja ini yang disebabkan oleh <i>force majure</i> tidak
                    dianggap sebagai pelanggaran terhadap perjanjian ini. FORCE
                    MAJURE adalah segala keadaan atau peristiwa yang terjadi di
                    luar batas kekuasaan KEDUA PIHAK, termasuk akan tetapi tidak
                    terbatas pada huru hara, epidemic, kebakaran, banjir gempa
                    bumi, pemogokan, perang, keputusan pemerintah yang
                    menghalangi KEDUA PIHAK secara langsung untuk melaksanakan
                    kewajiban-kewajiban sesuai peraturan terjadi.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">9.2.</td>
                  <td>
                    Dalam hal terjadinya satu atau beberapa kejadian atau
                    peristiwa Force Majure, pihak yang menderita berkewajiban
                    untuk memberitahukan secara tertulis kepada pihak lainnya
                    saat kejadian terjadi.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">9.3.</td>
                  <td>
                    Jika kondisi Force Majure terjadi selama jangka waktu lebih
                    dari 60 (Enam Puluh) hari maka salah satu pihak berhak untuk
                    mengakhiri Perjanjian.
                  </td>
                </tr>
              </tbody>
            </table>
            <p class="font-bold text-center">
              Pasal 10 <br />
              PENYELESAIAN PERSELISIHAN
            </p>
            <p>
              KEDUA PIHAK setuju untuk menyelesaikan secara kekeluargaan setiap
              perselisihan hubungan industrial dalam hal perselisihan hak,
              perselisihan kepentingan dan perselisihan PHK yang berkaitan
              dengan pelaksanaan perjanjian ini.
            </p>
          </div>
          <footer style="position: absolute; bottom: 0; width: 100%; text-align: center; padding: 1rem 0;">
            <table class="table-auto mx-auto border-collapse border">
                <thead>
                    <tr>
                        <th class="w-70 border border-slate-600">Pihak 1</th>
                        <th class="w-70 border border-slate-600">Pihak 2</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="h-20 border border-slate-600"></td>
                        <td class="h-20 border border-slate-600"></td>
                    </tr>
                </tbody>
            </table>
        </footer>

        </div>
        <!--{/* Hal 5 */}-->
        <div class="w-8-5 min-h-11 bg-white flex flex-col">
          <img src={{ public_path('assets/header.png') }} alt="header" class="mt-6 px-20" />
          <div class="mx-70 leading-tight flex-grow">
            <p class="font-bold text-center">
              Pasal 11 <br />
              PENUTUP
            </p>
            <table style="width: 100%; border-collapse: collapse;">
              <tbody>
                <tr>
                  <td style="vertical-align: top; width: 22pt;">11.1.</td>
                  <td style="text-align: justify; word-break: normal; white-space: normal;">
                    Surat perjanjian kerja ini dibuat dan ditandatangani oleh
                    KEDUA PIHAK dengan tanpa ada pengaruh dan paksaan dari
                    siapapun serta mengikat KEDUA PIHAK untuk menaati dan
                    melaksanakan dengan penuh tanggungjawab.
                  </td>
                </tr>
                <tr>
                  <td style="vertical-align: top; width: 22pt;">11.2.</td>
                  <td>
                    Apabila dikemudian hari Surat Perjanjian ini ternyata masih
                    terdapat hal – hal yang sekiranya bertentangan dengan
                    peraturan perundang – undangan, ketenagakerjaan Republik
                    Indonesia dan atau perkembangan Peraturan Perusahaan, maka
                    akan diadakan peninjauan dan penyesuaian atas persetujuan
                    KEDUA PIHAK.
                  </td>
                </tr>
              </tbody>
            </table>
            <p class="mt-12">
              Surat Perjanjian ini dibuat dan ditandatangani oleh KEDUA PIHAK di
              PONOROGO pada tanggal, bulan dan tahun tersebut di atas dalam
              rangkap 2 (dua) yang memiliki kekuatan hukum yang sama dan
              dipegang oleh masing – masing pihak
            </p>
            <div style="width: 100%; text-align: center; margin-top: 70px;">
                <!--Pihak Pertama-->
              <div style="display: inline-block; width: 40%; vertical-align: top; text-align: center; margin-right: 5%; position:relative;">
                <p style="margin-bottom: 70px;">PIHAK PERTAMA</p>
                @if($kontrak->ttd_atasan != null)
                    <img src={{ public_path('assets/Stampel.png') }} style="width: 3cm; height: auto; opacity: 60%; position: absolute; z-index: 3; top: -2%; left: 10%; rotate: 15deg;">
                    <img src={{ public_path('assets/tapak.png') }} style="width: 125px; height: auto; position: absolute; z-index: 2; top: 1%; left: 26%;">
                @endif
                <p style="font-weight: bold; text-decoration: underline; text-decoration-thickness: 2pt;">
                  {{ $kontrak->nama_pk_ptm }}<br />
                </p>
                <span style="font-weight: bold;">{{ $kontrak->jabatan_pk_ptm }}</span>
              </div>
            <!--Pihak Kedua-->
              <div style="display: inline-block; width: 40%; vertical-align: top; text-align: center; position: relative;">
                <p style="margin-bottom: 70px;">PIHAK KEDUA</p>
                @if($kontrak->ttd != null)
                    <img src="data:image/svg+xml;base64,{{ base64_encode($kontrak->ttd) }}" style="width: 125px; height: auto; position: absolute; z-index: 2; top: 4%; left: 24%;">
                @endif
                <p style="font-weight: bold; text-decoration: underline; text-decoration-thickness: 2pt;">
                  {{ ucwords(strtolower($kontrak->nama_pk_kda)) }}
                </p>
              </div>
            </div>

          </div>
          <footer style="position: absolute; bottom: 0; width: 100%; text-align: center; padding: 1rem 0;">
            <table class="table-auto mx-auto border-collapse border">
                <thead>
                    <tr>
                        <th class="w-70 border border-slate-600">Pihak 1</th>
                        <th class="w-70 border border-slate-600">Pihak 2</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="h-20 border border-slate-600"></td>
                        <td class="h-20 border border-slate-600"></td>
                    </tr>
                </tbody>
            </table>
        </footer>
        </div>
</body>
</html>
