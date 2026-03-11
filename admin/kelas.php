<?php
$kelas = array();
// Fetch class data with student count
$ambil = $koneksi->query("
    SELECT kelas.*, jurusan.nama_jurusan, tahun.tahun_ajaran,
    (SELECT COUNT(*) FROM siswakelas WHERE siswakelas.id_kelas = kelas.id_kelas) as jumlah_siswa
    FROM kelas
    LEFT JOIN jurusan ON kelas.id_jurusan=jurusan.id_jurusan
    LEFT JOIN tahun ON kelas.id_tahun=tahun.id_tahun
    ORDER BY kelas.id_kelas DESC
");

if (!$ambil) {
    echo "<div class='p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50' role='alert'>
            <span class='font-medium'>Query Error!</span> " . $koneksi->error . "
          </div>";
} else {
    while($tiap = $ambil->fetch_assoc()){
        $kelas[] = $tiap;
    }
}
?>

<div class="space-y-6 fade-in">
    <!-- Header & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold font-heading text-gray-800">Manajemen Kelas</h2>
            <p class="text-gray-500 text-sm mt-1">Atur data kelas, jurusan, dan jenjang pendidikan.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
             <div class="relative">
                <input type="text" id="searchKelas" placeholder="Cari info kelas..." 
                    class="pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-full sm:w-64 transition-all shadow-sm">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
            </div>
            <a href="index.php?halaman=kelas_tambah" class="btn btn-primary shadow-lg shadow-primary-500/30">
                <i class="fas fa-plus mr-2"></i> Tambah Kelas
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left" id="kelasTable">
                <thead class="bg-gray-50/50 text-gray-500 font-semibold uppercase text-xs tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4">Nama Kelas</th>
                        <th class="px-6 py-4">Jurusan</th>
                        <th class="px-6 py-4">Tahun Ajaran</th>
                        <th class="px-6 py-4 text-center">Jenjang</th>
                        <th class="px-6 py-4 text-center">Jml Siswa</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($kelas)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3 text-gray-400">
                                        <i class="fas fa-school text-2xl"></i>
                                    </div>
                                    <p class="font-medium">Belum ada data kelas.</p>
                                    <p class="text-xs mt-1">Silakan tambahkan data kelas baru.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($kelas as $key => $value): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 text-center text-gray-400 font-mono text-xs"><?= $key + 1 ?></td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-800 text-md"><?= $value['nama_kelas'] ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                    <span class="text-gray-600"><?= $value['nama_jurusan'] ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2.5 py-1 rounded border border-gray-200">
                                    <i class="far fa-calendar-alt mr-1"></i> <?= $value['tahun_ajaran'] ?? '-' ?>
                                </span>
                            </td>
                             <td class="px-6 py-4 text-center">
                                <span class="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-full border border-blue-100 uppercase">
                                    <?= $value['jenjang_kelas'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-emerald-50 text-emerald-600 text-xs font-bold px-2.5 py-1 rounded-full border border-emerald-100">
                                    <i class="fas fa-users mr-1"></i> <?= $value['jumlah_siswa'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="index.php?halaman=siswakelas&id=<?= $value['id_kelas'] ?>" 
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100 hover:text-sky-700 transition" 
                                       title="Lihat Siswa">
                                       <i class="fas fa-search text-xs"></i>
                                    </a>
                                     <a href="index.php?halaman=kelas_edit&id=<?= $value['id_kelas'] ?>" 
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 transition" 
                                       title="Edit Kelas">
                                       <i class="fas fa-pencil-alt text-xs"></i>
                                    </a>
                                    <!-- Optional: Add Delete if needed, though not in original -->
                                    <!-- 
                                    <a href="index.php?halaman=kelas_hapus&id=<?= $value['id_kelas'] ?>" 
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition" 
                                       onclick="return confirm('Hapus kelas?')"
                                       title="Hapus Kelas">
                                       <i class="fas fa-trash text-xs"></i>
                                    </a>
                                    -->
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
         <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            <p class="text-xs text-center text-gray-500">
                Menampilkan <strong><?= count($kelas) ?></strong> kelas aktif.
            </p>
        </div>
    </div>
</div>

<script>
    // Simple Search Functionality
    document.getElementById('searchKelas').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelector("#kelasTable tbody").rows;
        
        for (let i = 0; i < rows.length; i++) {
            let cells = rows[i].cells;
            if (cells.length < 2) continue; // Skip empty state row

            let textContent = "";
            // Search in Name (1), Jurusan (2), and Tahun (3)
            for (let j = 1; j <= 3; j++) {
                textContent += (cells[j].textContent || cells[j].innerText) + " ";
            }

            if (textContent.toUpperCase().indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                 rows[i].style.display = "none";
            }
        }
    });
</script>