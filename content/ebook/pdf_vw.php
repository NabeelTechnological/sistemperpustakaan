<?php
session_start();
$host = "esikaterp.id"; // atau sesuai dengan server database kamu
$user = "dsio5127_dsipustaka_mtsn1demak_user"; // username database
$pass = "N3LrsMZ2@A{w"; // password database (kosong jika default di XAMPP)
$dbname = "dsio5127_dsipustaka_mstsn1demak_db"; // ganti dengan nama database kamu

$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Query untuk mengambil data dari database
$sql = "SELECT judul, pengarangnormal, penerbit, nmfile FROM tebook WHERE idebook = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $dataRow = $result->fetch_assoc();

        $judul = $dataRow['judul'];
        $pengarangnormal = $dataRow['pengarangnormal'];
        $penerbit = $dataRow['penerbit'];
        $nmFile = $dataRow['nmfile'];

        // Pastikan file ada di folder
        $filePath = "file/" . rawurlencode($nmFile);

        if (!file_exists($filePath)) {
            die("File tidak ditemukan: " . $filePath);
        }
    } else {
        die("Data tidak ditemukan.");
    }
} else {
    die("Query gagal diproses.");
}
?>

<div class="ebook-container">
    <div class="ebook-header">
        <h2>E-BOOK</h2>
        <p><strong>Judul:</strong> <?= htmlspecialchars($judul); ?></p>
        <p><strong>Pengarang:</strong> <?= htmlspecialchars($pengarangnormal); ?></p>
        <p><strong>Penerbit:</strong> <?= htmlspecialchars($penerbit); ?></p>
        <p><strong>Nama File:</strong> <?= htmlspecialchars($nmFile); ?></p>
    </div>
    <iframe id="pdfViewer" src="<?= $filePath; ?>" width="100%" height="600px"></iframe>
</div>
