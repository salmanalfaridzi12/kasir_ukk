<?php
$tanggal = mktime(date('m'), date("d"), date('Y'));
date_default_timezone_set("Asia/Jakarta");
$jam = date("H:i");

require('../../../assets/plugin/fpdf/fpdf.php');
$pdf = new FPDF('L', 'mm', 'Letter');

include '../../../config/database.php';
$query = mysqli_query($kon, "select * from profil_aplikasi order by nama_aplikasi desc limit 1");
$row = mysqli_fetch_array($query);


$pdf->AddPage();
$pdf->Image('../../../page/aplikasi/logo/' . $row['logo'], 15, 5, 30, 30);
$pdf->SetFont('Arial', 'B', 21);
$pdf->Cell(0, 7, strtoupper($row['nama_aplikasi']), 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, $row['alamat'] . ', Telp ' . $row['no_telp'], 0, 1, 'C');
$pdf->Cell(0, 7, $row['website'], 0, 1, 'C');
$pdf->Cell(0, 7, ' ' . date("d/m/Y", ($tanggal)) . ' ' . $jam, 0, 1, 'R');
$pdf->Cell(10, 7, '', 0, 1);

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 6, 'Laporan Data Produk ', 0, 1, 'C');

$pdf->Cell(10, 3, '', 0, 1);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(8, 6, 'No', 1, 0, 'C');
$pdf->Cell(15, 6, 'Kode', 1, 0, 'C');
$pdf->Cell(60, 6, 'Produk', 1, 0, 'C');
$pdf->Cell(15, 6, 'Satuan', 1, 0, 'C');
$pdf->Cell(40, 6, 'Kategori', 1, 0, 'C');
$pdf->Cell(40, 6, 'Supplier', 1, 0, 'C');
$pdf->Cell(15, 6, 'Stok', 1, 0, 'C');
$pdf->Cell(30, 6, 'Harga Beli', 1, 0, 'C');
$pdf->Cell(30, 6, 'Harga Jual', 1, 1, 'C');

$pdf->SetFont('Arial', '', 10);


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
$no = 1;
$total = 0;
$total_modal = 0;
$total_jual = 0;
$total_stok_produk = 0;
$cek = mysqli_num_rows($query_mysqli);
//Menampilkan data dengan perulangan while
while ($data = mysqli_fetch_array($query_mysqli)) {
    $stok_produk = $data['stok_produk'];
    $harga_beli = $data['harga_beli'];
    $harga_jual = $data['harga_jual'];

    $total_modal += $data['harga_beli'] * $stok_produk;
    $total_jual += $data['harga_jual'] * $stok_produk;
    $total_stok_produk += $data['stok_produk'];

    $pdf->Cell(8, 6, $no, 1, 0, 'C');
    $pdf->Cell(15, 6, $data['kode_produk'], 1, 0, 'C');
    $pdf->Cell(60, 6, substr($data['nama_produk'], 0, 28), 1, 0);
    $pdf->Cell(15, 6, $data['satuan'], 1, 0, 'C');
    $pdf->Cell(40, 6, $data['nama_kt_produk'], 1, 0);
    $pdf->Cell(40, 6, $data['nama_supplier'], 1, 0);
    $pdf->Cell(15, 6, $data['stok_produk'], 1, 0, 'C');
    $pdf->Cell(30, 6, 'Rp. ' . number_format($harga_beli, 0, ',', '.'), 1, 0);
    $pdf->Cell(30, 6, 'Rp. ' . number_format($harga_jual, 0, ',', '.'), 1, 1);
    $no++;
}
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(178, 6, 'Total', 1, 0, 'C');
$pdf->Cell(15, 6, '' . number_format($total_stok_produk, 0, ',', '.'), 1, 0, 'C');
$pdf->Cell(30, 6, 'Rp.' . number_format($total_modal, 0, ',', '.'), 1, 0);
$pdf->Cell(30, 6, 'Rp.' . number_format($total_jual, 0, ',', '.'), 1, 1);

$pdf->Output();
