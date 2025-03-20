<?php
include 'koneksi.php'; // Pastikan koneksi database tersedia
session_start();

// Pastikan noapk tersedia di sesi
$noapk = $_SESSION['noapk'] ?? '';

// Query untuk mengambil total data dari tbuku berdasarkan noapk
$totalJudul = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(*) AS total FROM (SELECT DISTINCT judul FROM tbuku WHERE noapk = '$noapk') AS unique_titles"))['total'];
$totalPengarang = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(*) AS total FROM (SELECT DISTINCT pengarang FROM tbuku WHERE noapk = '$noapk') AS unique_authors"))['total'];
$totalJumlahBuku = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(id) AS total FROM tbuku WHERE noapk = '$noapk'"))['total'];
$totalSubyek = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(DISTINCT subyek) AS total FROM rsubyek WHERE noapk = '$noapk'"))['total'];
$totalDikembalikan = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(*) AS total FROM tpinbuku WHERE tglrealkembali IS NOT NULL AND noapk = '$noapk'"))['total'];
$totalBelumDikembalikan = mysqli_fetch_assoc(mysqli_query($koneksidb, "SELECT COUNT(*) AS total FROM tpinbuku WHERE tglrealkembali IS NULL AND noapk = '$noapk'"))['total'];
?>

<style>
    body {
        background-color: #f8f9fa;
    }

    .container {
        max-width: 1200px;
        margin: auto;
    }

    .card {
        color: white;
        padding: 60px;
        text-align: center;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    }

    .blue { background-color: #007bff; }
    .yellow { background-color: #ffc107; color: #333; }
    .red { background-color: #dc3545; }
    .green { background-color: #28a745; }

    h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    p {
        font-size: 1.2rem;
        font-weight: 500;
        margin: 0;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }

    .col-md-4 {
        flex: 1 1 calc(33.333% - 20px);
        max-width: calc(33.333% - 20px);
    }

    @media (max-width: 768px) {
        .col-md-4 {
            flex: 1 1 calc(50% - 20px);
            max-width: calc(50% - 20px);
        }
    }

    @media (max-width: 576px) {
        .col-md-4 {
            flex: 1 1 100%;
            max-width: 100%;
        }
    }
</style>

<div class="container mt-5">
    <h2 class="text-center mb-4">📚 Dashboard Perpustakaan 📚</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card blue">
                <h2><?= $totalJudul ?></h2>
                <p>Total Judul Buku</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card yellow">
                <h2><?= $totalDikembalikan ?></h2>
                <p>Total Buku Dikembalikan</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card red">
                <h2><?= $totalBelumDikembalikan ?></h2>
                <p>Total Buku Belum Kembali</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card green">
                <h2><?= $totalJumlahBuku ?></h2>
                <p>Total Jumlah Buku</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card red">
                <h2><?= $totalPengarang ?></h2>
                <p>Total Pengarang</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card yellow">
                <h2><?= $totalSubyek ?></h2>
                <p>Total Subyek Judul</p>
            </div>
        </div>
    </div>
</div>
