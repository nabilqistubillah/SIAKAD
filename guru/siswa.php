<?php
$id_kelas = $_GET['id_kelas'] ?? 0;
$id_guru = $_SESSION['guru']['id_guru'];

// Validasi: Pastikan guru ini memang Wali Kelas dari kelas tersebut
$cek_hak = $koneksi->query("SELECT * FROM kelas WHERE id_guru='$id_guru' AND id_kelas='$id_kelas'");
if ($cek_hak->num_rows == 0) {
    echo "<script>alert('Akses Ditolak! Anda bukan Wali Kelas untuk kelas ini.');location='index.php?halaman=kelas';</script>";
    exit();
}

$kelas = $koneksi->query("
    SELECT k.*, j.nama_jurusan 
    FROM kelas k 
    JOIN jurusan j ON k.id_jurusan=j.id_jurusan 
    WHERE id_kelas='$id_kelas'
")->fetch_assoc();

$siswa = [];
$ambil = $koneksi->query("
    SELECT s.* 
    FROM siswakelas sk 
    JOIN siswa s ON sk.id_siswa=s.id_siswa 
    WHERE sk.id_kelas='$id_kelas' 
    ORDER BY s.nama_siswa ASC
");
while ($t = $ambil->fetch_assoc()) {
    $siswa[] = $t;
}
?>

<div class="space-y-6 fade-in">
    <!-- Header & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="index.php?halaman=kelas" class="hover:text-primary-600 transition-colors">Daftar Kelas</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-800 font-semibold">Data Siswa</span>
            </div>
            <h2 class="text-2xl font-bold font-heading text-gray-800">Kelas <?= $kelas['nama_kelas'] ?></h2>
            <p class="text-gray-500 text-sm mt-1">Jurusan <?= $kelas['nama_jurusan'] ?> | Total: <?= count($siswa) ?> Siswa</p>
        </div>
        <div class="flex gap-3">
             <div class="relative">
                <input type="text" id="searchSiswa" placeholder="Cari siswa..." 
                    class="pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-full sm:w-64 transition-all shadow-sm">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
            </div>
        </div>
    </div>

    <!-- Data List -->
    <?php if (empty($siswa)): ?>
        <div class="bg-white border border-gray-100 rounded-2xl shadow-soft p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <i class="fas fa-user-graduate text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-800">Belum ada siswa</h3>
            <p class="text-gray-500 text-sm mt-1">Tidak ada data siswa yang terdaftar di kelas ini.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl shadow-soft border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50/50 text-gray-400 uppercase font-semibold text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Siswa</th>
                            <th class="px-6 py-4">NIS / NISN</th>
                            <th class="px-6 py-4">Tanggal Lahir</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="siswaTable">
                        <?php foreach ($siswa as $s): 
                            $statusClass = $s['status'] == 'LULUS' ? 'bg-green-50 text-green-600 border-green-100' : 'bg-blue-50 text-blue-600 border-blue-100';
                            $initial = strtoupper(substr($s['nama_siswa'], 0, 1));
                            $foto = $s['foto_siswa'];
                            $hasFoto = !empty($foto) && file_exists("../siswa-foto/$foto");
                        ?>
                            <tr class="hover:bg-gray-50/50 transition-colors siswa-row">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <?php if ($hasFoto): ?>
                                            <img src="../siswa-foto/<?= $foto ?>" alt="<?= $s['nama_siswa'] ?>" class="w-10 h-10 rounded-full object-cover shadow-sm">
                                        <?php else: ?>
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-primary-100 text-primary-600 flex items-center justify-center font-bold text-sm shadow-sm">
                                                <?= $initial ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <p class="font-bold text-gray-800 text-sm nama-siswa"><?= $s['nama_siswa'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600 font-mono nis-siswa">
                                    <?= $s['induk_siswa'] ?><br>
                                    
                                </td>
                                <td class="px-6 py-">
                                    <?= $s['tanggal_lahir'] ?><br>
                                    
                                </td>
                                <td class="px-6 py-4">
                                    <span class="<?= $statusClass ?> text-xs font-bold px-2.5 py-0.5 rounded-full border">
                                        <?= $s['status'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="index.php?halaman=siswa_detail&id=<?= $s['id_siswa'] ?>" 
                                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-colors text-xs font-semibold">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    document.getElementById('searchSiswa').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll('.siswa-row');
        
        rows.forEach(row => {
            let name = row.querySelector('.nama-siswa').textContent || row.querySelector('.nama-siswa').innerText;
            let nis = row.querySelector('.nis-siswa').textContent || row.querySelector('.nis-siswa').innerText;
            
            if (name.toUpperCase().indexOf(filter) > -1 || nis.toUpperCase().indexOf(filter) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
