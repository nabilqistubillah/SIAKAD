<?php
$id_siswa = $_SESSION['siswa']['id_siswa'];

$nilai = $koneksi->query("
    SELECT 
        m.nama_mapel as mapel, 
        t.tahun_ajaran, 
        mg.semester, 
        n.h1, n.h2, n.h3, n.h4, n.rph, n.pts, n.pas
    FROM nilai n
    JOIN siswakelas sk ON n.id_siswakelas = sk.id_siswakelas
    JOIN kelas k ON sk.id_kelas = k.id_kelas
    JOIN tahun t ON k.id_tahun = t.id_tahun
    JOIN mengajar mg ON n.id_mengajar = mg.id_ajar
    JOIN mapel m ON mg.id_mapel = m.id_mapel
    WHERE sk.id_siswa = '$id_siswa'
    ORDER BY t.tahun_ajaran DESC, mg.semester DESC
");

// prestasi
$prestasi = $koneksi->query("
  SELECT * FROM prestasi
  WHERE id_siswa = '$id_siswa'
  ORDER BY tanggal DESC
");
?>

<div class="space-y-8">
    <!-- ================= NILAI ================= -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-book-open text-primary mr-3 text-lg"></i>
                <h5 class="font-bold text-gray-700 text-lg">Catatan Nilai Akademik</h5>
            </div>
            <span class="text-primary text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Akademik</span>
        </div>
        
        <div class="p-0 overflow-x-auto">
            <?php if ($nilai && $nilai->num_rows > 0): ?>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                            <th class="py-4 px-4 text-center w-12">No</th>
                            <th class="py-4 px-4">Mata Pelajaran</th>
                            <th class="py-4 px-2 text-center">SMT</th>
                            <th class="py-4 px-2 text-center">H1</th>
                            <th class="py-4 px-2 text-center">H2</th>
                            <th class="py-4 px-2 text-center">H3</th>
                            <th class="py-4 px-2 text-center">H4</th>
                            <th class="py-4 px-2 text-center">RPH</th>
                            <th class="py-4 px-2 text-center">PTS</th>
                            <th class="py-4 px-2 text-center">PAS</th>
                            <th class="py-4 px-4 text-center">Nilai Akhir</th>
                            <th class="py-4 px-4 text-center">Predikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $no = 1; while ($n = $nilai->fetch_assoc()): 
                            $nilai_akhir = round(($n['rph'] + $n['pts'] + $n['pas']) / 3);
                            $predikat = 'E';
                            if ($nilai_akhir >= 90) $predikat = 'A';
                            elseif ($nilai_akhir >= 80) $predikat = 'B';
                            elseif ($nilai_akhir >= 70) $predikat = 'C';
                            elseif ($nilai_akhir >= 60) $predikat = 'D';
                        ?>
                            <tr class="hover:bg-blue-50/50 transition-colors duration-150 group">
                                <td class="py-4 px-4 text-center text-gray-500 text-sm font-medium"><?= $no++; ?></td>
                                <td class="py-4 px-4 font-semibold text-gray-800">
                                    <?= $n['mapel']; ?>
                                    <div class="text-xs text-gray-400 font-normal leading-tight mt-0.5">Tahun: <?= $n['tahun_ajaran']; ?></div>
                                </td>
                                <td class="py-4 px-2 text-center text-sm text-gray-600 font-medium"><?= $n['semester']; ?></td>
                                <td class="py-4 px-2 text-center text-sm text-gray-600"><?= $n['h1']; ?></td>
                                <td class="py-4 px-2 text-center text-sm text-gray-600"><?= $n['h2']; ?></td>
                                <td class="py-4 px-2 text-center text-sm text-gray-600"><?= $n['h3']; ?></td>
                                <td class="py-4 px-2 text-center text-sm text-gray-600"><?= $n['h4']; ?></td>
                                <td class="py-4 px-2 text-center text-sm font-semibold text-gray-700 bg-gray-50/50"><?= $n['rph']; ?></td>
                                <td class="py-4 px-2 text-center text-sm text-gray-600"><?= $n['pts']; ?></td>
                                <td class="py-4 px-2 text-center text-sm text-gray-600"><?= $n['pas']; ?></td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full font-bold text-gray-800 bg-gray-100 group-hover:bg-white group-hover:shadow-sm border border-transparent group-hover:border-gray-200 transition-all">
                                        <?= $nilai_akhir; ?>
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <?php 
                                        $badgeClass = 'bg-gray-100 text-gray-700 border-gray-200';
                                        if ($predikat == 'A') $badgeClass = 'bg-green-100 text-green-700 border-green-200';
                                        elseif ($predikat == 'B') $badgeClass = 'bg-blue-100 text-blue-700 border-blue-200';
                                        elseif ($predikat == 'C') $badgeClass = 'bg-yellow-100 text-yellow-700 border-yellow-200';
                                        elseif ($predikat == 'D' || $predikat == 'E') $badgeClass = 'bg-red-100 text-red-700 border-red-200';
                                    ?>
                                    <span class="inline-block px-3 py-1 rounded-md text-xs font-bold border <?= $badgeClass; ?>">
                                        <?= $predikat; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="p-16 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100">
                        <i class="fas fa-folder-open text-3xl text-gray-300"></i>
                    </div>
                    <h6 class="text-lg font-bold text-gray-700 mb-1">Belum Ada Nilai</h6>
                    <p class="text-gray-500 text-sm max-w-sm">Data nilai akademik untuk siswa ini belum dimasukkan ke dalam sistem oleh guru atau admin.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ================= PRESTASI ================= -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-trophy text-yellow-500 mr-3 text-lg"></i>
                <h5 class="font-bold text-gray-700 text-lg">Rekam Jejak Prestasi</h5>
            </div>
            <span class="text-primary text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Non-Akademik / Akademik</span>
        </div>
        
        <div class="p-0 overflow-x-auto">
            <?php if ($prestasi && $prestasi->num_rows > 0): ?>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                            <th class="py-4 px-6 text-center w-16">No</th>
                            <th class="py-4 px-6">Prestasi</th>
                            <th class="py-4 px-6 text-center">Tingkat</th>
                            <th class="py-4 px-6 text-center w-32">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $no = 1; while ($p = $prestasi->fetch_assoc()): ?>
                            <tr class="hover:bg-yellow-50/30 transition-colors duration-150">
                                <td class="py-4 px-6 text-center text-gray-500 text-sm font-medium"><?= $no++; ?></td>
                                <td class="py-4 px-6 font-semibold text-gray-800">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="fas fa-star text-xs"></i>
                                        </div>
                                        <?= $p['jenis_prestasi']; ?>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-block border border-gray-200 bg-gray-50 text-gray-600 px-3 py-1 rounded-md text-xs font-bold uppercase tracking-wide">
                                        <?= $p['tingkat']; ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center font-bold text-gray-700"><?= date('d M Y', strtotime($p['tanggal'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="p-16 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100">
                        <i class="fas fa-medal text-3xl text-gray-300"></i>
                    </div>
                    <h6 class="text-lg font-bold text-gray-700 mb-1">Belum Ada Prestasi</h6>
                    <p class="text-gray-500 text-sm max-w-sm">Belum ada rekam jejak prestasi siswa ini yang tercatat ke dalam sistem.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
