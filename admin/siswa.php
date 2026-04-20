<?php
// Prevent direct access if needed, though index.php handles it.
// Fetch data
$siswa = array();
$ambil = $koneksi->query("
    SELECT siswa.*, tahun.tahun_ajaran, 
    (SELECT COUNT(*) FROM siswakelas WHERE siswakelas.id_siswa = siswa.id_siswa) as has_class
    FROM siswa
    LEFT JOIN tahun ON siswa.id_tahun = tahun.id_tahun
    WHERE siswa.status = 'AKTIF'
    ORDER BY siswa.id_siswa DESC
");

// Error handling for query
if (!$ambil) {
    echo "<div class='p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50' role='alert'>
            <span class='font-medium'>Query Error!</span> " . $koneksi->error . "
          </div>";
} else {
    while($tiap = $ambil->fetch_assoc()){
        $siswa[] = $tiap;
    }
}
?>

<div class="space-y-6 fade-in">
    <!-- Header & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold font-heading text-gray-800">Data Siswa</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola data seluruh siswa yang terdaftar.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Cari siswa..." 
                    class="pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-full sm:w-64 transition-all shadow-sm">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left" id="siswaTable">
                <thead class="bg-gray-50/50 text-gray-500 font-semibold uppercase text-xs tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4">Informasi Siswa</th>
                        <th class="px-6 py-4">Tahun Masuk</th>
                        <th class="px-6 py-4 text-center">Status Kelas</th>
                        <th class="px-6 py-4 text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($siswa)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3 text-gray-400">
                                        <i class="fas fa-user-slash text-2xl"></i>
                                    </div>
                                    <p class="font-medium">Belum ada data siswa.</p>
                                    <p class="text-xs mt-1">Silakan tambahkan data siswa baru.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($siswa as $key => $value): 
                            $initial = strtoupper(substr($value['nama_siswa'], 0, 1));
                            $colorIndex = $key % 5;
                            $bgColors = ['bg-blue-100 text-blue-600', 'bg-emerald-100 text-emerald-600', 'bg-violet-100 text-violet-600', 'bg-amber-100 text-amber-600', 'bg-rose-100 text-rose-600'];
                            $avatarColor = $bgColors[$colorIndex];
                            
                            $statusKelasClass = $value['has_class'] > 0 
                                ? 'bg-emerald-50 text-emerald-600 border-emerald-100' 
                                : 'bg-gray-100 text-gray-500 border-gray-200';
                            $statusLabel = $value['has_class'] > 0 
                                ? 'Sudah Masuk Kelas' 
                                : 'Belum Masuk Kelas';
                            
                            $foto = $value['foto_siswa'];
                            $hasFoto = !empty($foto) && file_exists("../siswa-foto/$foto");
                            $imageSrc = $hasFoto ? "../siswa-foto/$foto" : "";
                        ?>
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 text-center text-gray-400 font-mono text-xs"><?= $key + 1 ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <?php if($hasFoto): ?>
                                        <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-200 shadow-sm flex-shrink-0">
                                            <img src="<?= $imageSrc ?>" alt="<?= $value['nama_siswa'] ?>" class="w-full h-full object-cover">
                                        </div>
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded-full <?= $avatarColor ?> flex items-center justify-center font-bold text-sm shadow-sm flex-shrink-0">
                                            <?= $initial ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="font-bold text-gray-800 group-hover:text-primary-600 transition-colors"><?= $value['nama_siswa'] ?></div>
                                        <div class="text-xs text-gray-400 font-mono mt-0.5">NIS: <?= $value['induk_siswa'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2.5 py-1 rounded border border-gray-200">
                                    <i class="far fa-calendar-alt mr-1"></i> <?= $value['tahun_ajaran'] ?? '-' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="<?= $statusKelasClass ?> text-xs font-bold px-3 py-1 rounded-full border inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full <?= $value['has_class'] > 0 ? 'bg-emerald-500' : 'bg-gray-400' ?>"></span>
                                    <?= $statusLabel ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="index.php?halaman=siswa_detail&id=<?= $value['id_siswa'] ?>" 
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition" 
                                       title="Lihat Detail">
                                       <i class="fas fa-eye text-xs"></i>
                                    </a>
                                     <a href="index.php?halaman=siswa_edit&id=<?= $value['id_siswa'] ?>" 
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 hover:text-amber-700 transition" 
                                       title="Edit Data">
                                       <i class="fas fa-pencil-alt text-xs"></i>
                                    </a>
                                    <a href="index.php?halaman=siswamain_hapus&id=<?= $value['id_siswa'] ?>" 
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus data siswa ini? Data yang terhapus tidak dapat dikembalikan.')"
                                       title="Hapus Data">
                                       <i class="fas fa-trash text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination / Footer (Static for now) -->
        <div class="bg-gray-50/50 px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-500">Menampilkan <strong><?= count($siswa) ?></strong> data siswa</span>
            
            <!-- Simple JS Pagination if needed later, for now just static placeholder or nothing -->
        </div>
    </div>
</div>

<script>
    // Simple Search Functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelector("#siswaTable tbody").rows;
        
        for (let i = 0; i < rows.length; i++) {
            let cells = rows[i].cells;
            let match = false;
            // Search in Name (index 1) and NIS (index 1 child)
            let nameText = cells[1].textContent || cells[1].innerText;
            let nisText = cells[1].textContent || cells[1].innerText; // Contains both
            
            if (nameText.toUpperCase().indexOf(filter) > -1) {
                match = true;
            }
            
            rows[i].style.display = match ? "" : "none";
        }
    });
</script>
