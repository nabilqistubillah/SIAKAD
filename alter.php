<?php
$koneksi = new mysqli('localhost', 'root', '', 'smk_siakad');
$res = $koneksi->query('ALTER TABLE kelas ADD COLUMN id_guru INT DEFAULT 0 AFTER id_jurusan');
if ($res) {
    echo "Success";
} else {
    echo "Error: " . $koneksi->error;
}
?>
