<?php
$guru = array();
$ambil = $koneksi->query("SELECT * FROM guru ORDER BY nama_guru ASC");
while ($tiap = $ambil->fetch_assoc()) {
    $guru[] = $tiap;
}
?>

<div class="space-y-6 fade-in">
    <!-- Header & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold font-heading text-gray-800">Data Guru</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola data tenaga pengajar dan staf.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
             <div class="relative">
                <input type="text" id="searchGuru" placeholder="Cari guru..." 
                    class="pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-full sm:w-64 transition-all shadow-sm">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
            </div>
            <a href="index.php?halaman=guru_tambah" class="btn btn-primary shadow-lg shadow-primary-500/30">
                <i class="fas fa-plus mr-2"></i> Tambah Guru
            </a>
        </div>
    </div>

    <!-- Grid Layout -->
    <?php if (empty($guru)): ?>
        <div class="bg-white border border-gray-100 rounded-2xl shadow-soft p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <i class="fas fa-chalkboard-teacher text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-800">Belum ada data guru</h3>
            <p class="text-gray-500 text-sm mt-1">Silakan tambahkan data guru baru untuk memulai.</p>
            <a href="index.php?halaman=guru_tambah" class="mt-4 inline-block btn btn-primary text-sm">
                Tambah Guru Sekarang
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="guruGrid">
            <?php foreach ($guru as $key => $value): 
                $foto = $value['foto_guru'];
                $hasFoto = !empty($foto) && file_exists("../assets/guru/$foto");
                $imageSrc = $hasFoto ? "../assets/guru/$foto" : "https://ui-avatars.com/api/?name=".urlencode($value['nama_guru'])."&background=random&size=256";
            ?>
                <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden group hover:shadow-lg transition-all hover:-translate-y-1 guru-card">
                    <!-- Image Area -->
                    <div class="relative h-48 bg-gray-100 overflow-hidden">
                        <img src="<?= $imageSrc ?>" 
                             alt="<?= $value['nama_guru'] ?>" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <!-- Actions Overlay -->
                        <div class="absolute bottom-4 right-4 flex gap-2 translate-y-10 group-hover:translate-y-0 transition-transform duration-300">
                            <a href="index.php?halaman=guru_edit&id=<?= $value['id_guru'] ?>" 
                               class="w-8 h-8 rounded-lg bg-white/90 text-amber-500 flex items-center justify-center hover:bg-amber-500 hover:text-white transition-colors shadow-sm backdrop-blur-sm" 
                               title="Edit">
                                <i class="fas fa-pencil-alt text-xs"></i>
                            </a>
                            <a href="index.php?halaman=guru_hapus&id=<?= $value['id_guru'] ?>" 
                               class="w-8 h-8 rounded-lg bg-white/90 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors shadow-sm backdrop-blur-sm" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus data guru ini?')"
                               title="Hapus">
                                <i class="fas fa-trash text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="p-5">
                        <h3 class="font-bold text-gray-800 font-heading text-lg truncate" title="<?= $value['nama_guru'] ?>">
                            <?= $value['nama_guru'] ?>
                        </h3>
                        <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">
                            <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded text-xs font-semibold border border-blue-100">NIP</span>
                            <span class="font-mono"><?= $value['induk_guru'] ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    // Search Functionality
    document.getElementById('searchGuru').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let cards = document.querySelectorAll('.guru-card');
        
        cards.forEach(card => {
            let name = card.querySelector('h3').textContent || card.querySelector('h3').innerText;
            let nip = card.querySelector('.font-mono').textContent || card.querySelector('.font-mono').innerText;
            
            if (name.toUpperCase().indexOf(filter) > -1 || nip.toUpperCase().indexOf(filter) > -1) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    });
</script>