<?php
if (isset($_POST['simpan'])) {
    $tahun      = $_POST['id_tahun'];
    $nis        = $_POST['nis'];
    $pass       = sha1($_POST['pass']);
    $nama       = $_POST['nama'];
    $alamat     = $_POST['alamat'];
    
    $foto_nama  = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $foto_nama = date("YmdHis") . "_" . preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/siswa/" . $foto_nama);
    }
    
    $query = $koneksi->query("INSERT INTO siswa (id_tahun, induk_siswa, pw_siswa, nama_siswa, alamat_siswa, foto_siswa, status) 
        VALUES ('$tahun', '$nis', '$pass', '$nama', '$alamat', '$foto_nama', 'AKTIF')");
    
    if ($query) {
        echo "<script>alert('Data siswa berhasil disimpan!');location='index.php?halaman=siswa';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan: " . $koneksi->error . "')</script>";
    }
}

$tahun_data = [];
$ambil = $koneksi->query("SELECT * FROM tahun ORDER BY id_tahun DESC");
while ($tiap = $ambil->fetch_assoc()) {
    $tahun_data[] = $tiap;
}
?>

<div class="max-w-5xl mx-auto fade-in">
    <form method="post" enctype="multipart/form-data">
        
        <!-- Action Header -->
        <div class="flex items-center justify-between mb-6 bg-white p-4 rounded-xl border border-gray-200 shadow-sm sticky top-0 z-10">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Pendaftaran Siswa</h2>
                <p class="text-xs text-gray-500">Input data siswa baru</p>
            </div>
            <div class="flex gap-3">
                <a href="index.php?halaman=siswa" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit" name="simpan" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Academic & Photo -->
            <div class="lg:col-span-1 space-y-6">
                 <!-- Photo Card -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Foto Siswa</label>
                    <div class="flex flex-col items-center">
                        <div class="relative w-32 h-32 mb-4 group cursor-pointer">
                             <div class="w-full h-full rounded-2xl overflow-hidden border-4 border-gray-100 shadow-inner bg-gray-50" id="photo-preview-container-siswa">
                                <img id="preview-img-siswa" class="w-full h-full object-cover hidden">
                                <div id="default-icon-siswa" class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i class="fas fa-user-graduate text-3xl"></i>
                                </div>
                            </div>
                             <input type="file" name="foto" id="foto-input-siswa" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" onchange="previewImageSiswa()">
                            <div class="absolute inset-0 bg-black/40 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                <span class="text-white text-xs font-medium">Ubah Foto</span>
                            </div>
                        </div>
                        <p class="text-xs text-center text-gray-400">Klik gambar untuk mengunggah.<br>JPG/PNG maks 2MB.</p>
                    </div>
                </div>

                <!-- Academic Info -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Akademik</label>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tahun Masuk</label>
                            <select name="id_tahun" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors appearance-none" required>
                                <option value="">Pilih Tahun Ajaran</option>
                                <?php foreach ($tahun_data as $t): ?>
                                    <option value="<?= $t['id_tahun'] ?>"><?= $t['tahun_ajaran'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">NIS</label>
                            <input type="text" name="nis" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors" placeholder="Nomor Induk Siswa" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Personal Data -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm h-full">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-6 border-b border-gray-100 pb-2">Data Pribadi</label>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                             <div>
                                <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
                                <input type="text" name="nama" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors" placeholder="Nama sesuai ijazah" required>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Password Akun</label>
                                <input type="password" name="pass" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors" placeholder="••••••" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Alamat Domisili</label>
                            <textarea name="alamat" rows="5" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors resize-none" placeholder="Alamat lengkap..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    function previewImageSiswa() {
        const input = document.getElementById('foto-input-siswa');
        const previewImg = document.getElementById('preview-img-siswa');
        const defaultIcon = document.getElementById('default-icon-siswa');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                defaultIcon.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
