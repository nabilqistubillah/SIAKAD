<?php
$id_siswa = $_SESSION['siswa']['id_siswa'];

// data siswa
$siswa = $koneksi->query("
  SELECT s.*, t.tahun_ajaran
  FROM siswa s
  LEFT JOIN tahun t ON s.id_tahun = t.id_tahun
  WHERE s.id_siswa = '$id_siswa'
")->fetch_assoc();

// data kelas aktif
$kelas = $koneksi->query("
  SELECT k.nama_kelas, j.nama_jurusan, t.tahun_ajaran
  FROM siswakelas sk
  JOIN kelas k ON sk.id_kelas = k.id_kelas
  JOIN jurusan j ON k.id_jurusan = j.id_jurusan
  JOIN tahun t ON k.id_tahun = t.id_tahun
  WHERE sk.id_siswa = '$id_siswa'
")->fetch_assoc();
?>

<!-- Header Section -->
<div class="mb-6 flex items-center justify-between">
    <h3 class="text-2xl font-bold text-gray-800 flex items-center">
        <i class="fas fa-user-circle mr-3 text-primary"></i> 
        Profil Siswa
    </h3>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- FOTO & STATUS -->
    <div class="md:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center h-full flex flex-col items-center justify-center relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-b from-primary/5 to-transparent h-1/2 transition-opacity duration-300 group-hover:opacity-100 opacity-60"></div>
            
            <div class="relative w-40 h-40 mx-auto rounded-full bg-white p-1.5 shadow-md border-2 border-primary/20 mb-4 z-10 transition-transform group-hover:scale-105 duration-300">
                <?php if (!empty($siswa['foto_siswa'])): ?>
                    <img src="../siswa-foto/<?= $siswa['foto_siswa']; ?>" alt="Foto Siswa" class="w-full h-full object-cover rounded-full">
                <?php else: ?>
                    <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                        <i class="fas fa-user text-5xl"></i>
                    </div>
                <?php endif; ?>
            </div>
            
            <h4 class="text-xl font-bold text-gray-800 relative z-10"><?= $siswa['nama_siswa']; ?></h4>
            <p class="text-gray-500 mb-4 relative z-10 text-sm"><?= $siswa['induk_siswa']; ?></p>
            
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-700 relative z-10 uppercase tracking-widest shadow-sm border border-green-200">
                <i class="fas fa-check-circle mr-1.5"></i> <?= $siswa['status']; ?>
            </span>
        </div>
    </div>

    <!-- DATA PRIBADI & KELAS -->
    <div class="md:col-span-2 space-y-6">
        
        <!-- DATA PRIBADI -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center">
                <i class="fas fa-address-card text-gray-400 mr-2"></i>
                <h5 class="font-bold text-gray-700">Data Pribadi</h5>
            </div>
            <div class="p-6">
                <dl class="divide-y divide-gray-100">
                    <div class="py-3 flex flex-col sm:flex-row sm:items-center">
                        <dt class="text-sm font-medium text-gray-500 sm:w-1/3">Nomor Induk Siswa</dt>
                        <dd class="mt-1 text-base text-gray-900 sm:mt-0 sm:w-2/3 font-semibold"><?= $siswa['induk_siswa']; ?></dd>
                    </div>
                    <div class="py-3 flex flex-col sm:flex-row sm:items-center">
                        <dt class="text-sm font-medium text-gray-500 sm:w-1/3">Tahun Masuk</dt>
                        <dd class="mt-1 text-base text-gray-900 sm:mt-0 sm:w-2/3 font-semibold"><?= $siswa['tahun_ajaran']; ?></dd>
                    </div>
                    <div class="py-3 flex flex-col sm:flex-row sm:items-center">
                        <dt class="text-sm font-medium text-gray-500 sm:w-1/3">Tanggal Lahir</dt>
                        <dd class="mt-1 text-base text-gray-900 sm:mt-0 sm:w-2/3 font-semibold"><?= $siswa['tanggal_lahir']; ?></dd>
                    </div>
                    <div class="py-3 flex flex-col sm:flex-row">
                        <dt class="text-sm font-medium text-gray-500 sm:w-1/3">Alamat Lengkap</dt>
                        <dd class="mt-1 text-sm text-gray-800 sm:mt-0 sm:w-2/3 leading-relaxed"><?= $siswa['alamat_siswa'] ?: '<span class="text-gray-400 italic">Belum diisi</span>'; ?></dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- INFORMASI KELAS -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center">
                <i class="fas fa-chalkboard-teacher text-gray-400 mr-2"></i>
                <h5 class="font-bold text-gray-700">Informasi Kelas & Jurusan</h5>
            </div>
            <div class="p-6">
                <?php if ($kelas): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 hover:shadow-md transition-shadow">
                            <p class="text-xs text-blue-500 uppercase font-bold tracking-wider mb-1">Kelas Aktif</p>
                            <p class="text-lg font-bold text-gray-800 truncate"><?= $kelas['nama_kelas']; ?></p>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100 hover:shadow-md transition-shadow">
                            <p class="text-xs text-indigo-500 uppercase font-bold tracking-wider mb-1">Jurusan</p>
                            <p class="text-lg font-bold text-gray-800 truncate"><?= $kelas['nama_jurusan']; ?></p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-xl border border-purple-100 hover:shadow-md transition-shadow">
                            <p class="text-xs text-purple-500 uppercase font-bold tracking-wider mb-1">Tahun Ajaran</p>
                            <p class="text-lg font-bold text-gray-800 truncate"><?= $kelas['tahun_ajaran']; ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex items-center justify-center p-6 bg-yellow-50 rounded-xl border border-yellow-100">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mr-4"></i>
                        <div>
                            <h6 class="font-bold text-yellow-800 mb-1">Peringatan</h6>
                            <p class="text-yellow-700 text-sm">Siswa belum terdaftar di kelas aktif mana pun pada sistem.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
