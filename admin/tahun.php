<?php
$tahun = array();
// Fetch tahun data with statistics
$ambil = $koneksi->query("
    SELECT t.*, 
    (SELECT COUNT(*) FROM siswa WHERE id_tahun = t.id_tahun) as jum_siswa,
    (SELECT COUNT(*) FROM kelas WHERE id_tahun = t.id_tahun) as jum_kelas
    FROM tahun t
    ORDER BY t.id_tahun DESC
");

if (!$ambil) {
    echo "<div class='p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50' role='alert'>
            <span class='font-medium'>Query Error!</span> " . $koneksi->error . "
          </div>";
} else {
    while($tiap = $ambil->fetch_assoc()){
        $tahun[] = $tiap;
    }
}
?>

<div class="space-y-6 fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold font-heading text-gray-800">Tahun Ajaran</h2>
            <p class="text-gray-500 text-sm mt-1">Daftar tahun akademik yang terdaftar di sistem.</p>
        </div>
        <!-- Placeholder for potential future 'Add' button -->
        <div class="bg-blue-50 text-blue-600 px-4 py-2 rounded-lg text-sm font-medium border border-blue-100 flex items-center">
            <i class="fas fa-info-circle mr-2"></i>
            Data referensi sistem
        </div>
    </div>

    <!-- Data Grid -->
    <?php if (empty($tahun)): ?>
        <div class="bg-white border border-gray-100 rounded-2xl shadow-soft p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <i class="fas fa-calendar-times text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-800">Belum ada data tahun ajaran</h3>
            <p class="text-gray-500 text-sm mt-1">Data tahun ajaran diperlukan untuk operasional sistem.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($tahun as $key => $value): 
                $isActive = $key === 0; // Assuming the first one (latest) is active/current for visual flair
                $cardClass = $isActive ? "ring-2 ring-primary-500 shadow-md transform -translate-y-1" : "hover:shadow-md hover:-translate-y-1";
            ?>
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-soft transition-all duration-300 relative overflow-hidden group <?= $cardClass ?>">
                <?php if ($isActive): ?>
                    <div class="absolute top-0 right-0 bg-primary-500 text-white text-[10px] uppercase font-bold px-3 py-1 rounded-bl-xl shadow-sm">
                        Terbaru
                    </div>
                <?php endif; ?>
                
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <span class="text-xs font-mono text-gray-400">#<?= $key + 1 ?></span>
                </div>
                
                <h3 class="text-xl font-bold text-gray-800 font-heading mb-1">
                    <?= $value['tahun_ajaran'] ?>
                </h3>
                <p class="text-xs text-gray-500 mb-6">ID: <?= $value['id_tahun'] ?></p>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm p-2 bg-gray-50 rounded-lg">
                        <span class="text-gray-600 flex items-center"><i class="fas fa-users w-5 text-gray-400"></i> Siswa</span>
                        <span class="font-bold text-gray-800"><?= $value['jum_siswa'] ?></span>
                    </div>
                    <div class="flex justify-between items-center text-sm p-2 bg-gray-50 rounded-lg">
                        <span class="text-gray-600 flex items-center"><i class="fas fa-school w-5 text-gray-400"></i> Kelas</span>
                        <span class="font-bold text-gray-800"><?= $value['jum_kelas'] ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>