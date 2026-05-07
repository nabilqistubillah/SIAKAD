<?php
include 'config/config.php';
$res = $koneksi->query("DESC mengajar");
echo "MENGAJAR:\n";
while($r = $res->fetch_assoc()) { echo $r['Field'] . " " . $r['Type'] . "\n"; }

$res2 = $koneksi->query("DESC nilai");
echo "\nNILAI:\n";
while($r = $res2->fetch_assoc()) { echo $r['Field'] . " " . $r['Type'] . "\n"; }

$res3 = $koneksi->query("DESC absensi");
echo "\nABSENSI:\n";
while($r = $res3->fetch_assoc()) { echo $r['Field'] . " " . $r['Type'] . "\n"; }
?>
