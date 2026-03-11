<?php
$mengajar = array();
// Fix SQL join structure and formatting
$ambil = $koneksi->query("
    SELECT mengajar.*, guru.nama_guru, guru.induk_guru, guru.foto_guru,
           mapel.nama_mapel, kategori.nama_kategori,
           kelas.nama_kelas, jurusan.nama_jurusan, tahun.tahun_ajaran
    FROM mengajar 
    LEFT JOIN guru ON mengajar.id_guru = guru.id_guru
    LEFT JOIN mapel ON mengajar.id_mapel = mapel.id_mapel
    LEFT JOIN kategori ON mapel.id_kategori = kategori.id_kategori
    LEFT JOIN kelas ON mengajar.id_kelas = kelas.id_kelas
    LEFT JOIN jurusan ON kelas.id_jurusan = jurusan.id_jurusan
    LEFT JOIN tahun ON kelas.id_tahun = tahun.id_tahun
    ORDER BY tahun.id_tahun DESC, kelas.nama_kelas ASC, mapel.nama_mapel ASC
");

if (!$ambil) {
    echo "<div class='p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50' role='alert'>
            <span class='font-medium'>Query Error!</span> " . $koneksi->error . "
          </div>";
} else {
    while($tiap = $ambil->fetch_assoc()){
        $mengajar[] = $tiap;
    }
}
?>

<div class="space-y-6 fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold font-heading text-gray-800">Jadwal Mengajar</h2>
            <p class="text-gray-500 text-sm mt-1">Daftar penugasan guru, kelas, dan mata pelajaran.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
             <div class="relative">
                <input type="text" id="searchInput" placeholder="Cari guru, kelas, mapel..." 
                    class="pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent w-full sm:w-64 transition-all shadow-sm">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
            </div>
             <!-- Optional: Add button here later -->
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left" id="mengajarTable">
                <thead class="bg-gray-50/50 text-gray-500 font-semibold uppercase text-xs tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4">Guru Pengajar</th>
                        <th class="px-6 py-4">Mata Pelajaran</th>
                        <th class="px-6 py-4">Kelas & Tahun</th>
                        <th class="px-6 py-4 text-center">Smt & KKM</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($mengajar)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3 text-gray-400">
                                        <i class="fas fa-chalkboard-teacher text-2xl"></i>
                                    </div>
                                    <p class="font-medium">Belum ada data jadwal mengajar.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mengajar as $key => $value): 
                            $initial = strtoupper(substr($value['nama_guru'], 0, 1));
                            $idx = $key % 5;
                            $hasPhoto = !empty($value['foto_guru']) && file_exists("../assets/guru/".$value['foto_guru']);
                        ?>
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4 text-center text-gray-400 font-mono text-xs"><?= $key + 1 ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <?php if($hasPhoto): ?>
                                         <img src="../assets/guru/<?= $value['foto_guru'] ?>" alt="Foto" class="w-10 h-10 rounded-full object-cover shadow-sm bg-gray-100">
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm shadow-sm">
                                            <?= $initial ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="font-bold text-gray-800 text-sm group-hover:text-primary-600 transition-colors"><?= $value['nama_guru'] ?></div>
                                        <div class="text-xs text-gray-400 font-mono mt-0.5">NIP: <?= $value['induk_guru'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-700 text-sm"><?= $value['nama_mapel'] ?></div>
                                <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2 py-0.5 rounded-full border border-gray-200 mt-1 inline-block uppercase tracking-wide">
                                    <?= substr($value['nama_kategori'], 0, 20) ?><?= strlen($value['nama_kategori'])>20 ? '...' : '' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-emerald-50 text-emerald-600 font-bold px-2 py-1 rounded text-xs border border-emerald-100 inline-block mb-1">
                                    <?= $value['nama_kelas'] ?>
                                </span>
                                <div class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                    <i class="far fa-calendar text-[10px]"></i> <?= $value['tahun_ajaran'] ?? '-' ?>
                                </div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-wide mt-0.5 font-medium">
                                    <?= $value['nama_jurusan'] ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="text-center p-1 min-w-[3rem]">
                                        <span class="block text-[10px] text-gray-400 font-bold uppercase">Smt</span>
                                        <span class="block text-sm font-bold text-gray-800 bg-gray-50 rounded px-1 border border-gray-100"><?= $value['semester'] ?></span>
                                    </div>
                                    <div class="text-center p-1 min-w-[3rem]">
                                        <span class="block text-[10px] text-gray-400 font-bold uppercase">KKM</span>
                                        <span class="block text-sm font-bold text-rose-600 bg-rose-50 rounded px-1 border border-rose-100"><?= $value['kkm'] ?></span>
                                    </div>
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
                Menampilkan <strong><?= count($mengajar) ?></strong> data jadwal mengajar.
            </p>
        </div>
    </div>
</div>

<script>
    // Search Functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelector("#mengajarTable tbody").rows;
        
        for (let i = 0; i < rows.length; i++) {
            let cells = rows[i].cells;
            if (cells.length < 2) continue;

            let teacher = cells[1].innerText; // Includes name and nip
            let mapel = cells[2].innerText;   // Includes subject and category
            let kelas = cells[3].innerText;   // Includes class, year, major

            if (teacher.toUpperCase().indexOf(filter) > -1 || 
                mapel.toUpperCase().indexOf(filter) > -1 || 
                kelas.toUpperCase().indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                 rows[i].style.display = "none";
            }
        }
    });
</script>
