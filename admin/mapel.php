<?php
$mapel = array();
// Fetch Mata Pelajaran with Category
$ambil = $koneksi->query("
    SELECT mapel.*, kategori.nama_kategori 
    FROM mapel 
    LEFT JOIN kategori ON mapel.id_kategori = kategori.id_kategori
    ORDER BY kategori.nama_kategori ASC, mapel.nama_mapel ASC
");

if (!$ambil) {
    echo "<div class='p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50' role='alert'>
            <span class='font-medium'>Query Error!</span> " . $koneksi->error . "
          </div>";
} else {
    while($tiap = $ambil->fetch_assoc()){
        $mapel[] = $tiap;
    }
}
?>

<div class="space-y-6 fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold font-heading text-gray-800">Mata Pelajaran</h2>
            <p class="text-gray-500 text-sm mt-1">Daftar kurikulum mata pelajaran sekolah.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative">
                 <input type="text" id="searchMapel" placeholder="Cari pelajaran..." 
                    class="pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-full sm:w-64 transition-all shadow-sm">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
            </div>
            <a href="index.php?halaman=mapel_tambah" class="bg-primary-600 text-white hover:bg-primary-700 px-4 py-2 rounded-xl text-sm font-medium transition flex items-center justify-center shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Pelajaran
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left" id="mapelTable">
                <thead class="bg-gray-50/50 text-gray-500 font-semibold uppercase text-xs tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4">Kategori MataPelajaran</th>
                        <th class="px-6 py-4">Mata Pelajaran</th>
                        <th class="px-6 py-4 text-center w-24">ID</th>
                        <th class="px-6 py-4 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($mapel)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3 text-gray-400">
                                        <i class="fas fa-book-open text-2xl"></i>
                                    </div>
                                    <p class="font-medium">Belum ada data mata pelajaran.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mapel as $key => $value): 
                            // Style categories with different colors
                            $cat = strtolower($value['nama_kategori']);
                            $badgeColor = 'bg-gray-100 text-gray-600'; // default
                            
                            if (strpos($cat, 'muatan nasional') !== false) $badgeColor = 'bg-red-50 text-red-600 border-red-100';
                            elseif (strpos($cat, 'muatan kewilayahan') !== false) $badgeColor = 'bg-blue-50 text-blue-600 border-blue-100';
                            elseif (strpos($cat, 'peminatan') !== false) $badgeColor = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                        ?>
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 text-center text-gray-400 font-mono text-xs"><?= $key + 1 ?></td>
                            <td class="px-6 py-4">
                                <span class="<?= $badgeColor ?> text-xs font-bold px-3 py-1 rounded-full border">
                                    <?= $value['nama_kategori'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-800 text-md group-hover:text-primary-600 transition-colors">
                                    <?= $value['nama_mapel'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-mono text-xs text-gray-400">#<?= $value['id_mapel'] ?></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="index.php?halaman=mapel_hapus&id=<?= $value['id_mapel'] ?>" class="text-red-500 hover:text-red-700 transition" onclick="return confirm('Yakin hapus mata pelajaran ini? Semua data terkait mungkin ikut terhapus.')" title="Hapus Mapel">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            <p class="text-xs text-center text-gray-500">
                Menampilkan <strong><?= count($mapel) ?></strong> mata pelajaran terdaftar.
            </p>
        </div>
    </div>
</div>

<script>
    // Simple Search Functionality
    document.getElementById('searchMapel').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelector("#mapelTable tbody").rows;
        
        for (let i = 0; i < rows.length; i++) {
            let cells = rows[i].cells;
            if (cells.length < 2) continue; 

            let category = cells[1].textContent || cells[1].innerText;
            let name = cells[2].textContent || cells[2].innerText;

            if (category.toUpperCase().indexOf(filter) > -1 || name.toUpperCase().indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                 rows[i].style.display = "none";
            }
        }
    });
</script>