<?php
$id_siswa = $_SESSION['siswa']['id_siswa'];

// ================= ABSENSI =================
$absensi = $koneksi->query("
  SELECT bulan, tahun, hadir, sakit, izin, alpa
  FROM absensi
  WHERE id_siswa = '$id_siswa'
  ORDER BY tahun DESC, FIELD(bulan,
    'Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','November','Desember'
  ) DESC
");

// ================= PELANGGARAN =================
$pelanggaran = $koneksi->query("
  SELECT * FROM pelanggaran
  WHERE id_siswa = '$id_siswa'
  ORDER BY tanggal DESC
");
?>

<div class="space-y-8">
    <!-- ================= ABSENSI ================= -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mr-3 shadow-inner">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h5 class="font-bold text-gray-700 text-lg">Riwayat Absensi Bulanan</h5>
            </div>
        </div>
        
        <div class="p-0 overflow-x-auto">
            <?php if ($absensi && $absensi->num_rows > 0): ?>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                            <th class="py-4 px-6 text-center w-16">No</th>
                            <th class="py-4 px-6">Bulan</th>
                            <th class="py-4 px-6 text-center">Tahun</th>
                            <th class="py-4 px-6 text-center">Hadir</th>
                            <th class="py-4 px-6 text-center">Sakit</th>
                            <th class="py-4 px-6 text-center">Izin</th>
                            <th class="py-4 px-6 text-center">Alpa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $no = 1; while ($a = $absensi->fetch_assoc()): ?>
                            <tr class="hover:bg-blue-50/30 transition-colors duration-150">
                                <td class="py-4 px-6 text-center text-gray-500 text-sm font-medium"><?= $no++; ?></td>
                                <td class="py-4 px-6 font-semibold text-gray-800"><?= $a['bulan']; ?></td>
                                <td class="py-4 px-6 text-center text-sm text-gray-600 font-medium"><?= $a['tahun']; ?></td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-block px-3 py-1 font-bold text-gray-800 min-w-[2.5rem]"><?= $a['hadir']; ?></span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-block px-3 py-1 font-bold text-gray-800 min-w-[2.5rem]"><?= $a['sakit']; ?></span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-block px-3 py-1 font-bold text-gray-800 min-w-[2.5rem]"><?= $a['izin']; ?></span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-block px-3 py-1 font-bold text-gray-800 min-w-[2.5rem]"><?= $a['alpa']; ?></span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="p-16 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-200">
                        <i class="fas fa-calendar-times text-3xl text-gray-400"></i>
                    </div>
                    <h6 class="text-lg font-bold text-gray-700 mb-1">Data Absensi Kosong</h6>
                    <p class="text-gray-500 text-sm max-w-sm">Belum ada riwayat absensi bulanan yang dimasukkan oleh guru untuk siswa ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ================= PELANGGARAN ================= -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center mr-3 shadow-inner">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h5 class="font-bold text-gray-700 text-lg">Catatan Pelanggaran & Tata Tertib</h5>
            </div>
        </div>
        
        <div class="p-0 overflow-x-auto">
            <?php if ($pelanggaran && $pelanggaran->num_rows > 0): ?>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                            <th class="py-4 px-6 text-center w-16">No</th>
                            <th class="py-4 px-6">Tanggal</th>
                            <th class="py-4 px-6">Jenis Pelanggaran</th>
                            <th class="py-4 px-6 text-center">Poin</th>
                            <th class="py-4 px-6">Keterangan Tambahan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $no = 1; while ($p = $pelanggaran->fetch_assoc()): ?>
                            <tr class="hover:bg-red-50/30 transition-colors duration-150">
                                <td class="py-4 px-6 text-center text-gray-500 text-sm font-medium"><?= $no++; ?></td>
                                <td class="py-4 px-6 text-sm text-gray-600 font-medium whitespace-nowrap">
                                    <i class="far fa-calendar-alt text-gray-400 mr-1.5"></i>
                                    <?= date('d M Y', strtotime($p['tanggal'])); ?>
                                </td>
                                <td class="py-4 px-6 font-semibold text-gray-800"><?= $p['jenis_pelanggaran']; ?></td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full font-bold text-white bg-red-500 shadow-sm shadow-red-200">
                                        <?= $p['poin']; ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-600 italic">
                                    <?= $p['keterangan'] ?: '<span class="text-gray-400">Tidak ada keterangan spesifik</span>'; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <!-- Empty State - Sangat Baik -->
                <div class="p-16 flex flex-col items-center justify-center text-center bg-gradient-to-b from-transparent to-green-50/30">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-5 border-4 border-green-50 shadow-sm relative">
                        <i class="fas fa-shield-alt text-4xl text-green-500"></i>
                        <span class="absolute -bottom-2 -right-2 bg-yellow-400 text-white w-8 h-8 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                            <i class="fas fa-star text-xs"></i>
                        </span>
                    </div>
                    <h6 class="text-xl font-extrabold text-green-700 mb-2">Sangat Disiplin!</h6>
                    <p class="text-gray-600 max-w-md">Luar biasa! Tidak ada catatan pelanggaran sama sekali untuk siswa ini. Pertahankan terus kedisiplinan dan tingkatkan prestasi.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
