<?php
$kategori = array();
$ambil_kategori = $koneksi->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
while($tiap = $ambil_kategori->fetch_assoc()){
    $kategori[] = $tiap;
}

if (isset($_POST['simpan'])) {
    $nama_mapel = $_POST['nama_mapel'];
    $id_kategori = $_POST['id_kategori'];

    if (!empty($nama_mapel) && !empty($id_kategori)) {
        // Prevent SQL Injection using basic escaping for now to match project style
        $nama_mapel = $koneksi->real_escape_string($nama_mapel);
        $id_kategori = $koneksi->real_escape_string($id_kategori);
        
        $koneksi->query("INSERT INTO mapel (id_kategori, nama_mapel) VALUES ('$id_kategori', '$nama_mapel')");
        
        echo "<script>alert('Mata Pelajaran berhasil ditambahkan');</script>";
        echo "<script>location='index.php?halaman=mapel';</script>";
    } else {
        echo "<script>alert('Kolom tidak boleh kosong');</script>";
    }
}
?>

<div class="max-w-2xl mx-auto fade-in">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6 sticky top-0 bg-gray-50/90 backdrop-blur-md p-4 rounded-xl shadow-sm z-20 border border-gray-200">
        <div>
            <h2 class="text-xl font-bold font-heading text-gray-800">Tambah Mata Pelajaran</h2>
            <p class="text-gray-500 text-sm mt-1">Registrasi pelajaran baru ke dalam sistem akademik.</p>
        </div>
        <a href="index.php?halaman=mapel" class="bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 px-4 py-2 rounded-xl text-sm font-medium transition flex items-center shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-soft overflow-hidden">
        <form method="post" class="p-8 space-y-6">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Mata Pelajaran</label>
                    <input type="text" name="nama_mapel" placeholder="Contoh: Matematika Peminatan" required autofocus
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all outline-none bg-gray-50 focus:bg-white text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori Mapel</label>
                    <div class="relative">
                        <select name="id_kategori" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all outline-none bg-gray-50 focus:bg-white text-sm appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih Kategori...</option>
                            <?php foreach ($kategori as $k): ?>
                                <option value="<?= $k['id_kategori'] ?>"><?= $k['nama_kategori'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-3.5 text-gray-400 pointer-events-none"></i>
                    </div>
                    <p class="text-xs text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>Kategori mempengaruhi struktur rapor akademik.</p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" name="simpan" class="bg-primary-600 text-white hover:bg-primary-700 px-6 py-2.5 rounded-xl text-sm font-bold transition-all shadow-md hover:shadow-lg flex items-center">
                    <i class="fas fa-save mr-2"></i> Simpan Mata Pelajaran
                </button>
            </div>
        </form>
    </div>
</div>
