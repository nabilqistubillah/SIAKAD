<?php
if (isset($_POST['simpan'])) {
    
    // Validate inputs
    if(empty($_POST['id_tahun']) || empty($_POST['id_jurusan']) || empty($_POST['nama_kelas'])) {
         echo "<script>alert('Harap lengkapi semua data!');</script>";
    } else {
        $id_tahun = $_POST['id_tahun'];
        $id_jurusan = $_POST['id_jurusan'];
        $id_guru = $_POST['id_guru'] ?? 0;
        $nama_kelas = $_POST['nama_kelas'];
        $jenjang_kelas = $_POST['jenjang_kelas'];
    
        $simpan = $koneksi->query("INSERT INTO kelas (id_tahun, id_jurusan, id_guru, nama_kelas, jenjang_kelas) 
        VALUES ('$id_tahun', '$id_jurusan', '$id_guru', '$nama_kelas', '$jenjang_kelas')");
    
        if ($simpan) {
            echo "<script>alert('Data kelas berhasil ditambahkan');location='index.php?halaman=kelas';</script>";
        } else {
             echo "<script>alert('Gagal menyimpan: " . $koneksi->error . "');</script>";
        }
    }
}

// Fetch Options
$tahun = [];
$ambil = $koneksi->query("SELECT * FROM tahun ORDER BY id_tahun DESC");
while($tiap = $ambil->fetch_assoc()){
    $tahun[] = $tiap;
}

$jurusan = [];
$ambil = $koneksi->query("SELECT * FROM jurusan ORDER BY nama_jurusan ASC");
while($tiap = $ambil->fetch_assoc()){
    $jurusan[] = $tiap;
}

$guru = [];
$ambil = $koneksi->query("SELECT * FROM guru ORDER BY nama_guru ASC");
while($tiap = $ambil->fetch_assoc()){
    $guru[] = $tiap;
}
?>

<div class="max-w-2xl mx-auto fade-in">
    <form method="post">
        
        <!-- Action Header -->
        <div class="flex items-center justify-between mb-6 bg-white p-4 rounded-xl border border-gray-200 shadow-sm sticky top-0 z-10">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Buat Kelas Baru</h2>
                <p class="text-xs text-gray-500">Konfigurasi ruang kelas</p>
            </div>
            <div class="flex gap-3">
                <a href="index.php?halaman=kelas" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit" name="simpan" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200">
                    <i class="fas fa-plus mr-2"></i>Buat Kelas
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-6 border-b border-gray-100 pb-2">Detail Kelas</label>

            <div class="space-y-6">
                
                <!-- Tahun Ajaran -->
                <div>
                   <label class="block text-sm text-gray-600 mb-1">Tahun Ajaran</label>
                   <select name="id_tahun" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors appearance-none" required>
                        <option value="">Pilih Tahun Ajaran</option>
                        <?php foreach($tahun as $value): ?>
                            <option value="<?= $value['id_tahun'] ?>"><?= $value['tahun_ajaran'] ?></option>
                        <?php endforeach; ?>
                   </select>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Jurusan -->
                    <div>
                         <label class="block text-sm text-gray-600 mb-1">Jurusan</label>
                        <select name="id_jurusan" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors appearance-none" required>
                            <option value="">Pilih Jurusan</option>
                            <?php foreach ($jurusan as $value): ?>
                                <option value="<?= $value['id_jurusan'] ?>"><?= $value['nama_jurusan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Jenjang -->
                    <div>
                         <label class="block text-sm text-gray-600 mb-1">Jenjang</label>
                        <select name="jenjang_kelas" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors appearance-none" required>
                            <option value="">Tingkat</option>
                            <option value="10">Kelas 10</option>
                            <option value="11">Kelas 11</option>
                            <option value="12">Kelas 12</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Nama Kelas -->
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nama Kelas</label>
                        <input type="text" name="nama_kelas" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors" placeholder="Contoh: XII RPL 1" required>
                    </div>

                    <!-- Wali Kelas -->
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Wali Kelas (Opsional)</label>
                        <select name="id_guru" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors appearance-none">
                            <option value="0">Pilih Guru / Wali Kelas</option>
                            <?php foreach ($guru as $value): ?>
                                <option value="<?= $value['id_guru'] ?>"><?= $value['nama_guru'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>