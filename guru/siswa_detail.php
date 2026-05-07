<?php
$id = $_GET['id'] ?? 0;
$id_guru = $_SESSION['guru']['id_guru'];

// Verifikasi guru memiliki hak lihat (merupakan Wali Kelas dari siswa ini)
$cek_hak = $koneksi->query("
    SELECT * FROM kelas k 
    JOIN siswakelas sk ON k.id_kelas = sk.id_kelas 
    WHERE k.id_guru = '$id_guru' AND sk.id_siswa = '$id'
");

if ($cek_hak->num_rows == 0) {
    echo "<script>alert('Akses Ditolak! Anda bukan Wali Kelas dari siswa ini.');location='index.php?halaman=kelas';</script>";
    exit();
}

// ambil data siswa
$siswa = $koneksi->query("
  SELECT s.*, t.tahun_ajaran 
  FROM siswa s
  LEFT JOIN tahun t ON s.id_tahun = t.id_tahun
  WHERE s.id_siswa = '$id'
")->fetch_assoc();

// ambil data kelas
$kelas = $koneksi->query("
  SELECT k.nama_kelas, j.nama_jurusan, t.tahun_ajaran, k.id_kelas, j.id_jurusan 
  FROM siswakelas sk
  JOIN kelas k ON sk.id_kelas = k.id_kelas
  JOIN jurusan j ON k.id_jurusan = j.id_jurusan
  JOIN tahun t ON k.id_tahun = t.id_tahun
  WHERE sk.id_siswa = '$id'
")->fetch_assoc();

// ambil nilai
$nilai = $koneksi->query("
  SELECT n.id_nilai, m.nama_mapel, n.pts, n.pas
  FROM nilai n
  JOIN mengajar mg ON n.id_mengajar = mg.id_ajar
  JOIN mapel m ON mg.id_mapel = m.id_mapel
  JOIN siswakelas sk ON n.id_siswakelas = sk.id_siswakelas
  WHERE sk.id_siswa = '$id'
");

// ambil absensi
$absensi = $koneksi->query("
  SELECT * FROM absensi WHERE id_siswa='$id' ORDER BY tahun DESC, bulan DESC
");
?>

<div class="max-w-6xl mx-auto fade-in">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 sticky top-0 bg-white/90 backdrop-blur-md p-4 rounded-xl shadow-sm z-20 border border-gray-200">
        <div>
            <h2 class="text-xl font-bold font-heading text-gray-800">Detail Siswa</h2>
            <p class="text-xs text-gray-500">Informasi akademik dan kedisiplinan (Mode Guru).</p>
        </div>
        <div class="flex gap-2">
            <a href="javascript:history.back()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative">
                <div class="h-24 bg-gradient-to-r from-primary-600 to-indigo-600"></div>
                <div class="px-6 pb-6 text-center -mt-12 relative z-10">
                    <?php
                    $foto = !empty($siswa['foto_siswa'])
                    ? "../siswa-foto/" . $siswa['foto_siswa']
                    : "https://ui-avatars.com/api/?name=" . urlencode($siswa['nama_siswa']) . "&background=random";
                    ?>
                    <img src="<?= $foto ?>" class="w-24 h-24 rounded-full border-4 border-white shadow-md mx-auto object-cover bg-white">
                    <h3 class="mt-3 font-bold text-gray-800 text-lg"><?php echo $siswa['nama_siswa']; ?></h3>
                    <p class="text-gray-500 text-sm font-mono"><?php echo $siswa['induk_siswa']; ?></p>
                    
                    <div class="mt-4 flex flex-col gap-2 text-sm text-left bg-gray-50 p-4 rounded-lg border border-gray-100">
                         <div class="flex justify-between border-b border-gray-200 pb-2">
                            <span class="text-gray-500">Tahun Masuk</span>
                            <span class="font-medium text-gray-700"><?php echo $siswa['tahun_ajaran']; ?></span>
                        </div>
                        <div class="flex justify-between border-b border-gray-200 pb-2 pt-2">
                             <span class="text-gray-500">Kelas</span>
                             <span class="font-medium text-gray-700"><?php echo $kelas['nama_kelas'] ?? '-'; ?></span>
                        </div>
                        <div class="flex justify-between pt-2">
                             <span class="text-gray-500">Jurusan</span>
                             <span class="font-medium text-gray-700 text-right"><?php echo $kelas['nama_jurusan'] ?? '-'; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- left-bottom-Card -->
             <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Tanggal Lahir</h4>
                <p class="text-sm text-gray-700 leading-relaxed">
                    <?php echo !empty($siswa['tanggal_lahir']) ? date('d-m-Y', strtotime($siswa['tanggal_lahir'])) : '-'; ?>
                </p>
            </div>
             <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Alamat Domisili</h4>
                <p class="text-sm text-gray-700 leading-relaxed"><?php echo $siswa['alamat_siswa']; ?></p>
             </div>
        </div>

        <!-- Right Column: Details Tabs/Sections -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Academic Grades (Read-Only) --> 
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"> 
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center"> 
                    <h3 class="font-bold text-gray-800 flex items-center gap-2"> <i class="fas fa-chart-bar text-primary-600"></i> Rekap Nilai Akademik </h3> 
                </div> 
                <div class="overflow-x-auto"> 
                    <table class="w-full text-sm text-left"> 
                        <thead class="bg-gray-50 text-gray-500 font-semibold uppercase text-xs"> 
                            <tr> 
                                <th class="px-6 py-3">Mata Pelajaran</th> 
                                <th class="px-2 py-3 text-center">PTS</th> 
                                <th class="px-2 py-3 text-center">PAS</th> 
                            </tr> 
                        </thead> 
                        <tbody class="divide-y divide-gray-100"> 
                            <?php if ($nilai->num_rows > 0): ?> 
                                <?php while ($n = $nilai->fetch_assoc()): ?> 
                                    <tr class="hover:bg-gray-50 transition-colors"> 
                                        <td class="px-6 py-3 font-medium text-gray-800"><?php echo $n['nama_mapel']; ?></td> 
                                        <td class="px-2 py-3 text-center"><?php echo $n['pts']; ?></td>
                                        <td class="px-2 py-3 text-center"><?php echo $n['pas']; ?></td>
                                    </tr> 
                                <?php endwhile; ?> 
                            <?php else: ?> 
                                <tr><td colspan="8" class="px-6 py-4 text-center text-gray-400">Belum ada data nilai.</td></tr> 
                            <?php endif; ?> 
                        </tbody> 
                    </table> 
                </div> 
                <div class="px-6 py-3 bg-gray-50/30 border-t border-gray-100 text-center">
                    <a href="index.php?halaman=nilai" class="text-sm text-primary-600 font-medium hover:text-primary-700">Input Nilai</a>
                </div>
            </div>

            <!-- Attendance (Read-Only) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                     <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="far fa-calendar-check text-emerald-600"></i> Rekap Absensi
                    </h3>
                </div>
                <div class="p-6">
                     <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                             <thead class="bg-gray-50 text-gray-500 font-semibold uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3">Periode</th>
                                    <th class="px-2 py-3 text-center">Hadir</th>
                                    <th class="px-2 py-3 text-center">Sakit</th>
                                    <th class="px-2 py-3 text-center">Izin</th>
                                    <th class="px-2 py-3 text-center">Alpa</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if ($absensi->num_rows > 0): ?>
                                    <?php while ($a = $absensi->fetch_assoc()): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 font-medium"><?php echo $a['bulan'] . ' ' . $a['tahun']; ?></td>
                                            <td class="px-2 py-3 text-center"><?php echo $a['hadir']; ?></td>
                                            <td class="px-2 py-3 text-center"><?php echo $a['sakit']; ?></td>
                                            <td class="px-2 py-3 text-center"><?php echo $a['izin']; ?></td>
                                            <td class="px-2 py-3 text-center"><?php echo $a['alpa']; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="px-6 py-4 text-center text-gray-400">Belum ada data absensi.</td></tr> 
                                <?php endif; ?>
                            </tbody>
                        </table>
                     </div>
                </div>
                <div class="px-6 py-3 bg-gray-50/30 border-t border-gray-100 text-center">
                    <a href="index.php?halaman=absensi" class="text-sm text-primary-600 font-medium hover:text-primary-700">Input Absensi</a>
                </div>
            </div>

            <!-- Discipline & Achievements -->
            <div class="grid grid-cols-1 gap-6">
                <!-- Achievements -->
                 <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                             <i class="fas fa-trophy text-amber-500"></i> Prestasi Siswa
                        </h3>
                    </div>
                    <div class="p-6">
                        <!-- Table -->
                        <?php
                            $id_siswa = $_GET['id'];
                            $prestasi = $koneksi->query("SELECT * FROM prestasi WHERE id_siswa='$id_siswa' ORDER BY tanggal DESC");
                        ?>
                        <div class="max-h-64 overflow-y-auto mb-4 border border-gray-100 rounded-lg">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-500 text-xs uppercase sticky top-0">
                                    <tr>
                                        <th class="px-4 py-2">Tanggal</th>
                                        <th class="px-4 py-2">Prestasi</th>
                                        <th class="px-4 py-2">Tingkat</th>
                                        <th class="px-2 py-2 text-center">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                     <?php while ($data = $prestasi->fetch_assoc()): ?>
                                        <tr>
                                            <td class="px-4 py-2 text-gray-500"><?= date("d M Y", strtotime($data['tanggal'])); ?></td>
                                            <td class="px-4 py-2 font-medium"><?= $data['jenis_prestasi']; ?></td>
                                            <td class="px-4 py-2"><span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded text-xs"><?= $data['tingkat']; ?></span></td>
                                            <td class="px-2 py-2 text-center">
                                                <a href="index.php?halaman=prestasi_hapus&id=<?= $data['id_prestasi'] ?>&ids=<?= $id_siswa ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Hapus?')"><i class="fas fa-times"></i></a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Mini Form -->
                        <form method="post" class="flex gap-2">
                             <input type="date" name="tanggal_prestasi" class="w-32 text-sm rounded-md border-gray-300" required>
                             <input type="text" name="jenis_prestasi" class="flex-grow text-sm rounded-md border-gray-300" placeholder="Juara 1 Lomba..." required>
                             <input type="text" name="tingkat" class="w-24 text-sm rounded-md border-gray-300" placeholder="Tingkat">
                             <button type="submit" name="simpan_prestasi" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 h-[38px]"><i class="fas fa-plus"></i></button>
                        </form>
                    </div>
                 </div>

                 <!-- Violations -->
                 <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                             <i class="fas fa-exclamation-triangle text-rose-500"></i> Pelanggaran & Tata Tertib
                        </h3>
                    </div>
                     <div class="p-6">
                        <!-- Table -->
                        <?php
                            $pelanggaran = $koneksi->query("SELECT * FROM pelanggaran WHERE id_siswa='$id_siswa' ORDER BY tanggal DESC");
                        ?>
                        <div class="max-h-64 overflow-y-auto mb-4 border border-gray-100 rounded-lg">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-500 text-xs uppercase sticky top-0">
                                    <tr>
                                        <th class="px-4 py-2">Tanggal</th>
                                        <th class="px-4 py-2">Pelanggaran</th>
                                        <th class="px-4 py-2 text-center">Poin</th>
                                        <th class="px-2 py-2 text-center">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                     <?php while ($data = $pelanggaran->fetch_assoc()): ?>
                                        <tr>
                                            <td class="px-4 py-2 text-gray-500"><?= date("d M Y", strtotime($data['tanggal'])); ?></td>
                                            <td class="px-4 py-2 font-medium text-rose-600"><?= $data['jenis_pelanggaran']; ?></td>
                                            <td class="px-4 py-2 text-center font-bold"><?= $data['poin']; ?></td>
                                            <td class="px-2 py-2 text-center">
                                                <a href="index.php?halaman=pelanggaran_hapus&id=<?= $data['id_pelanggaran'] ?>&ids=<?= $id_siswa ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Hapus?')"><i class="fas fa-times"></i></a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mini Form -->
                         <form method="post" class="flex gap-2">
                             <input type="date" name="tanggal" class="w-32 text-sm rounded-md border-gray-300" required>
                             <input type="text" name="jenis" class="flex-grow text-sm rounded-md border-gray-300" placeholder="Jenis pelanggaran..." required>
                             <input type="number" name="poin" class="w-20 text-sm rounded-md border-gray-300" placeholder="Poin" min="1" required>
                             <button type="submit" name="simpan_pelanggaran" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 h-[38px]"><i class="fas fa-plus"></i></button>
                        </form>
                     </div>
                 </div>
            </div>

        </div>
    </div>

    <?php
    // Logic Prestasi
    if (isset($_POST['simpan_prestasi'])) {
        $tgl = $_POST['tanggal_prestasi'];
        $jenis = $_POST['jenis_prestasi'];
        $tingkat = $_POST['tingkat'];
        $ket = ''; // keterangan default kosong
        $koneksi->query("INSERT INTO prestasi (id_siswa, tanggal, jenis_prestasi, tingkat, keterangan) VALUES ('$id_siswa', '$tgl', '$jenis', '$tingkat', '$ket')");
        echo "<script>alert('Data prestasi berhasil ditambahkan!'); location='index.php?halaman=siswa_detail&id=$id_siswa';</script>";
    }

    // Logic Pelanggaran
    if (isset($_POST['simpan_pelanggaran'])) {
        $tgl = $_POST['tanggal'];
        $jenis = $_POST['jenis'];
        $ket = ''; // keterangan default kosong
        $poin = $_POST['poin'];
        $koneksi->query("INSERT INTO pelanggaran (id_siswa, tanggal, jenis_pelanggaran, keterangan, poin) VALUES ('$id_siswa', '$tgl', '$jenis', '$ket', '$poin')");
        echo "<script>alert('Data pelanggaran berhasil ditambahkan!'); location='index.php?halaman=siswa_detail&id=$id_siswa';</script>";
    }
    ?>

</div>
