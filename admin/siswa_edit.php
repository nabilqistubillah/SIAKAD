<?php
$id = $_GET['id'];

$siswa = $koneksi->query("
    SELECT * FROM siswa 
    WHERE id_siswa='$id'
")->fetch_assoc();

// ambil tahun ajaran
$tahun = [];
$q = $koneksi->query("SELECT * FROM tahun");
while ($t = $q->fetch_assoc()) {
    $tahun[] = $t;
}

// ambil kelas siswa sekarang
$kelas = $koneksi->query("
    SELECT sk.id_kelas, k.nama_kelas 
    FROM siswakelas sk
    JOIN kelas k ON sk.id_kelas = k.id_kelas
    WHERE sk.id_siswa='$id'
")->fetch_assoc();

// ambil semua kelas
$daftar_kelas = [];
$q2 = $koneksi->query("SELECT * FROM kelas");
while ($kl = $q2->fetch_assoc()) {
    $daftar_kelas[] = $kl;
}
?>

<div class="max-w-5xl mx-auto fade-in">
    <form method="post" enctype="multipart/form-data">

        <!-- Action Header -->
        <div class="flex items-center justify-between mb-6 bg-white p-4 rounded-xl border border-gray-200 shadow-sm sticky top-0 z-10">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Edit Data Siswa</h2>
                <p class="text-xs text-gray-500">Perbarui informasi siswa</p>
            </div>
            <div class="flex gap-3">
                <a href="index.php?halaman=siswa_detail&id=<?php echo $id; ?>" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit" name="simpan" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>

         <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Col: Photo & Basic Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Photo -->
                 <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Foto Profil</label>
                    <div class="flex flex-col items-center">
                         <div class="relative w-32 h-32 mb-4 group cursor-pointer">
                            <div class="w-full h-full rounded-2xl overflow-hidden border-4 border-gray-100 shadow-inner bg-gray-50">
                                <?php if (!empty($siswa['foto_siswa'])): ?>
                                    <img id="preview-img-edit" src="../assets/siswa/<?= $siswa['foto_siswa']; ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                     <img id="preview-img-edit" class="w-full h-full object-cover hidden">
                                     <div id="default-icon-edit" class="w-full h-full flex items-center justify-center text-gray-300">
                                        <i class="fas fa-user text-3xl"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="foto" id="foto-input-edit" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" onchange="previewImageEdit()">
                            <div class="absolute inset-0 bg-black/40 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                <span class="text-white text-xs font-medium">Ganti Foto</span>
                            </div>
                        </div>
                        <p class="text-xs text-center text-gray-400">Klik gambar untuk mengganti.</p>
                    </div>
                </div>

                <!-- Academic -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Data Akademik</label>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tahun Masuk</label>
                            <select name="id_tahun" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors" required>
                                <?php foreach ($tahun as $t): ?>
                                    <option value="<?= $t['id_tahun']; ?>" <?= $t['id_tahun'] == $siswa['id_tahun'] ? 'selected' : '' ?>>
                                        <?= $t['tahun_ajaran']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Kelas Saat Ini</label>
                             <select name="id_kelas" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors">
                                <option value="">Pilih Kelas</option>
                                <?php foreach ($daftar_kelas as $dk): ?>
                                    <option value="<?= $dk['id_kelas']; ?>" <?= $kelas && $kelas['id_kelas'] == $dk['id_kelas'] ? 'selected' : '' ?>>
                                        <?= $dk['nama_kelas']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">NIS</label>
                            <input type="text" name="nis" value="<?= $siswa['induk_siswa']; ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Col: Personal Info -->
             <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm h-full">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-6 border-b border-gray-100 pb-2">Informasi Pribadi</label>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama" value="<?= $siswa['nama_siswa']; ?>" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors" required>
                        </div>

                         <div>
                            <label class="block text-sm text-gray-600 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" rows="5" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors resize-none"><?= $siswa['alamat_siswa']; ?></textarea>
                        </div>
                    </div>
                </div>
             </div>

         </div>
    </form>
</div>

<script>
    function previewImageEdit() {
        const input = document.getElementById('foto-input-edit');
        const previewImg = document.getElementById('preview-img-edit');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php
if (isset($_POST['simpan'])) {

    $id_tahun = $_POST['id_tahun'];
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $id_kelas_baru = $_POST['id_kelas'];

    // PROSES FOTO
    if (!empty($_FILES['foto']['name'])) {
        $nama_baru = date("YmdHis") . "_" . basename($_FILES['foto']['name']);
        $folder = "../assets/siswa/";

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $folder . $nama_baru)) {
            if (!empty($siswa['foto_siswa']) && file_exists($folder . $siswa['foto_siswa'])) {
                unlink($folder . $siswa['foto_siswa']);
            }
            $koneksi->query("UPDATE siswa SET foto_siswa='$nama_baru' WHERE id_siswa='$id'");
        }
    }

    // update data pribadi
    $koneksi->query("UPDATE siswa SET id_tahun='$id_tahun', induk_siswa='$nis', nama_siswa='$nama', alamat_siswa='$alamat' WHERE id_siswa='$id'");

    // update kelas
    $koneksi->query("UPDATE siswakelas SET id_kelas='$id_kelas_baru' WHERE id_siswa='$id'");

    echo "<script>alert('Perubahan berhasil disimpan');</script>";
    echo "<script>location='index.php?halaman=siswa_detail&id=$id';</script>";
}
?>