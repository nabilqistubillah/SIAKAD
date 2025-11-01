<?php 
$id_guru = $_GET['id'];
$ambil = $koneksi->query("SELECT * FROM guru WHERE id_guru = '$id_guru'");
$guru = $ambil->fetch_assoc();

?>  
  
  <div class="row">
    <div class="col-6">
        <h4>Edit Guru</h4>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label>NIP</label>
                <input type="text" class="form-control" name="nip" value="<?php echo $guru['induk_guru']?>" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="text" class="form-control" name="password" required>
                <p class="small text-primary">Kosongkan jika password tidak diubah</p>
            </div>
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama" value="<?php echo $guru['nama_guru']?>" required>
            </div>
            <div class="mb-3">
                <label>Jenis Kelamin</label>
                <select class="form-control" name="jk">
                    <option value="">Pilih</option>
                    <option value="Laki-laki" <?php echo $guru['kelamin_guru']=='laki laki' ? 'selected' : '';?> >Laki-laki</option>
                    <option value="Perempuan" <?php echo $guru['kelamin_guru']=='Perempuan' ? 'selected' : '';?> >Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea class="form-control" name="alamat" <?php echo $guru['alamat_guru']?> ></textarea>
            </div>
            <div class="mb-3">
                <label>Foto Lama</label> <br>
                <img src="../assets/guru/<?php echo $guru['foto_guru']?>" width="200">
            </div>
            <div class="mb-3">
                <label>Ganti Foto</label>
                <input type="file" class="form-control" name="foto">
            </div>
            <button class="btn btn-primary btn-sm" name="simpan">Simpan Perubahan</button>
        </form>
    </div>
</div>

<?php 
if (isset($_POST['simpan'])) 
    {
    $ps = sha1($_POST['password']);
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $jk = $_POST['jk'];
    $alamat = $_POST['alamat'];

    //masalah foto
    $namafoto = $_FILES['foto']['name'];
    $lokasifoto = $_FILES['foto']['tmp_name'];
    
    // jika foto tidak kosong atau ada (berarti si user ganti foto baru)
    if(!empty($lokasifoto))
    {
        $namafoto = date("YmdHis").$namafoto;
        move_uploaded_file($lokasifoto, "/SIAKAD/assets/guru/".$namafoto);

        //jika pass yang tidak kosong (ada)
        if(!empty($_POST['password']))
        {
            $koneksi->query("UPDATE guru SET
                induk_guru = '$nip',
                pw_guru = '$ps',
                nama_guru = '$nama', 
                kelamin_guru = '$jk',
                alamat_guru = '$alamat',
                foto_guru = '$namafoto' WHERE id_guru = '$id_guru'");
        }
        else
        {
            $koneksi->query("UPDATE guru SET
                induk_guru = '$nip',
                nama_guru = '$nama', 
                kelamin_guru = '$jk',
                alamat_guru = '$alamat',
                foto_guru = '$namafoto'WHERE id_guru = '$id_guru'");
        }
    }
    else
    {
        if(!empty($_POST['password'])) //tidak ada foto tapi ada pass
        {
            $koneksi->query("UPDATE guru SET
                induk_guru = '$nip',
                pw_guru = '$ps',
                nama_guru = '$nama', 
                kelamin_guru = '$jk',
                alamat_guru = '$alamat' WHERE id_guru = '$id_guru'");
        }
        else
        {
            $koneksi->query("UPDATE guru SET
                induk_guru = '$nip',
                nama_guru = '$nama', 
                kelamin_guru = '$jk',
                alamat_guru = '$alamat' WHERE id_guru = '$id_guru'");
        }

    }

    echo "<script>alert('data tersimpan')</script>";
    echo "<script>location='index.php?halaman=guru'</script>";
} 
?>