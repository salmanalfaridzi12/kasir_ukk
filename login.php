<?php
session_start();
if (isset($_SESSION["id_pengguna"])) {
  header("Location:index.php?page=dashboard");
}
$pesan = "";
//Fungsi untuk mencegah inputan karakter yang tidak sesuai
function input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
//Cek apakah ada kiriman form dari method post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include "config/database.php";

  $username = input($_POST["username"]);
  $password = input(md5($_POST["password"]));

  $cek_pengguna = "select * from pengguna where username='" . $username . "' and password='" . $password . "' limit 1";
  $hasil_cek = mysqli_query($kon, $cek_pengguna);
  $jumlah = mysqli_num_rows($hasil_cek);
  $row = mysqli_fetch_assoc($hasil_cek);

  if ($jumlah > 0) {
    if ($row["status"] == 1) {
      $_SESSION["id_pengguna"] = $row["id_pengguna"];
      $_SESSION["kode_pengguna"] = $row["kode_pengguna"];
      $_SESSION["nama_pengguna"] = $row["nama_pengguna"];
      $_SESSION["username"] = $row["username"];
      $_SESSION["level"] = $row["level"];

      $id_pengguna = $row["id_pengguna"];
      $waktu = date("Y-m-d h:i:s");
      $log_aktifitas = "Login";


      mysqli_query($kon, "INSERT into log_aktivitas (waktu,aktivitas,id_pengguna) values ('$waktu','$aktivitas',$id_pengguna)");


      header("Location:index.php?page=dashboard");
    } else {
      $pesan = "<div class='alert alert-warning'><strong>Gagal!</strong> Status pengguna tidak aktif.</div>";
    }
  } else {
    $pesan = "<div class='alert alert-danger'><strong>Error!</strong> Username dan password salah.</div>";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>KASIR</title>

  <!-- Custom fonts for this template-->
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="assets/font/font.css" rel="stylesheet" type="text/css">
  <link href='page/aplikasi/logo/logokasir.png' rel='shortcut icon'>

  <!-- Custom styles for this template-->
  <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
  <style>
/* Importing fonts from Google */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

/* Reseting */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #ecf0f3;
}

.wrapper {
    max-width: 350px;
    min-height: 500px;
    margin: 80px auto;
    padding: 40px 30px 30px 30px;
    background-color: #ecf0f3;
    border-radius: 15px;
    box-shadow: 13px 13px 20px #cbced1, -13px -13px 20px #fff;
}

.logo {
    width: 80px;
    margin: auto;
}

.logo img {
    width: 100%;
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
    box-shadow: 0px 0px 3px #5f5f5f,
        0px 0px 0px 5px #ecf0f3,
        8px 8px 15px #a7aaa7,
        -8px -8px 15px #fff;
}

.wrapper .name {
    font-weight: 600;
    font-size: 1.4rem;
    letter-spacing: 1.3px;
    padding-left: 10px;
    color: #555;
}

.wrapper .form-field input {
    width: 100%;
    display: block;
    border: none;
    outline: none;
    background: none;
    font-size: 1.2rem;
    color: #666;
    padding: 10px 15px 10px 10px;
    /* border: 1px solid red; */
}

.wrapper .form-field {
    padding-left: 10px;
    margin-bottom: 20px;
    border-radius: 20px;
    box-shadow: inset 8px 8px 8px #cbced1, inset -8px -8px 8px #fff;
}

.wrapper .form-field .fas {
    color: #555;
}

.wrapper .btn {
    box-shadow: none;
    width: 100%;
    height: 40px;
    background-color: #03A9F4;
    color: #fff;
    border-radius: 25px;
    box-shadow: 3px 3px 3px #b1b1b1,
        -3px -3px 3px #fff;
    letter-spacing: 1.3px;
}

.wrapper .btn:hover {
    background-color: #039BE5;
}

.wrapper a {
    text-decoration: none;
    font-size: 0.8rem;
    color: #03A9F4;
}

.wrapper a:hover {
    color: #039BE5;
}

@media(max-width: 380px) {
    .wrapper {
        margin: 30px 20px;
        padding: 40px 15px 15px 15px;
    }
}
  </style>

</head>


  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-6 col-lg-12 col-md-9">

       
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <?php
            include 'config/database.php';
            $hasil = mysqli_query($kon, "select * from profil_aplikasi order by nama_aplikasi desc limit 1");
            $data = mysqli_fetch_array($hasil);
            ?>
           
                <div class="wrapper">
                          <div class="text-center">
                    <img src="page/aplikasi/logo/<?php echo $data['logo']; ?>" id="preview"  class="img-thumbnail">
                    <h1 class="h4 text-gray-900 mb-4"><?php echo strtoupper($data['nama_aplikasi']); ?></h1>
                    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") echo $pesan; ?>
                  </div>
        
                  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="p-3 mt-3">
                    <div class="form-group d-flex align-items-center">
                      <input type="text" class="form-control form-control-user" name="username" placeholder="Masukan Username" required>
                    </div>
                    <div class="form-group d-flex align-items-center">
                      
                      <input type="password" class="form-control form-control-user" name="password" placeholder="Masukan Password" required>
                    </div>

                    <button type="submit" class="btn mt-3">Login</button>

                  </form>
       
    </div>
              
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="assets/js/sb-admin-2.min.js"></script>
  <script src="assets/js/loader.js"></script>

</body>

</html>