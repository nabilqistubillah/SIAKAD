
<?php
$id = $_GET['id'];

// Pastikan koneksi aktif
try {
    if (!@$koneksi->ping()) {
        $koneksi = new mysqli("localhost", "root", "", "smk_siakad");
    }
} catch (Throwable $e) {
    if (strpos($e->getMessage(), 'closed') !== false) {
        $koneksi = new mysqli("localhost", "root", "", "smk_siakad");
    }
}

if (isset($_POST['simpan_nilai'])) {

    $id_mapel = $_POST['id_mapel'];
    $h1 = $_POST['h1'];
    $h2 = $_POST['h2'];
    $h3 = $_POST['h3'];
    $h4 = $_POST['h4'];
    $pts = $_POST['pts'];
    $pas = $_POST['pas'];

    $rph = ($h1 + $h2 + $h3 + $h4) / 4;

    // Cari id_siswakelas
    $sk = $koneksi->query("SELECT id_siswakelas, id_kelas FROM siswakelas WHERE id_siswa = '$id' LIMIT 1")->fetch_assoc();
    
    if ($sk) {
        $id_siswakelas = $sk['id_siswakelas'];
        $id_kelas = $sk['id_kelas'];
        
        // Cari id_mengajar (kalau tidak ada, buat dummy ke mapelnya tanpa guru agar support manual input)
        $mg = $koneksi->query("SELECT id_ajar FROM mengajar WHERE id_mapel = '$id_mapel' AND id_kelas = '$id_kelas' LIMIT 1")->fetch_assoc();
        
        if ($mg) {
            $id_mengajar = $mg['id_ajar'];
        } else {
            $koneksi->query("INSERT INTO mengajar (id_mapel, id_kelas, id_guru) VALUES ('$id_mapel', '$id_kelas', 0)");
            $id_mengajar = $koneksi->insert_id;
        }

        // Simpan nilai sesuai struktur asli (id_mengajar & id_siswakelas)
        $koneksi->query("
            INSERT INTO nilai 
            (id_mengajar, id_siswakelas, h1, h2, h3, h4, rph, pts, pas)
            VALUES 
            ('$id_mengajar', '$id_siswakelas', '$h1', '$h2', '$h3', '$h4', '$rph', '$pts', '$pas')
        ");

        echo "<script>alert('Nilai berhasil ditambahkan'); location='halaman=siswa_detail&id=$id';</script>";
    } else {
        echo "<script>alert('Siswa ini belum disetel ke dalam kelas manapun!'); location='halaman=siswa_detail&id=$id';</script>";
    }
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
  SELECT k.nama_kelas, j.nama_jurusan, t.tahun_ajaran, k.id_kelas 
  FROM siswakelas sk
  JOIN kelas k ON sk.id_kelas = k.id_kelas
  JOIN jurusan j ON k.id_jurusan = j.id_jurusan
  JOIN tahun t ON k.id_tahun = t.id_tahun
  WHERE sk.id_siswa = '$id'
")->fetch_assoc();

// ambil nilai
$nilai = $koneksi->query("
  SELECT n.id_nilai, m.nama_mapel, n.h1, n.h2, n.h3, n.h4, n.rph, n.pts, n.pas
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
            <p class="text-xs text-gray-500">Informasi lengkap, akademik, dan kedisiplinan.</p>
        </div>
        <div class="flex gap-2">
            <?php if (!empty($kelas['id_kelas'])): ?>
                <a href="index.php?halaman=siswakelas&id=<?= $kelas['id_kelas']; ?>" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Kelas
                </a>
            <?php else: ?>
                 <a href="index.php?halaman=siswa" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            <?php endif; ?>
            <a href="index.php?halaman=siswa_edit&id=<?php echo $siswa['id_siswa']; ?>" class="px-4 py-2 bg-amber-100 text-amber-700 rounded-lg text-sm font-medium hover:bg-amber-200 transition-colors border border-amber-200">
                <i class="fas fa-edit mr-2"></i>Edit Data
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
        <?php 
        echo !empty($siswa['tanggal_lahir']) 
            ? date('d-m-Y', strtotime($siswa['tanggal_lahir'])) 
            : '-'; 
        ?>
    </p>
</div>
             <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Alamat Domisili</h4>
                <p class="text-sm text-gray-700 leading-relaxed"><?php echo $siswa['alamat_siswa']; ?></p>
             </div>
        </div>

        <!-- Right Column: Details Tabs/Sections -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Academic Grades --> 
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"> 
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center"> 
                    <h3 class="font-bold text-gray-800 flex items-center gap-2"> <i class="fas fa-chart-bar text-primary-600"></i> Rekap Nilai Akademik </h3> 
                </div> 
                <form method="post">
                <div class="overflow-x-auto"> 
                    <table class="w-full text-sm text-left"> 
                        <thead class="bg-gray-50 text-gray-500 font-semibold uppercase text-xs"> 
                            <tr> 
                                <th class="px-6 py-3">Mata Pelajaran</th> 
                                <th class="px-2 py-3 text-center">H1</th> 
                                <th class="px-2 py-3 text-center">H2</th> 
                                <th class="px-2 py-3 text-center">H3</th> 
                                <th class="px-2 py-3 text-center">H4</th> 
                                <th class="px-2 py-3 text-center">RPH</th> 
                                <th class="px-2 py-3 text-center">PTS</th> 
                                <th class="px-2 py-3 text-center">PAS</th> 
                            </tr> 
                        </thead> 
                        <tbody class="divide-y divide-gray-100"> 
                            <?php if ($nilai->num_rows > 0): ?> 
                                <?php while ($n = $nilai->fetch_assoc()): ?> 
                                    <tr class="hover:bg-gray-50 transition-colors"> 
                                        <td class="px-6 py-3 font-medium text-gray-800"><?php echo $n['nama_mapel']; ?></td> 
                                        <td class="px-2 py-3 text-center"><input type="number" readonly value="<?php echo $n['h1']; ?>" class="w-16 text-center text-sm rounded-md border-gray-100 bg-gray-50 focus:ring-0 cursor-not-allowed"></td>
                                        <td class="px-2 py-3 text-center"><input type="number" readonly value="<?php echo $n['h2']; ?>" class="w-16 text-center text-sm rounded-md border-gray-100 bg-gray-50 focus:ring-0 cursor-not-allowed"></td>
                                        <td class="px-2 py-3 text-center"><input type="number" readonly value="<?php echo $n['h3']; ?>" class="w-16 text-center text-sm rounded-md border-gray-100 bg-gray-50 focus:ring-0 cursor-not-allowed"></td>
                                        <td class="px-2 py-3 text-center"><input type="number" readonly value="<?php echo $n['h4']; ?>" class="w-16 text-center text-sm rounded-md border-gray-100 bg-gray-50 focus:ring-0 cursor-not-allowed"></td>
                                        <td class="px-2 py-3 text-center"><input type="number" readonly value="<?php echo $n['rph']; ?>" class="w-16 text-center text-sm font-bold text-gray-700 bg-gray-100 rounded-md border-gray-200 focus:ring-0 cursor-not-allowed"></td>
                                        <td class="px-2 py-3 text-center"><input type="number" readonly value="<?php echo $n['pts']; ?>" class="w-16 text-center text-sm rounded-md border-gray-100 bg-gray-50 focus:ring-0 cursor-not-allowed"></td>
                                        <td class="px-2 py-3 text-center"><input type="number" readonly value="<?php echo $n['pas']; ?>" class="w-16 text-center text-sm rounded-md border-gray-100 bg-gray-50 focus:ring-0 cursor-not-allowed"></td>
                                        <td class="px-2 py-3 text-center">
                                            <a href="index.php?halaman=nilai_hapus&id=<?php echo $n['id_nilai']; ?>&ids=<?php echo $id; ?>" class="text-red-500 hover:text-red-700 transition" onclick="return confirm('Hapus baris nilai mapel ini secara permanen?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr> 
                                <?php endwhile; ?> 
                            <?php else: ?> 
                                <tr><td colspan="9" class="px-6 py-4 text-center text-gray-400">Belum ada data nilai.</td></tr> 
                            <?php endif; ?> 
                        </tbody> 
                    </table> 
                </div> 
                </form>
            </div>
            <!--form input grades-->
            <form method="post" class="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-4">
    <h4 class="text-xs font-bold text-gray-500 uppercase mb-3">Input Nilai</h4>

    <div class="flex flex-wrap gap-3 items-end">

        <!-- Pilih Mapel -->
        <div class="w-48">
            <label class="text-xs text-gray-500 mb-1 block">Mata Pelajaran</label>
            <select name="id_mapel" class="w-full text-sm rounded-md border-gray-300" required>
                <option value="">Pilih Mapel</option>
                <?php
                $mapel = $koneksi->query("SELECT * FROM mapel");
                while ($m = $mapel->fetch_assoc()) {
                    echo "<option value='{$m['id_mapel']}'>{$m['nama_mapel']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Nilai -->
        <?php $fields = ['h1','h2','h3','h4','pts','pas']; ?>
        <?php foreach ($fields as $f): ?>
        <div class="w-16">
            <label class="text-xs mb-1 block uppercase"><?= $f ?></label>
            <input type="number" name="<?= $f ?>" class="w-full text-sm rounded-md border-gray-300" value="0">
        </div>
        <?php endforeach; ?>

        <button type="submit" name="simpan_nilai"
            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 h-[38px]">
            Simpan
        </button>
    </div>
</form>

            <!-- Attendance -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                     <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="far fa-calendar-check text-emerald-600"></i> Rekap Absensi
                    </h3>
                </div>
                <div class="p-6">
                     <div class="overflow-x-auto mb-6">
                        <table class="w-full text-sm text-left">
                             <thead class="bg-gray-50 text-gray-500 font-semibold uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3">Periode</th>
                                    <th class="px-2 py-3 text-center text-emerald-600">Hadir</th>
                                    <th class="px-2 py-3 text-center text-blue-600">Sakit</th>
                                    <th class="px-2 py-3 text-center text-amber-600">Izin</th>
                                    <th class="px-2 py-3 text-center text-red-600">Alpa</th>
                                    <th class="px-2 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php while ($a = $absensi->fetch_assoc()): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium"><?php echo $a['bulan'] . ' ' . $a['tahun']; ?></td>
                                        <td class="px-2 py-3 text-center"><?php echo $a['hadir']; ?></td>
                                        <td class="px-2 py-3 text-center"><?php echo $a['sakit']; ?></td>
                                        <td class="px-2 py-3 text-center"><?php echo $a['izin']; ?></td>
                                        <td class="px-2 py-3 text-center"><?php echo $a['alpa']; ?></td>
                                        <td class="px-2 py-3 text-center">
                                             <a href="index.php?halaman=absensi_hapus&id=<?php echo $a['id_siswa']; ?>&ids=<?php echo $a['id_absensi']; ?>"
                                              class="text-red-500 hover:text-red-700 transition-colors"
                                              onclick="return confirm('Hapus data absensi ini?')"><i class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                     </div>

                     <!-- Add Attendance Form -->
                     <form method="post" class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h4 class="text-xs font-bold text-gray-500 uppercase mb-3">Tambah Absensi Bulanan</h4>
                        <div class="flex flex-wrap gap-3 items-end">
                            <div class="w-32 flex-grow">
                                <label class="text-xs text-gray-500 mb-1 block">Bulan</label>
                                <select name="bulan" class="w-full text-sm rounded-md border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                                    <option value="">Pilih Bulan</option>
                                    <?php
                                    $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                    foreach ($bulan as $b) { echo "<option value='$b'>$b</option>"; }
                                    ?>
                                </select>
                            </div>
                            <div class="w-24">
                                <label class="text-xs text-gray-500 mb-1 block">Tahun</label>
                                <input type="number" name="tahun" class="w-full text-sm rounded-md border-gray-300 focus:border-primary-500 focus:ring-primary-500" value="<?php echo date('Y'); ?>" required>
                            </div>
                            <div class="w-16">
                                <label class="text-xs text-emerald-600 font-medium mb-1 block">Hadir</label>
                                <input type="number" name="hadir" class="w-full text-sm rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500" value="0">
                            </div>
                            <div class="w-16">
                                <label class="text-xs text-blue-600 font-medium mb-1 block">Sakit</label>
                                <input type="number" name="sakit" class="w-full text-sm rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" value="0">
                            </div>
                            <div class="w-16">
                                <label class="text-xs text-amber-600 font-medium mb-1 block">Izin</label>
                                <input type="number" name="izin" class="w-full text-sm rounded-md border-gray-300 focus:border-amber-500 focus:ring-amber-500" value="0">
                            </div>
                             <div class="w-16">
                                <label class="text-xs text-red-600 font-medium mb-1 block">Alpa</label>
                                <input type="number" name="alpa" class="w-full text-sm rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500" value="0">
                            </div>
                            <button type="submit" name="tambah_absensi" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 h-[38px]">
                                Simpan
                            </button>
                        </div>
                     </form>
                </div>
            </div>

            <!-- Discipline & Achievements (Tabs Concept) -->
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

    <!-- Hidden Logic (Kept Original) -->
    <?php
    // Logic Tambah Absensi
    if (isset($_POST['tambah_absensi'])) {
        $bulan = $_POST['bulan'];
        $tahun = $_POST['tahun'];
        $hadir = $_POST['hadir'];
        $sakit = $_POST['sakit'];
        $izin = $_POST['izin'];
        $alpa = $_POST['alpa'];

        $cek = $koneksi->query("SELECT * FROM absensi WHERE id_siswa='$id' AND bulan='$bulan' AND tahun='$tahun'");
        if ($cek->num_rows > 0) {
            echo "<script>alert('Data absensi bulan ini sudah ada!'); location='index.php?halaman=siswa_detail&id=$id';</script>";
        } else {
            $koneksi->query("INSERT INTO absensi (id_siswa, bulan, tahun, hadir, sakit, izin, alpa) VALUES ('$id', '$bulan', '$tahun', '$hadir', '$sakit', '$izin', '$alpa')");
            echo "<script>alert('Data absensi berhasil ditambahkan'); location='index.php?halaman=siswa_detail&id=$id';</script>";
        }
    }

    // Logic Prestasi
    if (isset($_POST['simpan_prestasi'])) {
        $tgl = $_POST['tanggal_prestasi'];
        $jenis = $_POST['jenis_prestasi'];
        $tingkat = $_POST['tingkat'];
        $ket = $_POST['keterangan_prestasi']; // Note: field 'keterangan_prestasi' was in original logic form but not explicitly in table insert? assuming handled by DB or removed. adjusted to original code flow.
         $koneksi->query("INSERT INTO prestasi (id_siswa, tanggal, jenis_prestasi, tingkat, keterangan) VALUES ('$id_siswa', '$tgl', '$jenis', '$tingkat', '$ket')");
        echo "<script>alert('Data prestasi berhasil ditambahkan!'); location='index.php?halaman=siswa_detail&id=$id_siswa';</script>";
    }

    // Logic Pelanggaran
    if (isset($_POST['simpan_pelanggaran'])) {
        $tgl = $_POST['tanggal'];
        $jenis = $_POST['jenis'];
        $ket = $_POST['ket'];
        $poin = $_POST['poin'];
        $koneksi->query("INSERT INTO pelanggaran (id_siswa, tanggal, jenis_pelanggaran, keterangan, poin) VALUES ('$id_siswa', '$tgl', '$jenis', '$ket', '$poin')");
        echo "<script>alert('Data pelanggaran berhasil ditambahkan!'); location='index.php?halaman=siswa_detail&id=$id_siswa';</script>";
    }

    ?>

</div>