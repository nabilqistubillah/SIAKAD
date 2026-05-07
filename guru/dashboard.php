<?php
$id_guru = $_SESSION['guru']['id_guru'];

// Get total classes taught by this teacher (as Wali Kelas)
$sql_kelas = $koneksi->query("
    SELECT COUNT(id_kelas) as total 
    FROM kelas 
    WHERE id_guru = '$id_guru'
");
$jml_kelas = $sql_kelas->fetch_assoc()['total'] ?? 0;

// Get total students in those classes
$sql_siswa = $koneksi->query("
    SELECT COUNT(sk.id_siswa) as total 
    FROM kelas k
    JOIN siswakelas sk ON k.id_kelas = sk.id_kelas
    WHERE k.id_guru = '$id_guru'
");
$jml_siswa = $sql_siswa->fetch_assoc()['total'] ?? 0;
?>

<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 fade-in">
    <div>
        <h1 class="text-3xl font-heading font-bold text-gray-800 tracking-tight">Dashboard Guru</h1>
        <p class="text-gray-500 mt-1">Selamat datang kembali, <?= htmlspecialchars($nama_guru) ?>!</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8 fade-in" style="animation-delay: 0.1s;">
    <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 hover:shadow-lg transition-all hover:-translate-y-1 group relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i class="fas fa-door-open text-6xl transform rotate-12"></i>
        </div>
        
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                <i class="fas fa-door-open text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kelas Binaan</p>
                <h3 class="text-2xl font-bold text-gray-800 font-heading"><?= $jml_kelas ?></h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 hover:shadow-lg transition-all hover:-translate-y-1 group relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
            <i class="fas fa-users text-6xl transform rotate-12"></i>
        </div>
        
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                <i class="fas fa-users text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Siswa Binaan</p>
                <h3 class="text-2xl font-bold text-gray-800 font-heading"><?= $jml_siswa ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Kelas Anda -->
<div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden flex flex-col fade-in" style="animation-delay: 0.2s;">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div>
            <h5 class="font-bold text-gray-800 font-heading text-lg">Kelas Binaan (Wali Kelas)</h5>
            <p class="text-xs text-gray-500">Daftar kelas di mana Anda menjadi Wali Kelas</p>
        </div>
        <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center">
            <i class="fas fa-school"></i>
        </div>
    </div>
    <div class="overflow-x-auto flex-1">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50/50 text-gray-400 uppercase font-semibold text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-4 font-medium">Kelas</th>
                    <th class="px-6 py-4 font-medium">Jurusan</th>
                    <th class="px-6 py-4 font-medium">Tahun Ajaran</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php
                $q = $koneksi->query("
                    SELECT k.nama_kelas, j.nama_jurusan, t.tahun_ajaran
                    FROM kelas k
                    JOIN jurusan j ON k.id_jurusan = j.id_jurusan
                    JOIN tahun t ON k.id_tahun = t.id_tahun
                    WHERE k.id_guru = '$id_guru'
                    ORDER BY t.tahun_ajaran DESC, k.nama_kelas ASC
                ");
                if ($q && $q->num_rows > 0):
                    while ($m = $q->fetch_assoc()):
                ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-700"><?= $m['nama_kelas'] ?></td>
                        <td class="px-6 py-4 text-gray-500"><?= $m['nama_jurusan'] ?></td>
                        <td class="px-6 py-4">
                            <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full border border-gray-200">
                                <?= $m['tahun_ajaran'] ?>
                            </span>
                        </td>
                    </tr>
                <?php 
                    endwhile;
                else: 
                ?>
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">Belum ada kelas yang ditugaskan kepada Anda sebagai Wali Kelas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($q && $q->num_rows > 0): ?>
    <div class="px-6 py-3 bg-gray-50/30 border-t border-gray-100 text-center">
        <a href="index.php?halaman=kelas" class="text-sm text-primary-600 font-medium hover:text-primary-700">Lihat Detail Kelas &rarr;</a>
    </div>
    <?php endif; ?>
</div>

<div class="mt-12 mb-4 text-center">
    <p class="text-gray-400 text-xs font-medium">
        Sistem Informasi Akademik (SIAKAD) &copy; 2025 SMK Al-Miftah Putri
    </p>
</div>
