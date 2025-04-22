<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>KRS Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<a href="index.php" class="btn btn-secondary">Kembali</a>
<body class="container mt-5">
<h2 class="mb-4">Form KRS</h2>

<?php
// Hapus Data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM krs WHERE id = $id");
    header("Location: krs.php");
}

// Simpan Data
if (isset($_POST['simpan'])) {
    $npm = $_POST['npm'];
    $kodemk = $_POST['kodemk'];

    if (isset($_POST['id_edit'])) {
        // Proses Update
        $id_edit = $_POST['id_edit'];
        mysqli_query($conn, "UPDATE krs SET mahasiswa_npm='$npm', matakuliah_kodemk='$kodemk' WHERE id=$id_edit");
    } else {
        // Proses Tambah
        mysqli_query($conn, "INSERT INTO krs (mahasiswa_npm, matakuliah_kodemk) VALUES('$npm', '$kodemk')");
    }
    header("Location: krs.php");
}

// Data untuk edit jika ada
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit = mysqli_query($conn, "SELECT * FROM krs WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($edit);
}
?>

<form method="post" class="mb-5">
    <?php if ($edit_data): ?>
        <input type="hidden" name="id_edit" value="<?= $edit_data['id'] ?>">
    <?php endif; ?>
    <div class="mb-3">
        <select name="npm" class="form-select" required>
            <option value="">-- Pilih Mahasiswa --</option>
            <?php
            $mhs = mysqli_query($conn, "SELECT * FROM mahasiswa");
            while ($row = mysqli_fetch_assoc($mhs)) {
                $selected = ($edit_data && $edit_data['mahasiswa_npm'] == $row['npm']) ? 'selected' : '';
                echo "<option value='{$row['npm']}' $selected>{$row['npm']} - {$row['nama']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="mb-3">
        <select name="kodemk" class="form-select" required>
            <option value="">-- Pilih Mata Kuliah --</option>
            <?php
            $mk = mysqli_query($conn, "SELECT * FROM matakuliah");
            while ($row = mysqli_fetch_assoc($mk)) {
                $selected = ($edit_data && $edit_data['matakuliah_kodemk'] == $row['kodemk']) ? 'selected' : '';
                echo "<option value='{$row['kodemk']}' $selected>{$row['kodemk']} - {$row['nama']}</option>";
            }
            ?>
        </select>
    </div>
    <button type="submit" name="simpan" class="btn btn-primary">
        <?= $edit_data ? 'Update' : 'Simpan' ?>
    </button>
    <a href="krs.php" class="btn btn-secondary">Reset</a>
</form>

<h3>Data KRS</h3>
<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>Mata Kuliah</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $no = 1;
    $data = mysqli_query($conn, "SELECT k.id, m.nama AS nama_mhs, mk.nama AS nama_mk, mk.jumlah_sks
                                 FROM krs k
                                 JOIN mahasiswa m ON k.mahasiswa_npm = m.npm
                                 JOIN matakuliah mk ON k.matakuliah_kodemk = mk.kodemk");

    while ($row = mysqli_fetch_assoc($data)) {
        echo "<tr>
                <td>{$no}</td>
                <td>{$row['nama_mhs']}</td>
                <td>{$row['nama_mk']}</td>
                <td><span class='highlight'>{$row['nama_mhs']}</span> Mengambil Mata Kuliah <span class='highlight'>{$row['nama_mk']} ({$row['jumlah_sks']} SKS)</span></td>
                <td>
                    <a href='krs.php?edit={$row['id']}' class='btn btn-sm btn-warning'>Edit</a>
                    <a href='krs.php?hapus={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
                </td>
              </tr>";
        $no++;
    }
    ?>
    </tbody>
</table>

</body>
</html>
