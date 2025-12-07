<?php
// kenaikan_kelas.php
// Letakkan di folder admin, pastikan path include config.php benar
include_once './config.php'; // sesuaikan jika path beda

// util: cek apakah kolom status ada
function has_status_column($koneksi) {
    $res = $koneksi->query("SHOW COLUMNS FROM siswa LIKE 'status'");
    return ($res && $res->num_rows > 0);
}

// ambil semua mapping kelas (per id_jurusan dan jenjang)
$kelas_rows = [];
$q = $koneksi->query("SELECT id_kelas, id_jurusan, jenjang_kelas, nama_kelas FROM kelas");
while ($r = $q->fetch_assoc()) {
    $kelas_rows[] = $r;
}

// bangun mapping: mapping[jurusan][jenjang] = id_kelas
$mapping = [];
foreach ($kelas_rows as $k) {
    $mapping[$k['id_jurusan']][ intval($k['jenjang_kelas']) ] = $k['id_kelas'];
}

// tampilkan ringkasan (opsional)
echo "<h4>Kenaikan Kelas - Konfirmasi</h4>";
echo "<p>Jumlah kelas load: ".count($kelas_rows)."</p>";
echo "<form method='post' onsubmit=\"return confirm('Yakin ingin mengeksekusi kenaikan kelas untuk seluruh siswa? Proses tidak bisa dibalik kecuali restore DB.');\">";

echo "<p><strong>Aturan:</strong> siswa di jenjang 10→dipromosikan ke jenjang 11; 11→12; 12→dianggap lulus (dikeluarkan dari siswakelas dan akan diberi status alumni bila kolom ada).</p>";

echo "<p>";
if (has_status_column($koneksi)) {
    echo "<b>Kolom status ditemukan:</b> akan menandai siswa kelas 12 menjadi 'alumni'.";
} else {
    echo "<b>Kolom status TIDAK ditemukan:</b> siswa kelas 12 akan dikeluarkan dari siswakelas (tidak ada tanda alumni).</p>";
    echo "<p>Kalau mau tanda alumni, jalankan SQL ALTER TABLE (script ada di dokumentasi) lalu ulangi proses.</p>";
}
echo "</p>";

echo "<button name='run' class='btn btn-primary'>Jalankan Kenaikan Kelas Sekarang</button> ";
echo "<a href='index.php?halaman=kelas' class='btn btn-secondary'>Batal</a>";
echo "</form>";

if (isset($_POST['run'])) {

    $hasStatus = has_status_column($koneksi);

    // Siapkan laporan
    $summary = [
        'total_processed' => 0,
        'promoted_inserted' => 0,
        'promoted_skipped_already' => 0,
        'graduated' => 0,
        'graduated_skipped' => 0,
        'errors' => [],
        'missing_target_class' => []
    ];

    // mulai transaction
    $koneksi->begin_transaction();

    try {
        // ambil semua siswa yang punya entry dalam siswakelas (current assignments)
        $sql = "SELECT sk.id_siswakelas, sk.id_siswa, sk.id_kelas, k.id_jurusan, k.jenjang_kelas 
                FROM siswakelas sk
                JOIN kelas k ON sk.id_kelas = k.id_kelas";
        $res = $koneksi->query($sql);
        if (!$res) throw new Exception("Query siswakelas gagal: " . $koneksi->error);

        while ($row = $res->fetch_assoc()) {
            $summary['total_processed']++;
            $id_siswakelas = $row['id_siswakelas'];
            $id_siswa = $row['id_siswa'];
            $id_kelas_lama = $row['id_kelas'];
            $id_jurusan = $row['id_jurusan'];
            $jenjang = intval($row['jenjang_kelas']);

            if ($jenjang < 12) {
                $target_jenjang = $jenjang + 1;
                // apakah ada kelas target untuk jurusan ini dan jenjang target?
                if (!isset($mapping[$id_jurusan][$target_jenjang])) {
                    // tidak ada kelas tujuan — catat dan skip
                    $summary['missing_target_class'][] = [
                        'id_siswa' => $id_siswa,
                        'from_kelas' => $id_kelas_lama,
                        'required_jenjang' => $target_jenjang,
                        'id_jurusan' => $id_jurusan
                    ];
                    continue;
                }
                $id_kelas_target = $mapping[$id_jurusan][$target_jenjang];

                // cek apakah siswa sudah ada di kelas target (hindari duplikat)
                $cek = $koneksi->query("SELECT * FROM siswakelas WHERE id_siswa='$id_siswa' AND id_kelas='$id_kelas_target'");
                if ($cek === false) throw new Exception("Error cek duplikat: " . $koneksi->error);

                if ($cek->num_rows > 0) {
                    $summary['promoted_skipped_already']++;
                    continue; // sudah ada, skip
                }

                // insert new assignment (riwayat dipertahankan)
                $ins = $koneksi->query("INSERT INTO siswakelas (id_siswa, id_kelas) VALUES ('$id_siswa', '$id_kelas_target')");
                if (!$ins) throw new Exception("Gagal insert siswakelas untuk siswa $id_siswa ke kelas $id_kelas_target: " . $koneksi->error);

                $summary['promoted_inserted']++;

            } else {
                // jenjang == 12 -> lulusan
                // delete assignment sehingga siswa tidak muncul di daftar kelas
                $del = $koneksi->query("DELETE FROM siswakelas WHERE id_siswakelas = '$id_siswakelas'");
                if (!$del) {
                    // jika gagal delete -> catat error dan lanjut
                    $summary['errors'][] = "Gagal menghapus siswakelas id $id_siswakelas: " . $koneksi->error;
                } else {
                    $summary['graduated']++;
                    // update status jika kolom ada
                    if ($hasStatus) {
                        $upd = $koneksi->query("UPDATE siswa SET status='alumni' WHERE id_siswa='$id_siswa'");
                        if (!$upd) {
                            $summary['errors'][] = "Gagal set status alumni untuk siswa $id_siswa: " . $koneksi->error;
                        }
                    }
                }
            }
        } // end loop

        // jika ada error besar? di sini kita commit jika tidak ada exception
        $koneksi->commit();

    } catch (Exception $e) {
        $koneksi->rollback();
        $summary['errors'][] = "Transaction rollback karena: " . $e->getMessage();
    }

    // tampilkan ringkasan
    echo "<h4>Ringkasan Hasil</h4>";
    echo "<ul>";
    echo "<li>Total baris siswakelas diproses: " . intval($summary['total_processed']) . "</li>";
    echo "<li>Siswa dipromosikan (insert baru): " . intval($summary['promoted_inserted']) . "</li>";
    echo "<li>Siswa yang sudah di kelas target (skip): " . intval($summary['promoted_skipped_already']) . "</li>";
    echo "<li>Siswa lulus (dikeluarkan): " . intval($summary['graduated']) . "</li>";
    if (!empty($summary['missing_target_class'])) {
        echo "<li>Beberapa siswa tidak dipromosikan karena tidak ditemukan kelas tujuan (periksa mapping kelas/jurusan): " . count($summary['missing_target_class']) . "</li>";
    }
    if (!empty($summary['errors'])) {
        echo "<li>Errors: <br><pre>" . implode("\n", $summary['errors']) . "</pre></li>";
    }
    echo "</ul>";

    if (!empty($summary['missing_target_class'])) {
        echo "<h5>Contoh missing target:</h5><pre>";
        foreach ($summary['missing_target_class'] as $m) {
            echo "id_siswa={$m['id_siswa']} jurusan={$m['id_jurusan']} perlu jenjang={$m['required_jenjang']} (kelas lama {$m['from_kelas']})\n";
        }
        echo "</pre>";
    }

    echo "<p><a href='index.php?halaman=kelas' class='btn btn-secondary'>Kembali ke Kelas</a></p>";
}
?>
