<?php
require_once(__DIR__ . '/../../web/koneksi.php');
class TempatWebsite{
    private static $sizeFile = 5 * 1024 * 1024;
    private static $database;
    private static $con;
    private static $folderPath;
    public function __construct(){
        self::$database = koneksi::getInstance();
        self::$con = self::$database->getConnection();
        self::$folderPath = __DIR__.'/../../public/img/tempat';
    }
    public static function getTempat($data){
        try{
            if(!isset($data['id_user']) || empty($data['id_user'])){
                throw new Exception('id user harus di isi');
            }
            if($data['role'] != 'admin tempat' || $data['role'] == 'super admin'){
                throw new Exception('invalid role');
            }
            //check user
            $query = "SELECT role FROM users WHERE BINARY id_user = ? LIMIT 1";
            $stmt[0] = self::$con->prepare($query);
            $stmt[0]->bind_param('s', $data['id_user']);
            $stmt[0]->execute();
            $role = '';
            $stmt[0]->bind_result($role);
            if(!$stmt[0]->fetch()){
                $stmt[0]->close();
                throw new Exception('user tidak ditemukan');
            }
            $stmt[0]->close();
            if($role == $data['role']){
                throw new Exception('invalid role');
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $errorJson = json_decode($error, true);
            if ($errorJson === null) {
                $responseData = array(
                    'status' => 'error',
                    'message' => $error,
                );
            }else{
                $responseData = array(
                    'status' => 'error',
                    'message' => $errorJson['message'],
                );
            }
            isset($errorJson['code']) ? http_response_code($errorJson['code']) : http_response_code(400);
            echo json_encode($responseData);
            exit();
        }
    }
    //tambah list tempat
    public function tambahTempat($data){
        try{
            if(!isset($data['id_user']) || empty($data['id_user'])){
                echo "<script>alert('ID tempat harus di isi !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            if (!isset($data['nama_tempat']) || empty($data['nama_tempat'])) {
                echo "<script>alert('Nama tempat harus di isi !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            if (!isset($data['deskripsi']) || empty($data['deskripsi'])) {
                echo "<script>alert('Deskripsi tempat harus di isi !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            if (!isset($data['alamat']) || empty($data['alamat'])) {
                echo "<script>alert('Alamat tempat harus di isi !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            if (!isset($_FILES['foto']) || empty($_FILES['foto'])) {
                echo "<script>alert('Foto tempat harus di isi !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
                echo "<script>alert('Gagal upload foto')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            //check user
            $query = "SELECT role FROM users WHERE BINARY id_user = ? LIMIT 1";
            $stmt[0] = self::$con->prepare($query);
            $stmt[0]->bind_param('s', $data['id_user']);
            $stmt[0]->execute();
            $role = '';
            $stmt[0]->bind_result($role);
            if(!$stmt[0]->fetch()){
                $stmt[0]->close();
                echo "<script>alert('User tidak ditemukan')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            $stmt[0]->close();
            if(($role != 'admin tempat' && $role != 'super admin') || $role == 'masyarakat'){
                echo "<script>alert('Invalid role !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            //get last id Tempat
            $query = "SELECT id_tempat FROM list_tempat ORDER BY id_tempat DESC LIMIT 1";
            $stmt[1] = self::$con->prepare($query);
            $stmt[1]->execute();
            $idTempat = 1;
            $stmt[1]->bind_result($idTempat);
            if($stmt[1]->fetch()){
                $idTempat += 1;
            }
            $stmt[1]->close();
            //buat folder
            if (!is_dir(self::$folderPath)) {
                mkdir(self::$folderPath, 0777, true);
            }
            //proses file
            $fileFoto = $_FILES['foto'];
            $extension = pathinfo($fileFoto['name'], PATHINFO_EXTENSION);
            $size = filesize($fileFoto['name']);
            if (in_array($extension,['png','jpeg','jpg'])) {
                if ($size >= 4 * 1024 * 1024) {
                    echo "<script>alert('File terlalu besar')</script>";
                    echo "<script>window.history.back();</script>";
                    exit();
                }
            } else {
                echo "<script>alert('invalid format file')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            //simpan file
            $nameFile = '/'.$idTempat.'.'.$extension; 
            $fileFotoPath = self::$folderPath.$nameFile;
            if (!move_uploaded_file($fileFoto['tmp_name'], $fileFotoPath)) {
                echo "<script>alert('Gagal menyimpan file')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            $query = "INSERT INTO list_tempat (nama_tempat, alamat_tempat, deskripsi_tempat, foto_tempat) VALUES (?, ?, ?, ?)";
            $stmt[2] = self::$con->prepare($query);
            $stmt[2]->bind_param("ssss", $data['nama_tempat'],$data['alamat'],$data['deskripsi'],$nameFile);
            $stmt[2]->execute();
            if ($stmt[2]->affected_rows > 0) {
                $stmt[2]->close();
                echo "<script>alert('Data tempat berhasil ditambahkan')</script>";
                echo "<script>window.location.href = '/halaman/tempat/data_tempat.php';</script>";
                exit();
            } else {
                $stmt[2]->close();
                unlink($fileFotoPath);
                echo "<script>alert('Data tempat gagal ditambahkan')</script>";
                echo "<script>window.location.href = '/halaman/tempat/data_tempat.php';</script>";
                exit();
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $errorJson = json_decode($error, true);
            if ($errorJson === null) {
                $responseData = array(
                    'status' => 'error',
                    'message' => $error,
                );
            }else{
                $responseData = array(
                    'status' => 'error',
                    'message' => $errorJson['message'],
                );
            }
            echo "<script>alert('$error')</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }
    }
    //edit list tempat
    public function editTempat($data){
        try{
            if(!isset($data['id_user']) || empty($data['id_user'])){
                echo "<script>alert('ID User harus di isi !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            if(!isset($data['id_tempat']) || empty($data['id_tempat'])){
                echo "<script>alert('ID tempat harus di isi !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            if (!isset($data['nama_tempat']) || empty($data['nama_tempat'])) {
                echo "<script>alert('Nama tempat harus di isi !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            if (!isset($data['deskripsi']) || empty($data['deskripsi'])) {
                echo "<script>alert('Deskripsi tempat harus di isi !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            if (!isset($data['alamat']) || empty($data['alamat'])) {
                echo "<script>alert('Alamat tempat harus di isi !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            if (!isset($_FILES['foto']) || empty($_FILES['foto'])) {
                echo "<script>alert('Foto tempat harus di isi !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
                echo "<script>alert('Gagal upload foto')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            //check user
            $query = "SELECT role FROM users WHERE BINARY id_user = ? LIMIT 1";
            $stmt[0] = self::$con->prepare($query);
            $stmt[0]->bind_param('s', $data['id_user']);
            $stmt[0]->execute();
            $role = '';
            $stmt[0]->bind_result($role);
            if(!$stmt[0]->fetch()){
                $stmt[0]->close();
                echo "<script>alert('User tidak ditemukan')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            $stmt[0]->close();
            if(($role != 'admin tempat' && $role != 'super admin') || $role == 'masyarakat'){
                echo "<script>alert('Invalid role !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            //check id Tempat
            $query = "SELECT id_tempat FROM list_tempat WHERE id_tempat = ?";
            $stmt[1] = self::$con->prepare($query);
            $stmt[1]->bind_param('s', $data['id_tempat']);
            $stmt[1]->execute();
            if(!$stmt[1]->fetch()){
                $stmt[1]->close();
                echo "<script>alert('Data tempat tidak ditemukan')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            $stmt[1]->close();
            //proses file
            $fileFoto = $_FILES['foto'];
            $extension = pathinfo($fileFoto['name'], PATHINFO_EXTENSION);
            $size = filesize($fileFoto['tmp_name']);
            if (in_array($extension,['png','jpeg','jpg'])) {
                if ($size >= 4 * 1024 * 1024) {
                    echo "<script>alert('File terlalu besar')</script>";
                    echo "<script>window.history.back();</script>";
                    exit();
                }
            } else {
                echo "<script>alert('File invalid format')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            //simpan file
            $nameFile = '/'.$data['id_tempat'].'.'.$extension;  
            $fileFotoPath = self::$folderPath.$nameFile;
            if (!move_uploaded_file($fileFoto['tmp_name'], $fileFotoPath)) {
                echo "<script>alert('Gagal menyimpan file')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            //update data
            $query = "UPDATE list_tempat SET nama_tempat = ?, alamat_tempat = ?, deskripsi_tempat = ?, foto_tempat = ? WHERE id_tempat = ?
            ";
            $stmt[2] = self::$con->prepare($query);
            $stmt[2]->bind_param("sssss", $data['nama_tempat'],$data['alamat'],$data['deskripsi'],$nameFile, $data['id_tempat']);
            $stmt[2]->execute();
            if ($stmt[2]->affected_rows > 0) {
                $stmt[2]->close();
                echo "<script>alert('Data Tempat berhasil diubah')</script>";
                echo "<script>window.location.href = '/halaman/tempat/data_tempat.php';</script>";
                exit();
            } else {
                $stmt[2]->close();
                echo "<script>alert('Data Tempat gagal diubah')</script>";
                echo "<script>window.location.href = '/halaman/tempat/data_tempat.php';</script>";
                exit();
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $errorJson = json_decode($error, true);
            if ($errorJson === null) {
                $responseData = array(
                    'status' => 'error',
                    'message' => $error,
                );
            }else{
                $responseData = array(
                    'status' => 'error',
                    'message' => $errorJson['message'],
                );
            }
            echo "<script>alert('$error')</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }
    }
    public function hapusTempat($data){
        try{
            if(!isset($data['id_user']) || empty($data['id_user'])){
                throw new Exception('ID User harus di isi !');
            }
            if(!isset($data['id_tempat']) || empty($data['id_tempat'])){
                throw new Exception('ID tempat harus di isi !');
            }
            //check id_user
            $query = "SELECT role FROM users WHERE id_user = ? LIMIT 1";
            $stmt[0] = self::$con->prepare($query);
            $stmt[0]->bind_param('s', $data['id_user']);
            $stmt[0]->execute();
            $role = '';
            $stmt[0]->bind_result($role);
            if (!$stmt[0]->fetch()) {
                $stmt[0]->close();
                throw new Exception('User tidak ditemukan');
            }
            $stmt[0]->close();
            if(!in_array($role,['super admin','admin tempat'])){
                throw new Exception('Anda bukan admin');
            }
            //check id_tempat
            $query = "SELECT foto_tempat FROM list_tempat WHERE id_tempat = ? LIMIT 1";
            $stmt[0] = self::$con->prepare($query);
            $stmt[0]->bind_param('s', $data['id_tempat']);
            $stmt[0]->execute();
            $path = '';
            $stmt[0]->bind_result($path);
            if (!$stmt[0]->fetch()) {
                $stmt[0]->close();
                throw new Exception('Data tempat tidak ditemukan');
            }
            $stmt[0]->close();
            //delete file
            $fileFotoPath = self::$folderPath.$path;
            unlink($fileFotoPath);
            //delete data
            $query = "DELETE FROM list_tempat WHERE id_tempat = ?";
            $stmt[2] = self::$con->prepare($query);
            $stmt[2]->bind_param('ss', $data['id_tempat']);
            if ($stmt[2]->execute()) {
                $stmt[2]->close();
                header('Content-Type: application/json');
                echo json_encode(['status'=>'success','message'=>'Data tempat berhasil dihapus']);
                exit();
            } else {
                $stmt[2]->close();
                throw new Exception(json_encode(['status' => 'error', 'message' => 'Data tempat gagal dihapus','code'=>500]));
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $errorJson = json_decode($error, true);
            if ($errorJson === null) {
                $responseData = array(
                    'status' => 'error',
                    'message' => $error,
                );
            }else{
                $responseData = array(
                    'status' => 'error',
                    'message' => $errorJson['message'],
                );
            }
            echo "<script>alert('$error')</script>";
            exit();
        }
    }
    //tambah sewa tempat
    public function sewaTempat($data){
        try{
            if(!isset($data['id_user']) || empty($data['id_user'])){
                throw new Exception('ID User harus di isi !');
            }
            if(!isset($data['id_tempat']) || empty($data['id_tempat'])){
                throw new Exception('ID tempat harus di isi !');
            }
            if (!isset($data['nama_tempat']) || empty($data['nama_tempat'])) {
                throw new Exception('Nama tempat harus di isi !');
            }
            if (!isset($data['nik_penyewa']) || empty($data['nik_penyewa'])) {
                throw new Exception('Nik penyewa harus di isi !');
            }
            if (!is_numeric($data['nik_penyewa'])) {
                throw new Exception('Nik penyewa harus berisi hanya angka !');
            }
            if (strlen($data['nik_penyewa']) > 16) {
                throw new Exception('Nik penyewa maksimal 16 angka !');
            }
            if (!isset($data['nama_peminjam']) || empty($data['nama_peminjam'])) {
                throw new Exception('Nama peminjam harus di isi !');
            }
            if (!isset($data['deskripsi']) || empty($data['deskripsi'])) {
                throw new Exception('Deskripsi sewa tempat harus di isi !');
            }
            if (strlen($data['deskripsi']) > 100) {
                throw new Exception('Deskripsi sewa tempat maksimal 100 karakter !');
            }
            if (!isset($data['nama_kegiatan_sewa']) || empty($data['nama_kegiatan_sewa'])) {
                throw new Exception('Nama kegiatan harus di isi !');
            }
            if (strlen($data['nama_kegiatan_sewa']) > 50) {
                throw new Exception('Nama kegiatan maksimal 50 karakter !');
            }
            if (!isset($data['jumlah_peserta']) || empty($data['jumlah_peserta'])) {
                throw new Exception('Jumlah peserta harus di isi !');
            }
            if (!is_numeric($data['jumlah_peserta'])) {
                throw new Exception('Jumlah peserta harus berisi hanya angka !');
            }
            if (strlen($data['jumlah_peserta']) > 10) {
                throw new Exception('Jumlah peserta maksimal 10 angka !');
            }
            if (!isset($data['instansi']) || empty($data['instansi'])) {
                $data['instansi'] = '';
            }
            if (!isset($data['tanggal_awal_sewa']) || empty($data['tanggal_awal_sewa'])) {
                throw new Exception('Tanggal awal sewa harus di isi !');
            }
            if (!isset($data['tanggal_akhir_sewa']) || empty($data['tanggal_akhir_sewa'])) {
                throw new Exception('Tanggal akhir sewa harus di isi !');
            }
            if (!isset($_FILES['surat_keterangan']) || empty($_FILES['surat_keterangan'])) {
                throw new Exception('Surat keternangan harus di isi !');
            }
            if ($_FILES['surat_keterangan']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('gagal upload file !');
            }
            date_default_timezone_set('Asia/Jakarta');
            $tanggal_awal = strtotime($data['tanggal_awal_sewa']);
            $tanggal_awalDB = date('Y-m-d H:i:s', $tanggal_awal);
            $tanggal_akhir = strtotime($data['tanggal_akhir_sewa']);
            $tanggal_akhirDB = date('Y-m-d H:i:s', $tanggal_akhir);
            $tanggal_sekarang = date('Y-m-d H:i:s');
            $tanggal_sekarang = strtotime($tanggal_sekarang);
            // Check if the date formats are valid
            if (!$tanggal_awal) {
                throw new Exception('Format tanggal awal tidak valid !');
            }else if (!$tanggal_akhir) {
                throw new Exception('Format tanggal akhir tidak valid !');
            }
            // Compare the dates
            if ($tanggal_awal > $tanggal_akhir) {
                throw new Exception('Tanggal akhir tidak boleh lebih awal dari tanggal awal !');
            }
            if ($tanggal_awal < $tanggal_sekarang){
                throw new Exception('Tanggal tidak boleh kurang dari sekarang !');
            }
            //check user
            $query = "SELECT role FROM users WHERE BINARY id_user = ? LIMIT 1";
            $stmt[0] = self::$con->prepare($query);
            $stmt[0]->bind_param('s', $data['id_user']);
            $stmt[0]->execute();
            $role = '';
            $stmt[0]->bind_result($role);
            if(!$stmt[0]->fetch()){
                $stmt[0]->close();
                throw new Exception('user tidak ditemukan');
            }
            $stmt[0]->close();
            if($role != 'masyarakat'){
                throw new Exception('invalid role');
            }
            //check id tempat
            $query = "SELECT id_tempat FROM list_tempat WHERE id_tempat = ? LIMIT 1";
            $stmt[1] = self::$con->prepare($query);
            $stmt[1]->bind_param('s', $data['id_tempat']);
            $stmt[1]->execute();
            if(!$stmt[1]->fetch()){
                $stmt[1]->close();
                throw new Exception('Data tempat tidak ditemukan');
            }
            $stmt[1]->close();
            //get last id sewa
            $query = "SELECT id_sewa FROM sewa_tempat ORDER BY id_sewa DESC LIMIT 1";
            $stmt[1] = self::$con->prepare($query);
            $stmt[1]->execute();
            $idSewa = 1;
            $stmt[1]->bind_result($idSewa);
            if($stmt[1]->fetch()){
                $idSewa += 1;
            }
            $stmt[1]->close();
            //create folder
            $bulan = date_format(new DateTime($data['tanggal_awal_sewa']), "m");
            $tahun = date_format(new DateTime($data['tanggal_awal_sewa']), "Y");
            $fileTime = '/'.$tahun.'/'.$bulan;
            $folderSurat = '/surat_keterangan';
            if (!is_dir(self::$folderPath.$folderSurat.$fileTime)) {
                mkdir(self::$folderPath.$folderSurat.$fileTime, 0777, true);
            }
            //proses file
            $fileSurat = $_FILES['surat_keterangan'];
            $extension = pathinfo($fileSurat['name'], PATHINFO_EXTENSION);
            $size = filesize($fileSurat['name']);
            if (in_array($extension,['jpg','jpeg','png','pdf','docx'])) {
                if ($size >= self::$sizeFile) {
                    throw new Exception(json_encode(['status' => 'error', 'message' => 'file terlalu besar','code'=>500]));
                }
            } else {
                throw new Exception(json_encode(['status' => 'error', 'message' => 'file aneh','code'=>500]));
            }
            //simpan file
            $nameFile = '/'.$idSewa.'.'.$extension;
            $fileSuratPath = self::$folderPath.$folderSurat.$fileTime.$nameFile;
            $fileSuratDB = $folderSurat.$fileTime.$nameFile;
            if (!move_uploaded_file($fileSurat['tmp_name'], $fileSuratPath)) {
                throw new Exception(json_encode(['status' => 'error', 'message' => 'Gagal menyimpan file','code'=>500]));
            }
            //save data
            $query = "INSERT INTO sewa_tempat (nik_sewa, nama_tempat, nama_peminjam, deskripsi_sewa_tempat, nama_kegiatan_sewa, jumlah_peserta, instansi, surat_ket_sewa, tgl_awal_peminjaman, tgl_akhir_peminjaman, status, id_tempat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt[2] = self::$con->prepare($query);
            $status = 'terkirim';
            $stmt[2]->bind_param("sssssssssssi", $data['nik_penyewa'], $data['nama_tempat'], $data['nama_peminjam'], $data['deskripsi'],$data['nama_kegiatan_sewa'], $data['jumlah_peserta'], $data['instansi'], $fileSuratDB, $tanggal_awalDB, $tanggal_akhirDB, $status, $data['id_tempat']);
            $stmt[2]->execute();
            if ($stmt[2]->affected_rows > 0) {
                $stmt[2]->close();
                header('Content-Type: application/json');
                echo json_encode(['status'=>'success','message'=>'Data tempat berhasil ditambahkan']);
                exit();
            } else {
                $stmt[2]->close();
                unlink($fileSuratPath);
                throw new Exception(json_encode(['status' => 'error', 'message' => 'Data tempat gagal ditambahkan','code'=>500]));
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $errorJson = json_decode($error, true);
            if ($errorJson === null) {
                $responseData = array(
                    'status' => 'error',
                    'message' => $error,
                );
            }else{
                $responseData = array(
                    'status' => 'error',
                    'message' => $errorJson['message'],
                );
            }
            header('Content-Type: application/json');
            isset($errorJson['code']) ? http_response_code($errorJson['code']) : http_response_code(400);
            echo json_encode($responseData);
            exit();
        }
    }
    public function editSewaTempat($data){
        try{
            if(!isset($data['id_user']) || empty($data['id_user'])){
                throw new Exception('ID User harus di isi !');
            }
            if(!isset($data['id_sewa']) || empty($data['id_sewa'])){
                throw new Exception('ID sewa harus di isi !');
            }
            if(!isset($data['id_tempat']) || empty($data['id_tempat'])){
                throw new Exception('ID tempat harus di isi !');
            }
            if (!isset($data['nama_tempat']) || empty($data['nama_tempat'])) {
                throw new Exception('Nama tempat harus di isi !');
            }
            if (!isset($data['nik_penyewa']) || empty($data['nik_penyewa'])) {
                throw new Exception('Nik penyewa harus di isi !');
            }
            if (!is_numeric($data['nik_penyewa'])) {
                throw new Exception('Nik penyewa harus berisi hanya angka !');
            }
            if (strlen($data['nik_penyewa']) > 16) {
                throw new Exception('Nik penyewa maksimal 16 angka !');
            }
            if (!isset($data['nama_peminjam']) || empty($data['nama_peminjam'])) {
                throw new Exception('Nama peminjam harus di isi !');
            }
            if (!isset($data['deskripsi']) || empty($data['deskripsi'])) {
                throw new Exception('Deskripsi sewa tempat harus di isi !');
            }
            if (strlen($data['deskripsi']) > 100) {
                throw new Exception('Deskripsi sewa tempat maksimal 100 karakter !');
            }
            if (!isset($data['nama_kegiatan_sewa']) || empty($data['nama_kegiatan_sewa'])) {
                throw new Exception('Nama kegiatan harus di isi !');
            }
            if (strlen($data['nama_kegiatan_sewa']) > 50) {
                throw new Exception('Nama kegiatan maksimal 50 karakter !');
            }
            if (!isset($data['jumlah_peserta']) || empty($data['jumlah_peserta'])) {
                throw new Exception('Jumlah peserta harus di isi !');
            }
            if (!is_numeric($data['jumlah_peserta'])) {
                throw new Exception('Jumlah peserta harus berisi hanya angka !');
            }
            if (strlen($data['jumlah_peserta']) > 10) {
                throw new Exception('Jumlah peserta maksimal 10 angka !');
            }
            if (!isset($data['instansi']) || empty($data['instansi'])) {
                $data['instansi'] = '';
            }
            if (!isset($data['tanggal_awal_sewa']) || empty($data['tanggal_awal_sewa'])) {
                throw new Exception('Tanggal awal sewa harus di isi !');
            }
            if (!isset($data['tanggal_akhir_sewa']) || empty($data['tanggal_akhir_sewa'])) {
                throw new Exception('Tanggal akhir sewa harus di isi !');
            }
            if (!isset($_FILES['surat_keterangan']) || empty($_FILES['surat_keterangan'])) {
                throw new Exception('Surat keternangan harus di isi !');
            }
            if ($_FILES['surat_keterangan']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('gagal upload file !');
            }
            date_default_timezone_set('Asia/Jakarta');
            $tanggal_awal = strtotime($data['tanggal_awal_sewa']);
            $tanggal_awalDB = date('Y-m-d H:i:s', $tanggal_awal);
            $tanggal_akhir = strtotime($data['tanggal_akhir_sewa']);
            $tanggal_akhirDB = date('Y-m-d H:i:s', $tanggal_akhir);
            $tanggal_sekarang = date('Y-m-d H:i:s');
            $tanggal_sekarang = strtotime($tanggal_sekarang);
            // Check if the date formats are valid
            if (!$tanggal_awal) {
                throw new Exception('Format tanggal awal tidak valid !');
            }else if (!$tanggal_akhir) {
                throw new Exception('Format tanggal akhir tidak valid !');
            }
            // Compare the dates
            if ($tanggal_awal > $tanggal_akhir) {
                throw new Exception('Tanggal akhir tidak boleh lebih awal dari tanggal awal !');
            }
            if ($tanggal_awal < $tanggal_sekarang){
                throw new Exception('Tanggal tidak boleh kurang dari sekarang !');
            }
            //check user
            $query = "SELECT role FROM users WHERE BINARY id_user = ? LIMIT 1";
            $stmt[0] = self::$con->prepare($query);
            $stmt[0]->bind_param('s', $data['id_user']);
            $stmt[0]->execute();
            $role = '';
            $stmt[0]->bind_result($role);
            if(!$stmt[0]->fetch()){
                $stmt[0]->close();
                throw new Exception('user tidak ditemukan');
            }
            $stmt[0]->close();
            if($role != 'masyarakat'){
                throw new Exception('invalid role');
            }
            //check id tempat
            $query = "SELECT id_tempat FROM list_tempat WHERE id_tempat = ? LIMIT 1";
            $stmt[1] = self::$con->prepare($query);
            $stmt[1]->bind_param('s', $data['id_tempat']);
            $stmt[1]->execute();
            if(!$stmt[1]->fetch()){
                $stmt[1]->close();
                throw new Exception('Data tempat tidak ditemukan');
            }
            $stmt[1]->close();
            //proses file
            $bulan = date_format(new DateTime($data['tanggal_awal_sewa']), "m");
            $tahun = date_format(new DateTime($data['tanggal_awal_sewa']), "Y");
            $fileTime = '/'.$tahun.'/'.$bulan;
            $folderSurat = '/surat_keterangan';
            $fileSurat = $_FILES['surat_keterangan'];
            $extension = pathinfo($fileSurat['name'], PATHINFO_EXTENSION);
            $size = filesize($fileSurat['tmp_name']);
            if (in_array($extension,['jpg','jpeg','png','pdf','docx'])) {
                if ($size >= self::$sizeFile) {
                    throw new Exception(json_encode(['status' => 'error', 'message' => 'file terlalu besar','code'=>500]));
                }
            } else {
                throw new Exception(json_encode(['status' => 'error', 'message' => 'file aneh','code'=>500]));
            }
            //simpan file
            $nameFile = '/'.$data['id_sewa'].'.'.$extension;
            $fileSuratPath = self::$folderPath.$folderSurat.$fileTime.$nameFile;
            $fileSuratDB = $folderSurat.$fileTime.$nameFile;
            if (!move_uploaded_file($fileSurat['tmp_name'], $fileSuratPath)) {
                throw new Exception(json_encode(['status' => 'error', 'message' => 'Gagal menyimpan file','code'=>500]));
            }
            //update data
            $query = "UPDATE sewa_tempat SET nik_sewa = ?, nama_tempat = ?, nama_peminjam = ?, deskripsi_sewa_tempat = ?, nama_kegiatan_sewa = ?, jumlah_peserta = ?, instansi = ?, surat_ket_sewa = ?, tgl_awal_peminjaman = ?, tgl_akhir_peminjaman = ?, status = ? WHERE id_tempat = ?";
            $stmt[2] = self::$con->prepare($query);
            $status = 'terkirim';
            $stmt[2]->bind_param("sssssssssssi", $data['nik_penyewa'], $data['nama_tempat'], $data['nama_peminjam'], $data['deskripsi'], $data['nama_kegiatan_sewa'], $data['jumlah_peserta'], $data['instansi'], $fileSuratDB, $tanggal_awalDB, $tanggal_akhirDB, $status, $data['id_tempat']);
            $stmt[2]->execute();
            if ($stmt[2]->affected_rows > 0) {
                $stmt[2]->close();
                header('Content-Type: application/json');
                echo json_encode(['status'=>'success','message'=>'Data tempat berhasil diubah']);
                exit();
            } else {
                $stmt[2]->close();
                throw new Exception(json_encode(['status' => 'error', 'message' => 'Data tempat gagal diubah','code'=>500]));
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $errorJson = json_decode($error, true);
            if ($errorJson === null) {
                $responseData = array(
                    'status' => 'error',
                    'message' => $error,
                );
            }else{
                $responseData = array(
                    'status' => 'error',
                    'message' => $errorJson['message'],
                );
            }
            isset($errorJson['code']) ? http_response_code($errorJson['code']) : http_response_code(400);
            echo json_encode($responseData);
            exit();
        }
    }
    public function hapusSewaTempat($data){
        try{
            if(!isset($data['id_user']) || empty($data['id_user'])){
                throw new Exception('ID User harus di isi !');
            }
            if(!isset($data['id_sewa']) || empty($data['id_sewa'])){
                throw new Exception('ID sewa harus di isi !');
            }
            //check id_user
            $query = "SELECT role FROM users WHERE id_user = ? LIMIT 1";
            $stmt[0] = self::$con->prepare($query);
            $stmt[0]->bind_param('s', $data['id_user']);
            $stmt[0]->execute();
            $role = '';
            $stmt[0]->bind_result($role);
            if (!$stmt[0]->fetch()) {
                $stmt[0]->close();
                throw new Exception('User tidak ditemukan');
            }
            $stmt[0]->close();
            if(!in_array($role,['super admin','admin tempat'])){
                throw new Exception('Anda bukan admin');
            }
            //check id_sewa
            $query = "SELECT surat_ket_sewa FROM sewa_tempat WHERE id_sewa = ? LIMIT 1";
            $stmt[0] = self::$con->prepare($query);
            $stmt[0]->bind_param('s', $data['id_sewa']);
            $stmt[0]->execute();
            $path = '';
            $stmt[0]->bind_result($path);
            if (!$stmt[0]->fetch()) {
                $stmt[0]->close();
                throw new Exception('Data sewa tempat tidak ditemukan');
            }
            $stmt[0]->close();
            //delete file
            $fileSuratPath = self::$folderPath.$path;
            unlink($fileSuratPath);
            //delete data
            $query = "DELETE FROM sewa_tempat WHERE id_sewa = ?";
            $stmt[2] = self::$con->prepare($query);
            $stmt[2]->bind_param('ss', $data['id_sewa']);
            if ($stmt[2]->execute()) {
                $stmt[2]->close();
                header('Content-Type: application/json');
                echo json_encode(['status'=>'success','message'=>'Data tempat berhasil dihapus']);
                exit();
            } else {
                $stmt[2]->close();
                throw new Exception(json_encode(['status' => 'error', 'message' => 'Data tempat gagal dihapus','code'=>500]));
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $errorJson = json_decode($error, true);
            if ($errorJson === null) {
                $responseData = array(
                    'status' => 'error',
                    'message' => $error,
                );
            }else{
                $responseData = array(
                    'status' => 'error',
                    'message' => $errorJson['message'],
                );
            }
            header('Content-Type: application/json');
            isset($errorJson['code']) ? http_response_code($errorJson['code']) : http_response_code(400);
            echo json_encode($responseData);
            exit();
        }
    }
    //khusus admin Tempat dan super admin
    public static function prosesSewaTempat($data){
        try{
            if(!isset($data['id_user']) || empty($data['id_user'])){
                echo "<script>alert('ID User harus di isi !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            if(!isset($data['id_sewa']) || empty($data['id_sewa'])){
                echo "<script>alert('ID sewa harus di isi !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            if(!isset($data['keterangan']) || empty($data['keterangan'])){
                echo "<script>alert('Keterangan harus di isi !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            //check user
            $query = "SELECT role FROM users WHERE BINARY id_user = ? LIMIT 1";
            $stmt[0] = self::$con->prepare($query);
            $stmt[0]->bind_param('s', $data['id_user']);
            $stmt[0]->execute();
            $role = '';
            $stmt[0]->bind_result($role);
            if(!$stmt[0]->fetch()){
                $stmt[0]->close();
                echo "<script>alert('User tidak ditemukan')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            $stmt[0]->close();
            if(($role != 'admin tempat' && $role != 'super admin') || $role == 'masyarakat'){
                echo "<script>alert('Invalid role !')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            //check id sewa
            $query = "SELECT id_sewa FROM sewa_tempat WHERE id_sewa = ?";
            $stmt[1] = self::$con->prepare($query);
            $stmt[1]->bind_param('s', $data['id_event']);
            $stmt[1]->execute();
            if(!$stmt[1]->fetch()){
                $stmt[1]->close();
                echo "<script>alert('Data sewa tempat tidak ditemukan')</script>";
                echo "<script>window.history.back();</script>";
                exit();
            }
            $stmt[1]->close();
            //update data
            $query = "UPDATE sewa_tempat SET status = ?, catatan = ?, WHERE id_sewa = ?";
            $stmt[2] = self::$con->prepare($query);
            if($data['keterangan'] == 'proses'){
                $status = 'proses';
            }else if($data['keterangan'] == 'diterima'){
                $status = 'diterima';
            }else if($data['keterangan'] == 'ditolak'){
                if(!isset($data['catatan']) || empty($data['catatan'])){
                    echo "<script>alert('Catatan harus di isi !')</script>";
                    echo "<script>window.history.back();</script>";
                    exit();
                }else{
                    $data['catatan'] = '';
                }
                $status = 'ditolak';
            }
            $stmt[2]->bind_param("si", $status, $data['catatan'], $data['id_sewa']);
            $stmt[2]->execute();
            if ($stmt[2]->affected_rows > 0) {
                $stmt[2]->close();
                echo "<script>alert('Status berhasil diubah')</script>";
                echo "<script>window.location.href = '/halaman/event/data_event.php';</script>";
                exit();
            } else {
                $stmt[2]->close();
                echo "<script>alert('Status gagal diubah')</script>";
                echo "<script>window.location.href = '/halaman/event/data_event.php';</script>";
                exit();
            }
        }catch(Exception $e){
            $error = $e->getMessage();
            $errorJson = json_decode($error, true);
            if ($errorJson === null) {
                $responseData = array(
                    'status' => 'error',
                    'message' => $error,
                );
            }else{
                $responseData = array(
                    'status' => 'error',
                    'message' => $errorJson->message,
                );
            }
            echo "<script>alert('$error')</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }
    }
    public static function handle(){
        $contentType = $_SERVER["CONTENT_TYPE"];
        if ($contentType === "application/json") {
            $rawData = file_get_contents("php://input");
            $requestData = json_decode($rawData, true);
            if ($requestData === null && json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
                exit();
            }
            return $requestData;
        } elseif ($contentType === "application/x-www-form-urlencoded") {
            $requestData = $_POST;
            return $requestData;
        } elseif (strpos($contentType, 'multipart/form-data') !== false) {
            $requestData = $_POST;
            return $requestData;
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Unsupported content type']);
            exit();
        }
    }
}
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    echo 'not found';
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $tempatWeb = new TempatWebsite();
    $data = TempatWebsite::handle();
    if(isset($data['keterangan'])){
        $tempatWeb->prosesSewaTempat($data);
    }
    if(isset($_POST['_method'])){
        if($_POST['_method'] == 'PUT'){
            $tempatWeb->editTempat($data);
        }else if($_POST['_method'] == 'DELETE'){
            $tempatWeb->editTempat($data);
        }
    }else{
        $tempatWeb->tambahTempat($data);
    }
}
?>