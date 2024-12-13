<?php
require 'koneksi.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Buku tidak ditemukan");
}

$book = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];

    $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, year = ? WHERE id = ?");
    $stmt->bind_param('ssii', $title, $author, $year, $id);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit;
    } else {
        $errorMessage = "Gagal memperbarui buku.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Edit Buku</h1>

    <?php if (isset($errorMessage)): ?>
        <div class="message error"> <?= $errorMessage ?> </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div>
            <label for="title">Judul Buku:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
        </div>
        <div>
            <label for="author">Penulis:</label>
            <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
        </div>
        <div>
            <label for="year">Tahun Terbit:</label>
            <input type="number" id="year" name="year" value="<?= htmlspecialchars($book['year']) ?>" required>
        </div>
        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>