<?php
$id = $_GET['id'];

// Get info kelas
$ambil = $koneksi->query("SELECT * FROM kelas
    LEFT JOIN tahun ON kelas.id_tahun=tahun.id_tahun
    LEFT JOIN jurusan ON kelas.id_jurusan=jurusan.id_jurusan
    WHERE kelas.id_kelas='$id'");
$kelas = $ambil->fetch_assoc();

// Get siswa in class
$siswakelas = array();
$ambil = $koneksi->query("SELECT * FROM siswakelas
 LEFT JOIN siswa ON siswakelas.id_siswa=siswa.id_siswa
 LEFT JOIN tahun ON siswa.id_tahun=tahun.id_tahun
 WHERE siswakelas.id_kelas = '$id'");
while ($tiap = $ambil->fetch_assoc()) {
    $siswakelas[] = $tiap;
}
?>

<div class="max-w-6xl mx-auto fade-in">
    
    <!-- Action Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6 bg-white p-4 rounded-xl border border-gray-200 shadow-sm sticky top-0 z-10">
         <div>
            <h2 class="text-xl font-bold text-gray-800 font-heading">Manajemen Kelas</h2>
            <div class="flex items-center gap-2 text-sm text-gray-500 mt-1">
                 <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded border border-indigo-100 font-medium"><?= $kelas['nama_kelas'] ?></span>
                 <span class="text-gray-300">|</span>
                 <span><?= $kelas['nama_jurusan'] ?></span>
                 <span class="text-gray-300">|</span>
                 <span><?= $kelas['tahun_ajaran'] ?></span>
            </div>
        </div>
        <div class="flex gap-3">
             <a href="index.php?halaman=kelas" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <button class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200" data-bs-toggle="modal" data-bs-target="#modal-masukkan-siswa">
                <i class="fas fa-user-plus mr-2"></i>Tambah Siswa
            </button>
        </div>
    </div>

    <!-- Student List Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Daftar Siswa Kelas</h3>
            <span class="text-xs font-medium bg-gray-200 text-gray-700 px-2 py-1 rounded-full"><?= count($siswakelas) ?> Siswa</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-white text-gray-500 font-semibold uppercase text-xs tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4">NIS</th>
                        <th class="px-6 py-4 text-center">Tahun Masuk</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($siswakelas)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-users-slash text-4xl mb-3 opacity-50"></i>
                                    <p class="font-medium text-gray-600">Belum ada siswa di kelas ini.</p>
                                    <p class="text-xs mt-1">Gunakan tombol "Tambah Siswa" untuk memulai.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($siswakelas as $key => $value): 
                             $initial = strtoupper(substr($value['nama_siswa'], 0, 1));
                        ?>
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-4 text-center text-gray-400 font-mono text-xs"><?= $key + 1 ?></td>
                                <td class="px-6 py-4">
                                     <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                            <?= $initial ?>
                                        </div>
                                        <div class="font-medium text-gray-800 group-hover:text-indigo-600 transition-colors">
                                            <?= $value['nama_siswa'] ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-gray-500 text-xs"><?= $value['induk_siswa'] ?></td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded border border-gray-200"><?= $value['tahun_ajaran'] ?></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                        <a href="index.php?halaman=siswa_detail&id=<?= $value['id_siswa']; ?>" 
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 transition-colors" title="Lihat Detail">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="index.php?halaman=siswa_hapus&id=<?= $value['id_siswakelas']; ?>&id_kelas=<?= $id; ?>" 
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 transition-colors" title="Hapus dari Kelas" onclick="return confirm('Hapus siswa ini dari kelas?')">
                                            <i class="fas fa-user-minus text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Masukkan Siswa -->
<?php
//siswa yang blm ada kelas
$nokelas = array();
$idt = $kelas['id_tahun'];
$ambil = $koneksi->query("
    SELECT * FROM siswa
    WHERE id_siswa NOT IN (SELECT id_siswa FROM siswakelas)
    ORDER BY nama_siswa ASC
");
while ($tiap = $ambil->fetch_assoc()) {
    $nokelas[] = $tiap;
}
?>

<div class="modal fade" id="modal-masukkan-siswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-2xl overflow-hidden">
            <div class="modal-header bg-gray-50 border-b border-gray-100 px-6 py-4">
                <div>
                     <h5 class="modal-title font-bold text-gray-800">Tambahkan Siswa</h5>
                     <p class="text-xs text-gray-500 mt-1">Pilih siswa yang belum memiliki kelas.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form method="post">
                <div class="modal-body p-0">
                    <div class="max-h-[400px] overflow-y-auto p-6 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <?php if (empty($nokelas)): ?>
                            <div class="col-span-2 text-center py-8 text-gray-400">
                                <i class="fas fa-check-circle text-4xl mb-3 text-emerald-200"></i>
                                <p>Semua siswa sudah memiliki kelas.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($nokelas as $key => $value): ?>
                                <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-200 cursor-pointer transition-all group">
                                    <input type="checkbox" name="id_siswa[]" value="<?= $value['id_siswa'] ?>" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <div>
                                        <div class="font-medium text-sm text-gray-700 group-hover:text-indigo-700"><?= $value['nama_siswa'] ?></div>
                                        <div class="text-xs text-gray-400 font-mono"><?= $value['induk_siswa'] ?></div>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-xs text-gray-500"><?= count($nokelas) ?> Siswa tersedia</span>
                    <button type="submit" name="simpan" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30 text-sm">
                        <i class="fas fa-plus mr-2"></i>Tambahkan Terpilih
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['simpan'])) {
    if (!empty($_POST['id_siswa'])) {
        $id_siswanya = $_POST['id_siswa'];
        $id_kelas = $_GET['id'];
    
        foreach ($id_siswanya as $key => $value) {
            $koneksi->query("INSERT INTO siswakelas (id_siswa, id_kelas) VALUES ('$value', '$id_kelas')");
        }
        echo "<script>alert('Siswa Berhasil dimasukkan ke kelas')</script>";
        echo "<script>location='index.php?halaman=siswakelas&id=$id_kelas'</script>";
    } else {
         echo "<script>alert('Pilih siswa terlebih dahulu!')</script>";
    }
}
?>