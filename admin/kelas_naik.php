<?php
// keamanan admin
if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Harus login admin');location='../login.php';</script>";
    exit;
}

// ambil data
$tahun = $koneksi->query("SELECT * FROM tahun ORDER BY id_tahun DESC");
$jurusan = $koneksi->query("SELECT * FROM jurusan ORDER BY nama_jurusan ASC");
?>

<div class="space-y-6 fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold font-heading text-gray-800">Kenaikan Kelas & Kelulusan</h2>
            <p class="text-gray-500 text-sm mt-1">Proses kenaikan tingkat siswa atau kelulusan.</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white p-6 rounded-2xl shadow-soft border border-gray-100 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-primary-50 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
        <form method="post" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end relative z-10">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Tahun Ajaran</label>
                <div class="relative">
                    <select name="id_tahun" class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none transition-all cursor-pointer hover:bg-gray-100" required>
                        <option value="">Pilih Tahun</option>
                        <?php while ($t = $tahun->fetch_assoc()): ?>
                            <option value="<?= $t['id_tahun'] ?>"><?= $t['tahun_ajaran'] ?></option>
                        <?php endwhile; ?>
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-3.5 text-gray-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Jurusan</label>
                <div class="relative">
                    <select name="id_jurusan" class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none transition-all cursor-pointer hover:bg-gray-100" required>
                        <option value="">Pilih Jurusan</option>
                        <?php while ($j = $jurusan->fetch_assoc()): ?>
                            <option value="<?= $j['id_jurusan'] ?>"><?= $j['nama_jurusan'] ?></option>
                        <?php endwhile; ?>
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-3.5 text-gray-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Kelas Asal</label>
                <div class="relative">
                    <select name="kelas_asal" class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none transition-all cursor-pointer hover:bg-gray-100" required>
                        <option value="">Pilih Kelas</option>
                        <?php
                        // Reset pointer or fetch again if needed, but here simple query is fine
                        $kelas = $koneksi->query("SELECT * FROM kelas ORDER BY nama_kelas ASC");
                        while ($k = $kelas->fetch_assoc()):
                        ?>
                            <option value="<?= $k['id_kelas'] ?>"><?= $k['nama_kelas'] ?></option>
                        <?php endwhile; ?>
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-3.5 text-gray-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            <div>
                <button type="submit" name="tampilkan" class="w-full px-6 py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/30 flex items-center justify-center gap-2 hover:-translate-y-0.5">
                    <i class="fas fa-users text-sm"></i> Tampilkan Siswa
                </button>
            </div>
        </form>
    </div>

    <?php
    // nampilin siswa
    if (isset($_POST['tampilkan'])) {

        $kelas_asal = $_POST['kelas_asal'];

        $siswa = $koneksi->query("SELECT sk.id_siswakelas, s.id_siswa, s.induk_siswa, s.nama_siswa
            FROM siswakelas sk
            JOIN siswa s ON sk.id_siswa = s.id_siswa
            WHERE sk.id_kelas = '$kelas_asal'
              AND s.status = 'AKTIF'
            ORDER BY s.nama_siswa ASC
        ");
    ?>
    
    <div class="bg-white border border-gray-100 rounded-2xl shadow-soft overflow-hidden fade-in">
        <form method="post" id="processForm">
            <input type="hidden" name="kelas_asal" value="<?= $kelas_asal ?>">
            
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-700 font-heading">Daftar Siswa</h3>
                <span class="text-xs font-semibold bg-blue-100 text-blue-600 px-2 py-0.5 rounded border border-blue-200">
                    <?= $siswa->num_rows ?> Siswa Ditemukan
                </span>
            </div>

            <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-white text-gray-500 font-semibold uppercase text-xs tracking-wider border-b border-gray-100 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-4 w-16 text-center">
                                <input type="checkbox" id="checkAll" checked class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 cursor-pointer">
                            </th>
                            <th class="px-6 py-4">NIS</th>
                            <th class="px-6 py-4">Nama Siswa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($siswa->num_rows == 0): ?>
                             <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                            <i class="fas fa-user-slash text-2xl"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-800">Tidak ada data siswa</h3>
                                        <p class="text-gray-500 text-sm mt-1">Tidak ditemukan siswa aktif di kelas ini.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php while ($s = $siswa->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-3 text-center">
                                        <input type="checkbox" name="id_siswa[]" value="<?= $s['id_siswa'] ?>" checked class="student-checkbox w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 cursor-pointer">
                                    </td>
                                    <td class="px-6 py-3 font-mono text-gray-500 text-xs"><?= $s['induk_siswa'] ?></td>
                                    <td class="px-6 py-3 font-bold text-gray-700"><?= $s['nama_siswa'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($siswa->num_rows > 0): ?>
            <div class="p-6 bg-gray-50/50 border-t border-gray-100">
                <div class="flex flex-col md:flex-row gap-6 items-end">
                    <div class="w-full md:w-1/3">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Kelas Tujuan (Naik Kelas)</label>
                        <div class="relative">
                            <select name="kelas_tujuan" class="w-full pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none transition-all shadow-sm">
                                <option value="">Pilih Kelas Tujuan</option>
                                <?php
                                $kelas = $koneksi->query("SELECT * FROM kelas ORDER BY nama_kelas ASC");
                                while ($k = $kelas->fetch_assoc()):
                                ?>
                                    <option value="<?= $k['id_kelas'] ?>"><?= $k['nama_kelas'] ?></option>
                                <?php endwhile; ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-3.5 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="flex gap-3 w-full md:w-auto ml-auto">
                        <button type="submit" name="naikkan" class="flex-1 md:flex-none px-6 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/30 flex items-center justify-center gap-2"
                            onclick="return confirm('Yakin naikkan kelas siswa terpilih?')">
                            <i class="fas fa-level-up-alt"></i> Naikkan Kelas
                        </button>

                        <button type="submit" name="Luluskan" class="flex-1 md:flex-none px-6 py-2.5 bg-rose-600 text-white font-medium rounded-xl hover:bg-rose-700 transition-all shadow-lg shadow-rose-500/30 flex items-center justify-center gap-2"
                            onclick="return confirm('Yakin LULUSKAN siswa terpilih? Aksi ini tidak dapat dibatalkan dengan mudah.')">
                            <i class="fas fa-graduation-cap"></i> Luluskan
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </form>
    </div>
    
    <script>
        document.getElementById('checkAll').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('.student-checkbox');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });
    </script>
    <?php } ?>
</div>

<?php
// Logic for processing actions
if (isset($_POST['naikkan'])) {
    if (empty($_POST['id_siswa'])) {
        echo "<script>alert('Pilih siswa terlebih dahulu');history.back();</script>";
        exit;
    }
    $kelas_asal   = $_POST['kelas_asal'];
    $kelas_tujuan = $_POST['kelas_tujuan'];
    $data         = $_POST['id_siswa'];

    if (empty($kelas_tujuan)) {
        echo "<script>alert('Pilih kelas tujuan!');history.back();</script>";
        exit;
    }

    foreach ($data as $id_siswa) {
        $koneksi->query("DELETE FROM siswakelas WHERE id_siswa='$id_siswa' AND id_kelas='$kelas_asal'");
        $koneksi->query("INSERT INTO siswakelas (id_siswa, id_kelas) VALUES ('$id_siswa', '$kelas_tujuan')");
    }
    echo "<script>alert('Kenaikan kelas berhasil');location='index.php?halaman=kelas';</script>";
}

if (isset($_POST['Luluskan'])) {
    if (empty($_POST['id_siswa'])) {
        echo "<script>alert('Pilih siswa terlebih dahulu');history.back();</script>";
        exit;
    }
    $data = $_POST['id_siswa'];
    foreach ($data as $id_siswa) {
        $koneksi->query("UPDATE siswa SET status='LULUS' WHERE id_siswa='$id_siswa'");
        $koneksi->query("DELETE FROM siswakelas WHERE id_siswa='$id_siswa'");
    }
    echo "<script>alert('Siswa berhasil diluluskan');location='index.php?halaman=alumni';</script>";
}
?>
