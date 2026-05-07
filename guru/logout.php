<?php
session_start();
session_destroy();
echo "<script>alert('Anda telah berhasil logout.');location='../index.php';</script>";
?>
