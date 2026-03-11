<?php
if (isset($_POST['simpan'])) {
    $namafoto = $_FILES['foto']['name'];
    $lokasifoto = $_FILES['foto']['tmp_name'];
    
    // Rename file if uploaded
    if (!empty($namafoto)) {
        $namafoto = date("YmdHis") . "_" . preg_replace("/[^a-zA-Z0-9.]/", "", $namafoto);
        move_uploaded_file($lokasifoto, "../assets/guru/" . $namafoto);
    } else {
        $namafoto = "";
    }

    $ps = sha1($_POST['password']);
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $jk = $_POST['jk'];
    $alamat = $_POST['alamat'];
    
    $simpan = $koneksi->query("INSERT INTO guru (induk_guru, pw_guru, nama_guru, kelamin_guru, alamat_guru, foto_guru) 
        VALUES ('$nip', '$ps', '$nama', '$jk', '$alamat', '$namafoto')");

    if ($simpan) {
        echo "<script>alert('Data guru berhasil disimpan');location='index.php?halaman=guru';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data: " . $koneksi->error . "');</script>";
    }
} 
?>

<div class="max-w-5xl mx-auto fade-in">
    <form action="" method="post" enctype="multipart/form-data">
        
        <!-- Action Header (Enterprise Style) -->
        <div class="flex items-center justify-between mb-6 bg-white p-4 rounded-xl border border-gray-200 shadow-sm sticky top-0 z-10">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Tambah Guru</h2>
                <p class="text-xs text-gray-500">Entri data pengajar baru</p>
            </div>
            <div class="flex gap-3">
                <a href="index.php?halaman=guru" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit" name="simpan" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200">
                    <i class="fas fa-check mr-2"></i>Simpan
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Photo & Basic Account -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Photo Card -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Foto Profil</label>
                    
                    <div class="flex flex-col items-center">
                        <div class="relative w-32 h-32 mb-4 group cursor-pointer">
                            <div class="w-full h-full rounded-full overflow-hidden border-4 border-gray-100 shadow-inner bg-gray-50" id="photo-preview-container">
                                <img id="preview-img" class="w-full h-full object-cover hidden">
                                <div id="default-icon" class="w-full h-full flex items-center justify-center text-gray-300">
                                    <i class="fas fa-camera text-3xl"></i>
                                </div>
                            </div>
                            <input type="file" name="foto" id="foto-upload" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" onchange="previewFile()">
                            <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                <span class="text-white text-xs font-medium">Ubah Foto</span>
                            </div>
                        </div>
                        <p class="text-xs text-center text-gray-400">Klik gambar untuk mengunggah.<br>JPG/PNG maks 2MB.</p>
                    </div>
                </div>

                <!-- Account Credentials -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                     <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Akun Login</label>
                     <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Password</label>
                            <input type="password" name="password" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors" placeholder="••••••" required>
                        </div>
                     </div>
                </div>
            </div>

            <!-- Right Column: Personal Details -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm h-full">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-6 border-b border-gray-100 pb-2">Informasi Pribadi</label>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">NIP</label>
                            <input type="text" name="nip" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors" placeholder="Nomor Induk Pegawai" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors" placeholder="Nama & Gelar" required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm text-gray-600 mb-2">Jenis Kelamin</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer p-3 border border-gray-200 rounded-lg hover:bg-gray-50 w-full transition-colors">
                                <input type="radio" name="jk" value="Laki-laki" class="text-indigo-600 focus:ring-indigo-500" required>
                                <span class="text-sm text-gray-700">Laki-laki</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer p-3 border border-gray-200 rounded-lg hover:bg-gray-50 w-full transition-colors">
                                <input type="radio" name="jk" value="Perempuan" class="text-indigo-600 focus:ring-indigo-500" required>
                                <span class="text-sm text-gray-700">Perempuan</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="4" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors resize-none" placeholder="Jalan, RT/RW, Kelurahan..."></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewFile() {
        const input = document.getElementById('foto-upload');
        const previewImg = document.getElementById('preview-img');
        const defaultIcon = document.getElementById('default-icon');
        
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