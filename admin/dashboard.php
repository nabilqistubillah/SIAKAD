<!--ini juga harus di styling yaa niaa-->
<?php
function getCount($koneksi, $sql)
{
    $q = $koneksi->query($sql);
    if (!$q) {
        die("SQL Error: " . $koneksi->error);
    }
    $r = $q->fetch_assoc();
    return $r['j'] ?? 0;
}

function getSingle($koneksi, $sql)
{
    $q = $koneksi->query($sql);
    if (!$q) {
        die("SQL Error: " . $koneksi->error);
    }
    return $q->fetch_assoc();
}

// DATA RINGKAS
$jml_siswa   = getCount($koneksi, "SELECT COUNT(*) j FROM siswa WHERE status='AKTIF'");
$jml_alumni  = getCount($koneksi, "SELECT COUNT(*) j FROM siswa WHERE status='LULUS'");
$jml_kelas   = getCount($koneksi, "SELECT COUNT(*) j FROM kelas");
$jml_jurusan = getCount($koneksi, "SELECT COUNT(*) j FROM jurusan");
$jml_guru    = getCount($koneksi, "SELECT COUNT(*) j FROM guru");

$tahun = getSingle($koneksi, "
    SELECT tahun_ajaran
    FROM tahun
    ORDER BY id_tahun DESC
    LIMIT 1
");
?>

<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 fade-in">
    <div>
        <h1 class="text-3xl font-heading font-bold text-gray-800 tracking-tight">Dashboard Overview</h1>
        <p class="text-gray-500 mt-1">Selamat datang kembali di panel administrasi.</p>
    </div>
    <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 flex items-center gap-3">
        <div class="p-2 bg-primary-50 text-primary-600 rounded-lg">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Tahun Ajaran</p>
            <p class="font-bold text-gray-800"><?= $tahun['tahun_ajaran'] ?? '-' ?></p>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 fade-in" style="animation-delay: 0.1s;">
    <?php
    $cards = [
        ['value' => $jml_siswa,   'label' => 'Siswa Aktif', 'icon' => 'fa-users', 'color' => 'blue', 'gradient' => 'from-blue-500 to-blue-600'],
        ['value' => $jml_alumni,  'label' => 'Alumni', 'icon' => 'fa-user-graduate', 'color' => 'emerald', 'gradient' => 'from-emerald-500 to-emerald-600'],
        ['value' => $jml_kelas,   'label' => 'Kelas', 'icon' => 'fa-school', 'color' => 'violet', 'gradient' => 'from-violet-500 to-violet-600'],
        ['value' => $jml_guru,    'label' => 'Guru', 'icon' => 'fa-chalkboard-teacher', 'color' => 'rose', 'gradient' => 'from-rose-500 to-rose-600'],
    ];

    foreach ($cards as $c):
    ?>
        <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 hover:shadow-lg transition-all hover:-translate-y-1 group relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="fas <?= $c['icon'] ?> text-6xl transform rotate-12"></i>
            </div>
            
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br <?= $c['gradient'] ?> flex items-center justify-center text-white shadow-lg shadow-<?= $c['color'] ?>-500/30">
                    <i class="fas <?= $c['icon'] ?> text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider"><?= $c['label'] ?></p>
                    <h3 class="text-2xl font-bold text-gray-800 font-heading"><?= $c['value'] ?></h3>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 fade-in" style="animation-delay: 0.2s;">
    
    <!-- Statistik Siswa per Kelas -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h5 class="font-bold text-gray-800 font-heading text-lg">Statistik Siswa</h5>
                <p class="text-xs text-gray-500">Jumlah siswa per kelas</p>
            </div>
            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                <i class="fas fa-chart-pie"></i>
            </div>
        </div>
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/50 text-gray-400 uppercase font-semibold text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">Kelas</th>
                        <th class="px-6 py-4 font-medium">Jurusan</th>
                        <th class="px-6 py-4 font-medium text-center">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php
                    $q = $koneksi->query("
                        SELECT k.nama_kelas, j.nama_jurusan, COUNT(sk.id_siswa) jumlah
                        FROM kelas k
                        JOIN jurusan j ON k.id_jurusan=j.id_jurusan
                        LEFT JOIN siswakelas sk ON k.id_kelas=sk.id_kelas
                        GROUP BY k.id_kelas
                        LIMIT 5
                    ");
                    if (!$q) die($koneksi->error);

                    while ($k = $q->fetch_assoc()):
                    ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-700"><?= $k['nama_kelas'] ?></td>
                            <td class="px-6 py-4 text-gray-500"><?= $k['nama_jurusan'] ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-50 text-blue-600 text-xs font-bold px-3 py-1 rounded-full border border-blue-100">
                                    <?= $k['jumlah'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3 bg-gray-50/30 border-t border-gray-100 text-center">
            <a href="index.php?halaman=kelas" class="text-sm text-primary-600 font-medium hover:text-primary-700">Lihat Semua Kelas &rarr;</a>
        </div>
    </div>

    <!-- Siswa Terbaru -->
    <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
             <div>
                <h5 class="font-bold text-gray-800 font-heading text-lg">Siswa Terbaru</h5>
                <p class="text-xs text-gray-500">Pendaftaran terakhir</p>
            </div>
            <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center">
                <i class="fas fa-user-clock"></i>
            </div>
        </div>
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/50 text-gray-400 uppercase font-semibold text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">Info Siswa</th>
                        <th class="px-6 py-4 font-medium text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php
                    $siswa = $koneksi->query("
                        SELECT s.induk_siswa, s.nama_siswa, t.tahun_ajaran, s.status
                        FROM siswa s
                        LEFT JOIN tahun t ON s.id_tahun=t.id_tahun
                        WHERE s.status='AKTIF'
                        ORDER BY s.id_siswa DESC
                        LIMIT 5
                    ");
                    while ($s = $siswa->fetch_assoc()):
                        $statusClass = $s['status'] == 'LULUS' 
                            ? 'bg-green-50 text-green-600 border-green-100' 
                            : 'bg-blue-50 text-blue-600 border-blue-100';
                            
                        $initial = strtoupper(substr($s['nama_siswa'], 0, 1));
                    ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500 uppercase">
                                    <?= $initial ?>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm"><?= $s['nama_siswa'] ?></p>
                                    <p class="text-xs text-gray-400 font-mono"><?= $s['induk_siswa'] ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="<?= $statusClass ?> text-xs font-bold px-2.5 py-0.5 rounded-full border">
                                <?= $s['status'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
         <div class="px-6 py-3 bg-gray-50/30 border-t border-gray-100 text-center">
            <a href="index.php?halaman=siswa" class="text-sm text-primary-600 font-medium hover:text-primary-700">Lihat Semua Siswa &rarr;</a>
        </div>
    </div>
</div>

<div class="mt-12 mb-4 text-center">
    <p class="text-gray-400 text-xs font-medium">
        Sistem Informasi Akademik (SIAKAD) &copy; 2025 SMK Al-Miftah Putri
    </p>
</div>