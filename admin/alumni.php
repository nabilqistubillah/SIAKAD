<?php
// tahun ajaran
$tahun = $koneksi->query("SELECT * FROM tahun ORDER BY id_tahun DESC");

// filter
$where = "WHERE s.status='LULUS' OR s.status='ALUMNI'";
$filterTahun = "";
if (isset($_POST['filter'])) {
    $id_tahun = $_POST['id_tahun'];
    if (!empty($id_tahun)) {
        $where .= " AND s.id_tahun='$id_tahun'";
        $filterTahun = $id_tahun;
    }
}

// data alumni
$alumni = $koneksi->query("SELECT s.*, t.tahun_ajaran
    FROM siswa s
    LEFT JOIN tahun t ON s.id_tahun = t.id_tahun
    $where
    ORDER BY s.nama_siswa ASC
");
?>

<div class="space-y-6 fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold font-heading text-gray-800">Data Alumni</h2>
            <p class="text-gray-500 text-sm mt-1">Daftar siswa yang telah lulus.</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white p-5 rounded-2xl shadow-soft border border-gray-100">
        <form method="post" class="flex flex-col sm:flex-row gap-4 items-end">
             <div class="w-full sm:w-64">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Tahun Masuk</label>
                <div class="relative">
                    <select name="id_tahun" class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none transition-all">
                        <option value="">Semua Tahun Masuk</option>
                        <?php while ($t = $tahun->fetch_assoc()): ?>
                            <option value="<?= $t['id_tahun'] ?>" <?= $filterTahun == $t['id_tahun'] ? 'selected' : '' ?>>
                                <?= $t['tahun_ajaran'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-3.5 text-gray-400 text-xs pointer-events-none"></i>
                </div>
            </div>
            <button type="submit" name="filter" class="w-full sm:w-auto px-6 py-2.5 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors shadow-lg shadow-primary-500/30 flex items-center justify-center gap-2">
                <i class="fas fa-filter text-sm"></i> Terapkan Filter
            </button>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/50 text-gray-500 font-semibold uppercase text-xs tracking-wider border-b border-gray-100">
                    <tr>
                         <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4">Tahun Masuk</th>
                        <th class="px-6 py-4">Informasi Alumni</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                     <?php if ($alumni->num_rows == 0): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                        <i class="fas fa-user-graduate text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-800">Belum ada data alumni</h3>
                                    <p class="text-gray-500 text-sm mt-1">Coba ubah filter tahun atau pastikan ada siswa yang lulus.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; while ($a = $alumni->fetch_assoc()): 
                             $initial = strtoupper(substr($a['nama_siswa'], 0, 1));
                        ?>
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 text-center text-gray-400 font-mono text-xs"><?= $no++ ?></td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2.5 py-1 rounded border border-gray-200">
                                    <i class="far fa-calendar-alt mr-1"></i> <?= $a['tahun_ajaran'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                 <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-sm shadow-sm">
                                        <?= $initial ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800 group-hover:text-primary-600 transition-colors"><?= $a['nama_siswa'] ?></div>
                                        <div class="text-xs text-gray-400 font-mono mt-0.5"><?= $a['induk_siswa'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-emerald-50 text-emerald-600 text-xs font-bold px-3 py-1 rounded-full border border-emerald-100">
                                    LULUS
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="index.php?halaman=siswa_detail&id=<?= $a['id_siswa'] ?>" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
