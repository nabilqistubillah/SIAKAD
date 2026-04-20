<?php
$id = $_GET['id'];

// Get info kelas
$ambil = $koneksi->query("SELECT * FROM kelas
    LEFT JOIN tahun ON kelas.id_tahun=tahun.id_tahun
    LEFT JOIN jurusan ON kelas.id_jurusan=jurusan.id_jurusan
    WHERE kelas.id_kelas='$id'");
$kelas = $ambil->fetch_assoc();

// Get siswa in class
$siswakelas = array();
$ambil = $koneksi->query("SELECT * FROM siswakelas
 LEFT JOIN siswa ON siswakelas.id_siswa=siswa.id_siswa
 LEFT JOIN tahun ON siswa.id_tahun=tahun.id_tahun
 WHERE siswakelas.id_kelas = '$id'");
while ($tiap = $ambil->fetch_assoc()) {
    $siswakelas[] = $tiap;
}
?>

<div class="max-w-6xl mx-auto fade-in">
    
    <!-- Action Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6 bg-white p-4 rounded-xl border border-gray-200 shadow-sm sticky top-0 z-10">
         <div>
            <h2 class="text-xl font-bold text-gray-800 font-heading">Manajemen Kelas</h2>
            <div class="flex items-center gap-2 text-sm text-gray-500 mt-1">
                 <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded border border-indigo-100 font-medium"><?= $kelas['nama_kelas'] ?></span>
                 <span class="text-gray-300">|</span>
                 <span><?= $kelas['nama_jurusan'] ?></span>
                 <span class="text-gray-300">|</span>
                 <span><?= $kelas['tahun_ajaran'] ?></span>
            </div>
        </div>
        <div class="flex gap-3">
             <a href="index.php?halaman=kelas" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <button class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200" onclick="openModalSiswa()">
                <i class="fas fa-user-plus mr-2"></i>Tambah Siswa
            </button>
        </div>
    </div>

    <!-- Student List Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Daftar Siswa Kelas</h3>
            <span class="text-xs font-medium bg-gray-200 text-gray-700 px-2 py-1 rounded-full"><?= count($siswakelas) ?> Siswa</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-white text-gray-500 font-semibold uppercase text-xs tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4">NIS</th>
                        <th class="px-6 py-4 text-center">Tahun Masuk</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($siswakelas)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-users-slash text-4xl mb-3 opacity-50"></i>
                                    <p class="font-medium text-gray-600">Belum ada siswa di kelas ini.</p>
                                    <p class="text-xs mt-1">Gunakan tombol "Tambah Siswa" untuk memulai.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($siswakelas as $key => $value): 
                             $initial = strtoupper(substr($value['nama_siswa'], 0, 1));
                             $foto = $value['foto_siswa'];
                             $hasFoto = !empty($foto) && file_exists("../siswa-foto/$foto");
                             $imageSrc = $hasFoto ? "../siswa-foto/$foto" : "";
                        ?>
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-4 text-center text-gray-400 font-mono text-xs"><?= $key + 1 ?></td>
                                <td class="px-6 py-4">
                                     <div class="flex items-center gap-3">
                                        <?php if($hasFoto): ?>
                                            <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-200">
                                                <img src="<?= $imageSrc ?>" alt="<?= $value['nama_siswa'] ?>" class="w-full h-full object-cover">
                                            </div>
                                        <?php else: ?>
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                                <?= $initial ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="font-medium text-gray-800 group-hover:text-indigo-600 transition-colors">
                                            <?= $value['nama_siswa'] ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-gray-500 text-xs"><?= $value['induk_siswa'] ?></td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded border border-gray-200"><?= $value['tahun_ajaran'] ?></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                        <a href="index.php?halaman=siswa_detail&id=<?= $value['id_siswa']; ?>" 
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 transition-colors" title="Lihat Detail">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="index.php?halaman=siswa_hapus&id=<?= $value['id_siswakelas']; ?>&id_kelas=<?= $id; ?>" 
                                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 hover:text-rose-700 transition-colors" title="Hapus dari Kelas" onclick="return confirm('Hapus siswa ini dari kelas?')">
                                            <i class="fas fa-user-minus text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Masukkan Siswa Baru -->
<?php
// Ambil data tahun untuk form
$tahun_data = [];
$ambil_t = $koneksi->query("SELECT * FROM tahun ORDER BY id_tahun DESC");
while ($tiap_t = $ambil_t->fetch_assoc()) {
    $tahun_data[] = $tiap_t;
}
?>

<!-- Modal Background (Tailwind) -->
<div id="modal-masukkan-siswa" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity opacity-0 duration-300" id="modal-backdrop-siswa" onclick="closeModalSiswa()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Modal Panel -->
            <div id="modal-panel-siswa" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all duration-300 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 sm:my-8 w-full max-w-3xl border border-gray-100">
                
                <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                    <div>
                         <h5 class="text-lg font-bold text-gray-800" id="modal-title">Tambahkan Siswa Baru</h5>
                         <p class="text-xs text-gray-500 mt-1">Input data siswa baru untuk dimasukkan ke kelas ini.</p>
                    </div>
                    <button type="button" onclick="closeModalSiswa()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-200 p-2 rounded-lg transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form method="post" enctype="multipart/form-data">
                    <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                        
                        <!-- Foto Siswa -->
                        <div class="flex flex-col items-center mb-4">
                            <div class="relative w-24 h-24 mb-2 group cursor-pointer">
                                <div class="w-full h-full rounded-2xl overflow-hidden border-4 border-gray-100 shadow-inner bg-gray-50" id="photo-preview-container-siswa">
                                    <img id="preview-img-siswa" class="w-full h-full object-cover hidden">
                                    <div id="default-icon-siswa" class="w-full h-full flex items-center justify-center text-gray-300">
                                        <i class="fas fa-user-graduate text-2xl"></i>
                                    </div>
                                </div>
                                <input type="file" name="foto" id="foto-input-siswa" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" onchange="previewImageSiswa()">
                                <div class="absolute inset-0 bg-black/40 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                    <span class="text-white text-[10px] font-medium">Ubah Foto</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-center text-gray-400">Foto Opsional<br>JPG/PNG maks 2MB.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Nama Siswa</label>
                                <input type="text" name="nama" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors" placeholder="Nama Lengkap" required>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">NISN</label>
                                <input type="text" name="nis" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors" placeholder="Nomor Induk Siswa" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <p class="text-[10px] text-gray-400 mt-1">Digunakan sebagai password default akun siswa.</p>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Tahun Masuk</label>
                                <select name="id_tahun" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors appearance-none" required>
                                    <option value="">Pilih Tahun Ajaran</option>
                                    <?php foreach ($tahun_data as $t): ?>
                                        <option value="<?= $t['id_tahun'] ?>"><?= $t['tahun_ajaran'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors" required>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Alamat Domisili</label>
                                <textarea name="alamat" rows="2" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:bg-white focus:border-indigo-500 transition-colors resize-none" placeholder="Alamat lengkap..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                        <button type="button" onclick="closeModalSiswa()" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition text-sm">
                            Batal
                        </button>
                        <button type="submit" name="simpan_siswa_baru" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30 text-sm flex items-center">
                            <i class="fas fa-save mr-2"></i>Simpan & Masukkan Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modal-masukkan-siswa');
    const backdrop = document.getElementById('modal-backdrop-siswa');
    const panel = document.getElementById('modal-panel-siswa');

    function openModalSiswa() {
        modal.classList.remove('hidden');
        // Force reflow
        void modal.offsetWidth;
        
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
        
        panel.classList.remove('opacity-0', 'translate-y-4', 'sm:scale-95');
        panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
    }

    function closeModalSiswa() {
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        
        panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        panel.classList.add('opacity-0', 'translate-y-4', 'sm:scale-95');
        
        // Wait for transition to finish
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function previewImageSiswa() {
        const input = document.getElementById('foto-input-siswa');
        const previewImg = document.getElementById('preview-img-siswa');
        const defaultIcon = document.getElementById('default-icon-siswa');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                defaultIcon.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php
if (isset($_POST['simpan_siswa_baru'])) {
    $tahun      = $_POST['id_tahun'];
    $nis        = $_POST['nis'];
    $pass       = sha1($nis); // Menggunakan NISN sebagai password default
    $nama       = $_POST['nama'];
    $alamat     = $_POST['alamat'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    
    $foto_nama  = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $foto_nama = date("YmdHis") . "_" . preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], "../siswa-foto/" . $foto_nama);
    }
    
    $stmt = $koneksi->prepare("INSERT INTO siswa (id_tahun, induk_siswa, pw_siswa, nama_siswa, alamat_siswa, foto_siswa, tanggal_lahir, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'AKTIF')");
    $stmt->bind_param("sssssss", $tahun, $nis, $pass, $nama, $alamat, $foto_nama, $tanggal_lahir);
    $query_siswa = $stmt->execute();
    
    if ($query_siswa) {
        $id_siswa_baru = $koneksi->insert_id;
        $id_kelas = $_GET['id'];
        
        $koneksi->query("INSERT INTO siswakelas (id_siswa, id_kelas) VALUES ('$id_siswa_baru', '$id_kelas')");
        
        echo "<script>alert('Siswa berhasil ditambahkan dan dimasukkan ke kelas!');</script>";
        echo "<script>location='index.php?halaman=siswakelas&id=$id_kelas';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan: " . $koneksi->error . "')</script>";
    }
}
?>