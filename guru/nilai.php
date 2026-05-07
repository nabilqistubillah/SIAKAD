<?php
$id_guru = $_SESSION['guru']['id_guru'];
$id_kelas_selected = $_GET['id_kelas'] ?? '';
$id_mengajar_selected = $_GET['id_mengajar'] ?? '';

// Fetch classes for this Wali Kelas
$kelas_list = $koneksi->query("SELECT id_kelas, nama_kelas FROM kelas WHERE id_guru = '$id_guru' ORDER BY nama_kelas ASC");

$mapel_list = [];
if ($id_kelas_selected) {
    // Fetch subjects for this class
    $mapel_query = $koneksi->query("
        SELECT mg.id_ajar, m.nama_mapel 
        FROM mengajar mg
        JOIN mapel m ON mg.id_mapel = m.id_mapel
        WHERE mg.id_kelas = '$id_kelas_selected'
        ORDER BY m.nama_mapel ASC
    ");
    while($row = $mapel_query->fetch_assoc()){
        $mapel_list[] = $row;
    }
}

// Save logic
if (isset($_POST['simpan_nilai'])) {
    $id_mengajar = $_POST['id_mengajar'];
    $nilai_data = $_POST['nilai']; // array: id_siswakelas => ['pts' => val, 'pas' => val]
    
    foreach ($nilai_data as $id_sk => $n) {
        $pts = (int)$n['pts'];
        $pas = (int)$n['pas'];
        
        $cek = $koneksi->query("SELECT id_nilai FROM nilai WHERE id_mengajar='$id_mengajar' AND id_siswakelas='$id_sk'");
        if ($cek->num_rows > 0) {
            $row = $cek->fetch_assoc();
            $id_n = $row['id_nilai'];
            $koneksi->query("UPDATE nilai SET pts='$pts', pas='$pas' WHERE id_nilai='$id_n'");
        } else {
            $koneksi->query("INSERT INTO nilai (id_mengajar, id_siswakelas, h1, h2, h3, h4, rph, pts, pas) VALUES ('$id_mengajar', '$id_sk', 0,0,0,0,0, '$pts', '$pas')");
        }
    }
    echo "<script>alert('Nilai berhasil disimpan!'); location='index.php?halaman=nilai&id_kelas=$id_kelas_selected&id_mengajar=$id_mengajar';</script>";
    exit();
}

// Fetch students
$siswa_list = [];
if ($id_kelas_selected && $id_mengajar_selected) {
    $siswa_query = $koneksi->query("
        SELECT sk.id_siswakelas, s.nama_siswa, s.induk_siswa, n.pts, n.pas
        FROM siswakelas sk
        JOIN siswa s ON sk.id_siswa = s.id_siswa
        LEFT JOIN nilai n ON sk.id_siswakelas = n.id_siswakelas AND n.id_mengajar = '$id_mengajar_selected'
        WHERE sk.id_kelas = '$id_kelas_selected'
        ORDER BY s.nama_siswa ASC
    ");
    while($row = $siswa_query->fetch_assoc()){
        $siswa_list[] = $row;
    }
}
?>

<div class="space-y-6 fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold font-heading text-gray-800">Input Nilai UTS & UAS</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola nilai evaluasi semester untuk kelas perwalian Anda.</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="get" action="index.php" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="hidden" name="halaman" value="nilai">
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Kelas</label>
                <select name="id_kelas" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" onchange="this.form.submit()">
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
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih Mata Pelajaran</label>
                <select name="id_mengajar" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" onchange="this.form.submit()">
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    <?php foreach($mapel_list as $m): ?>
                        <option value="<?= $m['id_ajar'] ?>" <?= $id_mengajar_selected == $m['id_ajar'] ? 'selected' : '' ?>>
                            <?= $m['nama_mapel'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Student List Form -->
    <?php if ($id_kelas_selected && $id_mengajar_selected): ?>
        <form method="post" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <input type="hidden" name="id_mengajar" value="<?= $id_mengajar_selected ?>">
            
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 z-10">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-users text-primary-600"></i> Daftar Siswa
                </h3>
                <button type="submit" name="simpan_nilai" class="px-5 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-md shadow-primary-500/20">
                    <i class="fas fa-save mr-2"></i>Simpan Nilai
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-white text-gray-500 font-semibold uppercase text-xs border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 w-16 text-center">No</th>
                            <th class="px-6 py-4">Nama Siswa</th>
                            <th class="px-6 py-4">NIS</th>
                            <th class="px-6 py-4 w-32 text-center text-primary-700">Nilai UTS (PTS)</th>
                            <th class="px-6 py-4 w-32 text-center text-primary-700">Nilai UAS (PAS)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($siswa_list)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">
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
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 font-mono text-xs">
                                        <?= $s['induk_siswa'] ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" name="nilai[<?= $s['id_siswakelas'] ?>][pts]" value="<?= $s['pts'] ?>" min="0" max="100" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-center font-medium" placeholder="0-100">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" name="nilai[<?= $s['id_siswakelas'] ?>][pas]" value="<?= $s['pas'] ?>" min="0" max="100" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-center font-medium" placeholder="0-100">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (!empty($siswa_list)): ?>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                <button type="submit" name="simpan_nilai" class="px-6 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-lg shadow-primary-500/30">
                    <i class="fas fa-save mr-2"></i>Simpan Seluruh Nilai
                </button>
            </div>
            <?php endif; ?>
        </form>
    <?php elseif ($id_kelas_selected): ?>
        <div class="bg-white border border-gray-100 rounded-2xl shadow-soft p-12 text-center">
            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 text-primary-500">
                <i class="fas fa-book-open text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-800">Pilih Mata Pelajaran</h3>
            <p class="text-gray-500 text-sm mt-1">Silakan pilih mata pelajaran di atas untuk mulai mengisi nilai.</p>
        </div>
    <?php else: ?>
        <div class="bg-white border border-gray-100 rounded-2xl shadow-soft p-12 text-center">
            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 text-primary-500">
                <i class="fas fa-door-open text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-800">Pilih Kelas</h3>
            <p class="text-gray-500 text-sm mt-1">Silakan pilih kelas perwalian Anda untuk menginput nilai.</p>
        </div>
    <?php endif; ?>
</div>
