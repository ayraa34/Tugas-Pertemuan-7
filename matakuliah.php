<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Mata Kuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<a href="index.php" class="btn btn-secondary">Kembali</a>
<body class="container mt-5">

<h2 class="mb-4">Form Input Mata Kuliah</h2>

<?php
// Hapus data
if (isset($_GET['hapus'])) {
    $kodemk = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM matakuliah WHERE kodemk = '$kodemk'");
    header('Location: matakuliah.php');
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $kodemk = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM matakuliah WHERE kodemk = '$kodemk'");
    $edit_data = mysqli_fetch_assoc($result);
}

// Simpan atau Update data
if (isset($_POST['simpan'])) {
    $kodemk = $_POST['kodemk'];
    $nama = $_POST['nama'];
    $jumlah_sks = $_POST['jumlah_sks'];

    if (isset($_POST['edit_mode'])) {
        mysqli_query($conn, "UPDATE matakuliah SET nama='$nama', jumlah_sks=$jumlah_sks WHERE kodemk='$kodemk'");
    } else {
        mysqli_query($conn, "INSERT INTO matakuliah VALUES('$kodemk', '$nama', $jumlah_sks)");
    }
    header('Location: matakuliah.php');
}
?>

<form method="post" class="mb-5">
    <?php if ($edit_data): ?>
        <input type="hidden" name="edit_mode" value="true">
    <?php endif; ?>
    <div class="mb-3">
        <input type="text" name="kodemk" class="form-control" placeholder="Kode MK" required value="<?= $edit_data['kodemk'] ?? '' ?>" <?= $edit_data ? 'readonly' : '' ?>>
    </div>
    <div class="mb-3">
        <input type="text" name="nama" class="form-control" placeholder="Nama Mata Kuliah" required value="<?= $edit_data['nama'] ?? '' ?>">
    </div>
    <div class="mb-3">
        <input type="number" name="jumlah_sks" class="form-control" placeholder="Jumlah SKS" required value="<?= $edit_data['jumlah_sks'] ?? '' ?>">
    </div>
    <button type="submit" name="simpan" class="btn btn-primary"><?= $edit_data ? 'Update' : 'Simpan' ?></button>
    <a href="matakuliah.php" class="btn btn-secondary">Reset</a>
</form>

<h3>Data Mata Kuliah</h3>
<table class="table table-bordered">
<thead class="table-dark">
<tr>
    <th>Kode MK</th>
    <th>Nama</th>
    <th>SKS</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php
$data = mysqli_query($conn, "SELECT * FROM matakuliah");
while ($row = mysqli_fetch_assoc($data)) {
    echo "<tr>
            <td>{$row['kodemk']}</td>
            <td>{$row['nama']}</td>
            <td>{$row['jumlah_sks']}</td>
            <td>
                <a href='matakuliah.php?edit={$row['kodemk']}' class='btn btn-warning btn-sm'>Edit</a>
                <a href='matakuliah.php?hapus={$row['kodemk']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
            </td>
          </tr>";
}
?>
</tbody>
</table>

</body>
</html>
