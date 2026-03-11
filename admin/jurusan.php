<?php
$jurusan = array();
// Fetch tahun data with statistics
$ambil = $koneksi->query("
    SELECT j.*, 
    (SELECT COUNT(*) FROM kelas WHERE id_jurusan = j.id_jurusan) as jum_kelas,
    (SELECT COUNT(*) FROM siswa WHERE id_jurusan = j.id_jurusan) as jum_siswa
    FROM jurusan j
    ORDER BY j.nama_jurusan ASC
");

if (!$ambil) {
    echo "<div class='p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50' role='alert'>
            <span class='font-medium'>Query Error!</span> " . $koneksi->error . "
          </div>";
} else {
    while($tiap = $ambil->fetch_assoc()){
        $jurusan[] = $tiap;
    }
}
?>

<div class="space-y-6 fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold font-heading text-gray-800">Data Jurusan</h2>
            <p class="text-gray-500 text-sm mt-1">Daftar program keahlian yang tersedia.</p>
        </div>
        
        <!-- Placeholder for potential future 'Add' button -->
        <div class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-lg text-sm font-medium border border-indigo-100 flex items-center">
            <i class="fas fa-layer-group mr-2"></i>
            <?= count($jurusan) ?> Program Keahlian
        </div>
    </div>

    <!-- Data Grid -->
    <?php if (empty($jurusan)): ?>
        <div class="bg-white border border-gray-100 rounded-2xl shadow-soft p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <i class="fas fa-shapes text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-800">Belum ada data jurusan</h3>
            <p class="text-gray-500 text-sm mt-1">Silakan tambahkan data jurusan baru.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($jurusan as $key => $value): 
                // Define colors based on index for visual variety
                $idx = $key % 4;
                $colors = ['blue', 'emerald', 'amber', 'rose'];
                $clr = $colors[$idx];
                $bgClass = "bg-$clr-50 text-$clr-600 border-$clr-100";
                $gradClass = "from-$clr-500 to-$clr-600";
            ?>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-soft hover:shadow-lg transition-all transform hover:-translate-y-1 group relative overflow-hidden">
                <!-- Decoration -->
                <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-<?= $clr ?>-50 opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                
                <div class="flex items-start justify-between relative z-10 mb-4">
                    <div class="size-12 rounded-xl bg-gradient-to-br <?= $gradClass ?> text-white flex items-center justify-center text-xl shadow-lg shadow-<?= $clr ?>-200">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
                
                <div class="relative z-10">
                    <h3 class="text-xl font-bold text-gray-800 font-heading mb-1 pr-4" title="<?= $value['nama_jurusan'] ?>">
                        <?= $value['nama_jurusan'] ?>
                    </h3>
                    <p class="text-xs text-gray-400 font-mono mb-6">ID: <?= $value['id_jurusan'] ?></p>
                    
                    <div class="grid grid-cols-2 gap-3">
                         <div class="bg-gray-50 rounded-lg p-3 text-center border border-gray-100 group-hover:border-<?= $clr ?>-100 transition-colors">
                            <i class="fas fa-users text-gray-400 text-xs mb-1 block"></i>
                            <span class="font-bold text-gray-800 text-lg block leading-none"><?= $value['jum_siswa'] ?? 0 ?></span>
                            <span class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Siswa</span>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center border border-gray-100 group-hover:border-<?= $clr ?>-100 transition-colors">
                            <i class="fas fa-door-open text-gray-400 text-xs mb-1 block"></i>
                            <span class="font-bold text-gray-800 text-lg block leading-none"><?= $value['jum_kelas'] ?? 0 ?></span>
                            <span class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">Kelas</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
