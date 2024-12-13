<?php
require 'koneksi.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['editMode'])) {
        header('Location: edit.php?id=' . $_POST['id']);
        exit;
    } elseif (isset($_POST['delete'])) {
        // Handle delete
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            $successMessage = "Buku berhasil dihapus!";
        } else {
            $errorMessage = "Gagal menghapus buku.";
        }
    } else {
        // Handle add
        $title = $_POST['title'];
        $author = $_POST['author'];
        $year = $_POST['year'];

        $stmt = $conn->prepare("INSERT INTO books (title, author, year) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $title, $author, $year);

        if ($stmt->execute()) {
            $successMessage = "Buku berhasil ditambahkan!";
        } else {
            $errorMessage = "Gagal menambahkan buku.";
        }
    }
}

// Fetch books
$books = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penyimpanan Data Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Penyimpanan Data Buku</h1>

    <?php if (isset($successMessage)): ?>
        <div class="message success"> <?= $successMessage ?> </div>
    <?php endif; ?>
    <?php if (isset($errorMessage)): ?>
        <div class="message error"> <?= $errorMessage ?> </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div>
            <label for="title">Judul Buku:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div>
            <label for="author">Penulis:</label>
            <input type="text" id="author" name="author" required>
        </div>
        <div>
            <label for="year">Tahun Terbit:</label>
            <input type="number" id="year" name="year" required>
        </div>
        <button type="submit">Tambah Buku</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Tahun Terbit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($books->num_rows > 0): ?>
                <?php $no = 1; while ($row = $books->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['author']) ?></td>
                        <td><?= htmlspecialchars($row['year']) ?></td>
                        <td>
                            <form method="POST" action="" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" name="editMode">Edit</button>
                            </form>
                            <form method="POST" action="" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" name="delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada data buku</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>