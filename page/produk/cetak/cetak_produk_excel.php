<?php
$tanggal = date('l, d-m-Y');
date_default_timezone_set("Asia/Jakarta");
$jam = date("H:i");

//Koneksi database
include '../../../config/database.php';
//Mengambil nama aplikasi
$query = mysqli_query($kon, "select nama_aplikasi from profil_aplikasi order by nama_aplikasi desc limit 1");
$row = mysqli_fetch_array($query);

//Membuat file format excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=LAPORAN EXCEL DATA PRODUK " . strtoupper($row['nama_aplikasi']) . ".xls");
?>
<h2>
    <center>LAPORAN EXCEL DATA PRODUK <?php echo strtoupper($row['nama_aplikasi']); ?></center>
</h2>
<p align="right"><?php echo "$tanggal $jam"; ?></p>

<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Produk</th>
            <th>Satuan</th>
            <th>Kategori</th>
            <th>Supplier</th>
            <th>Stok</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>

        </tr>
    </thead>
    <tbody>
        <?php

        $kt_produk = @$_POST['kt_produk'];
        $cetak_semua = @$_POST['cetak_semua'];
        $cetak = @$_POST['cetak'];

        // jika tombol Cetak semua di klik
        if ($cetak_semua) {
            $query_mysqli = mysqli_query($kon, "SELECT p.kode_produk, p.nama_produk, p.satuan, p.stok_produk, p.harga_beli, p.harga_jual, p.kategori_produk, p.supplier, k.id_kt_produk, k.nama_kt_produk, s.id_supplier, s.nama_supplier from produk p, kategori_produk k, supplier s WHERE k.id_kt_produk=p.kategori_produk and s.id_supplier=p.supplier order by id_produk desc") or die(mysqli_error());
        }

        if ($cetak) {

            if ($kt_produk != "") {
                // query mysql untuk mencari berdasarkan nama negara. .
                $query_mysqli = mysqli_query($kon, "SELECT p.kode_produk, p.nama_produk, p.satuan, p.stok_produk, p.harga_beli, p.harga_jual, p.kategori_produk, p.supplier, k.id_kt_produk, k.nama_kt_produk, s.id_supplier, s.nama_supplier from produk p, kategori_produk k, supplier s WHERE k.id_kt_produk=p.kategori_produk and s.id_supplier=p.supplier and k.nama_kt_produk like '%$kt_produk%' ") or die(mysqil_error());
            } else {
                $query_mysqli = mysqli_query($kon, "SELECT p.kode_produk, p.nama_produk, p.satuan, p.stok_produk, p.harga_beli, p.harga_jual, p.kategori_produk, p.supplier, k.id_kt_produk, k.nama_kt_produk, s.id_supplier, s.nama_supplier from produk p, kategori_produk k, supplier s WHERE k.id_kt_produk=p.kategori_produk and s.id_supplier=p.supplier order by id_produk desc") or die(mysqli_error());
            }
        }
        // perintah sql
        $no = 0;
        $total = 0;
        $total_modal = 0;
        $total_jual = 0;
        $total_stok_produk = 0;

        //Menampilkan data dengan perulangan while
        while ($data = mysqli_fetch_array($query_mysqli)) :
            $no++;
            $stok_produk = $data['stok_produk'];
            $harga_beli = $data['harga_beli'];
            $harga_jual = $data['harga_jual'];

            $total_modal += $data['harga_beli'] * $stok_produk;
            $total_jual += $data['harga_jual'] * $stok_produk;
            $total_stok_produk += $data['stok_produk'];
        ?>
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $data['kode_produk']; ?></td>
                <td><?php echo $data['nama_produk']; ?></td>
                <td><?php echo $data['satuan']; ?></td>
                <td><?php echo $data['nama_kt_produk']; ?></td>
                <td><?php echo $data['nama_supplier']; ?></td>
                <td><?php echo $data['stok_produk']; ?></td>
                <td>Rp. <?php echo number_format($harga_beli, 0, ',', '.'); ?></td>
                <td>Rp. <?php echo number_format($harga_jual, 0, ',', '.'); ?></td>
            </tr>
            <!-- bagian akhir (penutup) while -->
        <?php endwhile; ?>
        <tr>
            <td colspan="6"><strong>Total</strong></td>
            <td><strong><?php echo number_format($total_stok_produk, 0, ',', '.'); ?></strong></td>
            <td><strong>Rp. <?php echo number_format($total_modal, 0, ',', '.'); ?></strong></td>
            <td><strong>Rp. <?php echo number_format($total_jual, 0, ',', '.'); ?></strong></td>
        </tr>
    </tbody>
</table>