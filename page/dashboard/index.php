
<!-- Begin Page Content -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
    <style>
        .card {
    background-color: #fff;
    border-radius: 15px;
    border: none;
    right: -50px;
    position: relative;
    margin-bottom: 30px;    
    box-shadow: 0 0.46875rem 2.1875rem rgba(90,97,105,0.1), 0 0.9375rem 1.40625rem rgba(90,97,105,0.1), 0 0.25rem 0.53125rem rgba(90,97,105,0.12), 0 0.125rem 0.1875rem rgba(90,97,105,0.1);
}
.l-bg-cherry {
    background: linear-gradient(to right, #493240, #f09) !important;
    right: -5px;
    color: #fff;
}

.l-bg-blue-dark {
    background: linear-gradient(to right, #373b44, #4286f4) !important;
    right: -20px;
    color: #fff;
}

.l-bg-green-dark {
    background: linear-gradient(to right, #0a504a, #38ef7d) !important;
    right: -40px;
    color: #fff;
}

.l-bg-orange-dark {
    background: linear-gradient(to right, #a86008, #ffba56) !important;
    right: -80px;
    color: #fff;
}

.card .card-statistic-3 .card-icon-large .fas, .card .card-statistic-3 .card-icon-large .far, .card .card-statistic-3 .card-icon-large .fab, .card .card-statistic-3 .card-icon-large .fal {
    font-size: 110px;
}

.card .card-statistic-3 .card-icon {
    text-align: center;
    line-height: 50px;
    margin-left: 15px;
    color: #000;
    position: absolute;
    right: 20px;
    top: 20px;
    opacity: 0.1;
}

.l-bg-cyan {
    background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
    color: #fff;
}

.l-bg-green {
    background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
    color: #fff;
}

.l-bg-orange {
    background: linear-gradient(to right, #f9900e, #ffba56) !important;
    color: #fff;
}

.l-bg-cyan {
    background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
    color: #fff;
}
    </style>
</head>

<div class="container">
    <div class="row ">
    <?php 
        include 'config/database.php';
        $hari_ini=date('Y-m-d');
        
        $kasir=0;
        if ($_SESSION["level"]=="Kasir"){
            $kasir=$_SESSION["id_pengguna"];
            $sql="select SUM(harga*qty) as hari_ini from penjualan p inner join detail_penjualan d on d.no_invoice=p.no_invoice where p.id_kasir=$kasir and date(tanggal)='$hari_ini'";
         }else {
            $sql="select SUM(harga*qty) as hari_ini from penjualan p inner join detail_penjualan d on d.no_invoice=p.no_invoice where date(tanggal)='$hari_ini'";
        }

        $hasil=mysqli_query($kon,$sql);
        $data = mysqli_fetch_array($hasil);       
    ?>
        <div class="card-deck col-sm-3">
            <div class="card l-bg-cherry">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0 font-weight-bold">Penjualan hari ini</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                        <div class="h4 mb-0 font-weight-bold ">Rp <?php  echo number_format($data['hari_ini'],0,',','.');?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
        include 'config/database.php';
        $bulan_ini=date('m');
        $tahun_ini=date('Y');
        //Perintah sql untuk menampilkan semua data pada tabel jurusan
        $kasir=0;
        if ($_SESSION["level"]=="Kasir"){
            $kasir=$_SESSION["id_pengguna"];
            $sql="select SUM(harga*qty) as bulan_ini from penjualan p inner join detail_penjualan d on d.no_invoice=p.no_invoice where p.id_kasir=$kasir and month(tanggal)='$bulan_ini' and year(tanggal)='$tahun_ini' ";
        }else {
        $sql="select SUM(harga*qty) as bulan_ini from penjualan p inner join detail_penjualan d on d.no_invoice=p.no_invoice where month(tanggal)='$bulan_ini' and year(tanggal)='$tahun_ini' ";
        }
        $hasil=mysqli_query($kon,$sql);
        $data = mysqli_fetch_array($hasil);
    ?>
        <div class="card-deck col-sm-3">
            <div class="card l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0 font-weight-bold">Penjualan bulan ini</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                        <div class="h4 mb-0 font-weight-bold">Rp <?php  echo number_format($data['bulan_ini'],0,',','.');?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
        include 'config/database.php';
        $tahun_ini=date('Y');
        
        if ($_SESSION["level"]=="Kasir"){
            $kasir=$_SESSION["id_pengguna"];
            $sql="select SUM(harga*qty) as tahun_ini from penjualan p inner join detail_penjualan d on d.no_invoice=p.no_invoice where p.id_kasir=$kasir and year(tanggal)='$tahun_ini'";
        }else{
            $sql="select SUM(harga*qty) as tahun_ini from penjualan p inner join detail_penjualan d on d.no_invoice=p.no_invoice where year(tanggal)='$tahun_ini'";
        }

        $hasil=mysqli_query($kon,$sql);
        $data = mysqli_fetch_array($hasil);
    ?>
        <div class="card-deck col-sm-3">
            <div class="card l-bg-green-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0 font-weight-bold">Penjualan tahun ini</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                        <div class="h4 mb-0 font-weight-bold ">Rp <?php  echo number_format($data['tahun_ini'],0,',','.');?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
        include 'config/database.php';
       
        if ($_SESSION["level"]=="Kasir"){
            $kasir=$_SESSION["id_pengguna"];
            $sql="select SUM(harga*qty) as selama_ini from penjualan p inner join detail_penjualan d on d.no_invoice=p.no_invoice where p.id_kasir=$kasir";

        }else {
            $sql="select SUM(harga*qty) as selama_ini from penjualan p inner join detail_penjualan d on d.no_invoice=p.no_invoice";

        }

        $hasil=mysqli_query($kon,$sql);
        $data = mysqli_fetch_array($hasil);
    ?>
        <div class="card-deck col-sm-3">
            <div class="card l-bg-orange-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0 font-weight-bold">Penjualan selama ini</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                        <div class="h4 mb-0 font-weight-bold">Rp <?php  echo number_format($data['selama_ini'],0,',','.');?></div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</div>

