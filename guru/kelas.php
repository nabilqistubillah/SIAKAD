<?php
$id_guru = $_SESSION['guru']['id_guru'];

// Get distinct classes where this teacher is Wali Kelas
$ambil = $koneksi->query("
    SELECT k.id_kelas, k.nama_kelas, j.nama_jurusan, 
           COUNT(sk.id_siswa) as jml_siswa
    FROM kelas k
    LEFT JOIN jurusan j ON k.id_jurusan = j.id_jurusan
    LEFT JOIN siswakelas sk ON k.id_kelas = sk.id_kelas
    WHERE k.id_guru = '$id_guru'
    GROUP BY k.id_kelas, k.nama_kelas, j.nama_jurusan
    ORDER BY k.nama_kelas ASC
");
?>

<div class="space-y-6 fade-in">
    <!-- Header & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold font-heading text-gray-800">Daftar Kelas Anda</h2>
            <p class="text-gray-500 text-sm mt-1">Pilih kelas untuk melihat daftar siswa di dalamnya.</p>
        </div>
    </div>

    <!-- Grid Layout -->
    <?php if ($ambil->num_rows == 0): ?>
        <div class="bg-white border border-gray-100 rounded-2xl shadow-soft p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <i class="fas fa-door-open text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-800">Belum ada kelas yang ditugaskan</h3>
            <p class="text-gray-500 text-sm mt-1">Anda belum dijadwalkan mengajar di kelas manapun.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($kelas = $ambil->fetch_assoc()): ?>
                <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden group hover:shadow-lg transition-all hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl">
                                <i class="fas fa-chalkboard"></i>
                            </div>
                            <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full border border-gray-200">
                                <?= $kelas['jml_siswa'] ?> Siswa
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-800 font-heading mb-1"><?= $kelas['nama_kelas'] ?></h3>
                        <p class="text-sm text-gray-500 mb-6"><?= $kelas['nama_jurusan'] ?></p>
                        
                        <a href="index.php?halaman=siswa&id_kelas=<?= $kelas['id_kelas'] ?>" 
                           class="w-full btn btn-primary flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <i class="fas fa-users mr-2"></i> Lihat Data Siswa
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>
