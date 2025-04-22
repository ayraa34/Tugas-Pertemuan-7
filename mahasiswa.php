<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<a href="index.php" class="btn btn-secondary">Kembali</a>
<body class="container mt-5">

<h2 class="mb-4">Form Input Mahasiswa</h2>

<?php
// Proses Hapus
if (isset($_GET['hapus'])) {
    $npm = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE npm = '$npm'");
    header('Location: mahasiswa.php');
}

// Ambil Data untuk Edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $npm = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE npm = '$npm'");
    $edit_data = mysqli_fetch_assoc($result);
}

// Proses Simpan / Update
if (isset($_POST['simpan'])) {
    $npm = $_POST['npm'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $alamat = $_POST['alamat'];

    if (isset($_POST['edit_mode'])) {
        mysqli_query($conn, "UPDATE mahasiswa SET nama='$nama', jurusan='$jurusan', alamat='$alamat' WHERE npm='$npm'");
    } else {
        mysqli_query($conn, "INSERT INTO mahasiswa VALUES('$npm','$nama','$jurusan','$alamat')");
    }
    header('Location: mahasiswa.php');
}
?>

<form method="post" class="mb-5">
    <?php if ($edit_data): ?>
        <input type="hidden" name="edit_mode" value="true">
    <?php endif; ?>
    <div class="mb-3">
        <input type="text" name="npm" class="form-control" placeholder="NPM" required value="<?= $edit_data['npm'] ?? '' ?>" <?= $edit_data ? 'readonly' : '' ?>>
    </div>
    <div class="mb-3">
        <input type="text" name="nama" class="form-control" placeholder="Nama" required value="<?= $edit_data['nama'] ?? '' ?>">
    </div>
    <div class="mb-3">
        <select name="jurusan" class="form-select">
            <option value="Teknik Informatika" <?= (isset($edit_data) && $edit_data['jurusan'] == 'Teknik Informatika') ? 'selected' : '' ?>>Teknik Informatika</option>
            <option value="Sistem Operasi" <?= (isset($edit_data) && $edit_data['jurusan'] == 'Sistem Operasi') ? 'selected' : '' ?>>Sistem Operasi</option>
        </select>
    </div>
    <div class="mb-3">
        <textarea name="alamat" class="form-control" placeholder="Alamat"><?= $edit_data['alamat'] ?? '' ?></textarea>
    </div>
    <button type="submit" name="simpan" class="btn btn-primary"><?= $edit_data ? 'Update' : 'Simpan' ?></button>
    <a href="mahasiswa.php" class="btn btn-secondary">Reset</a>
</form>

<h3>Data Mahasiswa</h3>
<table class="table table-bordered">
<thead class="table-dark">
<tr>
    <th>NPM</th>
    <th>Nama</th>
    <th>Jurusan</th>
    <th>Alamat</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>
<?php
$data = mysqli_query($conn, "SELECT * FROM mahasiswa");
while ($row = mysqli_fetch_assoc($data)) {
    echo "<tr>
            <td>{$row['npm']}</td>
            <td>{$row['nama']}</td>
            <td>{$row['jurusan']}</td>
            <td>{$row['alamat']}</td>
            <td>
                <a href='mahasiswa.php?edit={$row['npm']}' class='btn btn-warning btn-sm'>Edit</a>
                <a href='mahasiswa.php?hapus={$row['npm']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
            </td>
          </tr>";
}
?>
</tbody>
</table>

</body>
</html>
