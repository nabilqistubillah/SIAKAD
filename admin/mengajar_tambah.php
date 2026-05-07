<?php
// Fetch Guru
$guru = [];
$g_res = $koneksi->query("SELECT * FROM guru ORDER BY nama_guru ASC");
while($g = $g_res->fetch_assoc()) $guru[] = $g;

// Fetch Mapel
$mapel = [];
$m_res = $koneksi->query("SELECT m.*, k.nama_kategori FROM mapel m LEFT JOIN kategori k ON m.id_kategori = k.id_kategori ORDER BY m.nama_mapel ASC");
while($m = $m_res->fetch_assoc()) $mapel[] = $m;

// Fetch Kelas
$kelas = [];
$k_res = $koneksi->query("SELECT * FROM kelas ORDER BY nama_kelas ASC");
while($k = $k_res->fetch_assoc()) $kelas[] = $k;

if (isset($_POST['simpan'])) {
    $id_guru = $_POST['id_guru'] ?? 0; // Optional, can be 0 or null
    $id_mapel = $_POST['id_mapel'];
    $id_kelas = $_POST['id_kelas'];
    $semester = $_POST['semester'];
    $kkm = $_POST['kkm'];

    $koneksi->query("INSERT INTO mengajar (id_guru, id_mapel, id_kelas, semester, kkm) VALUES ('$id_guru', '$id_mapel', '$id_kelas', '$semester', '$kkm')");
    
    echo "<script>alert('Jadwal Mengajar berhasil ditambahkan'); location='index.php?halaman=mengajar';</script>";
    exit;
}
?>

<div class="max-w-2xl mx-auto fade-in">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6 sticky top-0 bg-gray-50/90 backdrop-blur-md p-4 rounded-xl shadow-sm z-20 border border-gray-200">
        <div>
            <h2 class="text-xl font-bold font-heading text-gray-800">Tambah Jadwal Mengajar</h2>
            <p class="text-gray-500 text-sm mt-1">Tambahkan penugasan mata pelajaran ke kelas.</p>
        </div>
        <a href="index.php?halaman=mengajar" class="bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 px-4 py-2 rounded-xl text-sm font-medium transition flex items-center shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-soft overflow-hidden">
        <form method="post" class="p-8 space-y-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Guru Pengajar <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <select name="id_guru" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none bg-gray-50">
                        <option value="0">-- Tidak Ada / Kosongkan --</option>
                        <?php foreach($guru as $g): ?>
                            <option value="<?= $g['id_guru'] ?>"><?= $g['nama_guru'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-400 mt-2"><i class="fas fa-info-circle mr-1"></i>Bisa dikosongkan karena nilai akan diinput oleh Wali Kelas.</p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select name="id_mapel" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none bg-gray-50">
                        <option value="" disabled selected>Pilih Mata Pelajaran...</option>
                        <?php foreach($mapel as $m): ?>
                            <option value="<?= $m['id_mapel'] ?>"><?= $m['nama_mapel'] ?> (<?= $m['nama_kategori'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kelas <span class="text-red-500">*</span></label>
                    <select name="id_kelas" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none bg-gray-50">
                        <option value="" disabled selected>Pilih Kelas...</option>
                        <?php foreach($kelas as $k): ?>
                            <option value="<?= $k['id_kelas'] ?>"><?= $k['nama_kelas'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Semester <span class="text-red-500">*</span></label>
                        <select name="semester" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none bg-gray-50">
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">KKM <span class="text-red-500">*</span></label>
                        <input type="number" name="kkm" placeholder="Contoh: 75" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none bg-gray-50">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" name="simpan" class="bg-primary-600 text-white hover:bg-primary-700 px-6 py-2.5 rounded-xl text-sm font-bold transition-all shadow-md flex items-center">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
