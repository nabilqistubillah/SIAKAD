<?php
$id_guru = $_SESSION['guru']['id_guru'];
$id_kelas_selected = $_GET['id_kelas'] ?? '';

$bulan_list = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

$bulan_indonesia = array(
    'January' => 'Januari',
    'February' => 'Februari',
    'March' => 'Maret',
    'April' => 'April',
    'May' => 'Mei',
    'June' => 'Juni',
    'July' => 'Juli',
    'August' => 'Agustus',
    'September' => 'September',
    'October' => 'Oktober',
    'November' => 'November',
    'December' => 'Desember'
);
$current_month_eng = date('F');
$default_bulan = $bulan_indonesia[$current_month_eng] ?? 'Januari';

$bulan_selected = $_GET['bulan'] ?? $default_bulan;
$tahun_selected = $_GET['tahun'] ?? date('Y');

// Fetch classes for this Wali Kelas
$kelas_list = $koneksi->query("SELECT id_kelas, nama_kelas FROM kelas WHERE id_guru = '$id_guru' ORDER BY nama_kelas ASC");

// Save logic
if (isset($_POST['simpan_absensi'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $absensi_data = $_POST['absensi']; // array: id_siswa => ['hadir' => val, 'sakit' => val, 'izin' => val, 'alpa' => val]
    
    foreach ($absensi_data as $id_siswa => $ab) {
        $hadir = (int)$ab['hadir'];
        $sakit = (int)$ab['sakit'];
        $izin = (int)$ab['izin'];
        $alpa = (int)$ab['alpa'];
        
        $cek = $koneksi->query("SELECT id_absensi FROM absensi WHERE id_siswa='$id_siswa' AND bulan='$bulan' AND tahun='$tahun'");
        if ($cek->num_rows > 0) {
            $row = $cek->fetch_assoc();
            $id_ab = $row['id_absensi'];
            $koneksi->query("UPDATE absensi SET hadir='$hadir', sakit='$sakit', izin='$izin', alpa='$alpa' WHERE id_absensi='$id_ab'");
        } else {
            $koneksi->query("INSERT INTO absensi (id_siswa, bulan, tahun, hadir, sakit, izin, alpa) VALUES ('$id_siswa', '$bulan', '$tahun', '$hadir', '$sakit', '$izin', '$alpa')");
        }
    }
    echo "<script>alert('Absensi bulan $bulan $tahun berhasil disimpan!'); location='index.php?halaman=absensi&id_kelas=$id_kelas_selected&bulan=$bulan&tahun=$tahun';</script>";
    exit();
}

// Fetch students
$siswa_list = [];
if ($id_kelas_selected) {
    $siswa_query = $koneksi->query("
        SELECT sk.id_siswa, s.nama_siswa, s.induk_siswa, a.hadir, a.sakit, a.izin, a.alpa
        FROM siswakelas sk
        JOIN siswa s ON sk.id_siswa = s.id_siswa
        LEFT JOIN absensi a ON sk.id_siswa = a.id_siswa AND a.bulan = '$bulan_selected' AND a.tahun = '$tahun_selected'
        WHERE sk.id_kelas = '$id_kelas_selected'
        ORDER BY s.nama_siswa ASC
    ");
    while($row = $siswa_query->fetch_assoc()){
        // default 0 if null
        $row['hadir'] = $row['hadir'] ?? 0;
        $row['sakit'] = $row['sakit'] ?? 0;
        $row['izin']  = $row['izin'] ?? 0;
        $row['alpa']  = $row['alpa'] ?? 0;
        $siswa_list[] = $row;
    }
}
?>

<div class="space-y-6 fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold font-heading text-gray-800">Rekap Absensi Bulanan</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola data kehadiran siswa untuk kelas perwalian Anda.</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="get" action="index.php" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="halaman" value="absensi">
            
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Kelas</label>
                <select name="id_kelas" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors" onchange="this.form.submit()">
                    <option value="">-- Pilih Kelas --</option>
                    <?php while($k = $kelas_list->fetch_assoc()): ?>
                        <option value="<?= $k['id_kelas'] ?>" <?= $id_kelas_selected == $k['id_kelas'] ? 'selected' : '' ?>>
                            <?= $k['nama_kelas'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <?php if ($id_kelas_selected): ?>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Bulan</label>
                <select name="bulan" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors" onchange="this.form.submit()">
                    <?php foreach($bulan_list as $b): ?>
                        <option value="<?= $b ?>" <?= $bulan_selected == $b ? 'selected' : '' ?>><?= $b ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tahun</label>
                <select name="tahun" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors" onchange="this.form.submit()">
                    <?php 
                    $current_year = date('Y');
                    for($y = $current_year - 2; $y <= $current_year + 1; $y++): ?>
                        <option value="<?= $y ?>" <?= $tahun_selected == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Student List Form -->
    <?php if ($id_kelas_selected): ?>
        <form method="post" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <input type="hidden" name="bulan" value="<?= $bulan_selected ?>">
            <input type="hidden" name="tahun" value="<?= $tahun_selected ?>">
            
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 z-10">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="far fa-calendar-check text-emerald-600"></i> Absensi <?= $bulan_selected ?> <?= $tahun_selected ?>
                </h3>
                <button type="submit" name="simpan_absensi" class="px-5 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-md shadow-emerald-500/20">
                    <i class="fas fa-save mr-2"></i>Simpan Absensi
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-white text-gray-500 font-semibold uppercase text-xs border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 w-16 text-center">No</th>
                            <th class="px-6 py-4">Nama Siswa</th>
                            <th class="px-4 py-4 w-28 text-center text-emerald-600">Hadir</th>
                            <th class="px-4 py-4 w-28 text-center text-blue-600">Sakit</th>
                            <th class="px-4 py-4 w-28 text-center text-amber-600">Izin</th>
                            <th class="px-4 py-4 w-28 text-center text-red-600">Alpa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($siswa_list)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                    <i class="fas fa-info-circle text-2xl mb-2 block"></i>
                                    Belum ada siswa di kelas ini.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($siswa_list as $index => $s): ?>
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <td class="px-6 py-4 text-center text-gray-500"><?= $index + 1 ?></td>
                                    <td class="px-6 py-4 font-bold text-gray-800">
                                        <?= $s['nama_siswa'] ?>
                                        <div class="text-xs font-mono text-gray-500 font-normal mt-0.5"><?= $s['induk_siswa'] ?></div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <input type="number" name="absensi[<?= $s['id_siswa'] ?>][hadir]" value="<?= $s['hadir'] ?>" min="0" max="31" class="w-full px-2 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-center font-medium" placeholder="0">
                                    </td>
                                    <td class="px-4 py-4">
                                        <input type="number" name="absensi[<?= $s['id_siswa'] ?>][sakit]" value="<?= $s['sakit'] ?>" min="0" max="31" class="w-full px-2 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center font-medium" placeholder="0">
                                    </td>
                                    <td class="px-4 py-4">
                                        <input type="number" name="absensi[<?= $s['id_siswa'] ?>][izin]" value="<?= $s['izin'] ?>" min="0" max="31" class="w-full px-2 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-center font-medium" placeholder="0">
                                    </td>
                                    <td class="px-4 py-4">
                                        <input type="number" name="absensi[<?= $s['id_siswa'] ?>][alpa]" value="<?= $s['alpa'] ?>" min="0" max="31" class="w-full px-2 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-center font-medium" placeholder="0">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (!empty($siswa_list)): ?>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                <button type="submit" name="simpan_absensi" class="px-6 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-save mr-2"></i>Simpan Seluruh Absensi
                </button>
            </div>
            <?php endif; ?>
        </form>
    <?php else: ?>
        <div class="bg-white border border-gray-100 rounded-2xl shadow-soft p-12 text-center">
            <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4 text-emerald-500">
                <i class="fas fa-door-open text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-800">Pilih Kelas</h3>
            <p class="text-gray-500 text-sm mt-1">Silakan pilih kelas perwalian Anda untuk mengisi absensi.</p>
        </div>
    <?php endif; ?>
</div>
