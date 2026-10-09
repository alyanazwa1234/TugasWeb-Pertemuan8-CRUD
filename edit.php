
<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/functions.php';

$pdo = Database::getInstance()->getConnection();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    setFlash('error', 'ID produk tidak valid.');
    redirect('index.php');
}

$stmt = $pdo->prepare("SELECT * FROM produk WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    setFlash('error', 'Produk tidak ditemukan.');
    redirect('index.php');
}

$kategori = $pdo->query(
    "SELECT id, nama FROM kategori ORDER BY nama"
)->fetchAll();

$supplier = $pdo->query(
    "SELECT id, nama FROM supplier ORDER BY nama"
)->fetchAll();

$errors = [];
$nama = $product['nama'];
$harga = $product['harga'];
$stok = $product['stok'];
$kategoriId = $product['kategori_id'];
$supplierId = $product['supplier_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $nama = trim($_POST['nama'] ?? '');
    $harga = trim($_POST['harga'] ?? '');
    $stok = trim($_POST['stok'] ?? '');
    $kategoriId = $_POST['kategori_id'] ?? '';
    $supplierId = $_POST['supplier_id'] ?? '';

    if ($nama === '' || mb_strlen($nama) > 150) {
        $errors[] = 'Nama produk wajib diisi, maksimal 150 karakter.';
    }

    if (!is_numeric($harga) || (float) $harga < 0 ||
        !is_finite((float) $harga) || (float) $harga > 9999999999.99) {
        $errors[] = 'Harga harus berupa angka yang valid dan tidak negatif.';
    }

    if (filter_var($stok, FILTER_VALIDATE_INT) === false ||
        (int) $stok < 0) {
        $errors[] = 'Stok harus berupa bilangan bulat nonnegatif.';
    }

    $kategoriValid = false;
    foreach ($kategori as $item) {
        if ((string) $item['id'] === (string) $kategoriId) {
            $kategoriValid = true;
            break;
        }
    }

    $supplierValid = false;
    foreach ($supplier as $item) {
        if ((string) $item['id'] === (string) $supplierId) {
            $supplierValid = true;
            break;
        }
    }

    if (!$kategoriValid) {
        $errors[] = 'Pilih kategori yang valid.';
    }

    if (!$supplierValid) {
        $errors[] = 'Pilih supplier yang valid.';
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                "UPDATE produk
                 SET nama = :nama,
                     harga = :harga,
                     stok = :stok,
                     kategori_id = :kategori_id,
                     supplier_id = :supplier_id
                 WHERE id = :id"
            );

            $stmt->execute([
                'nama' => $nama,
                'harga' => $harga,
                'stok' => $stok,
                'kategori_id' => $kategoriId,
                'supplier_id' => $supplierId,
                'id' => $id,
            ]);

            setFlash('success', 'Produk berhasil diperbarui.');
            redirect('index.php');
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $errors[] = 'Produk gagal diperbarui. Periksa data database.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="form-container">
    <a class="back-link" href="index.php">← Kembali ke daftar produk</a>

    <section class="panel form-panel">
        <p class="eyebrow">DATA INVENTARIS</p>
        <h1>Edit Produk</h1>
        <p class="subtitle">Perbarui informasi produk yang dipilih.</p>

        <?php foreach ($errors as $error): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endforeach; ?>

        <form method="POST" action="edit.php?id=<?= e($id) ?>">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">

            <label for="nama">Nama Produk</label>
            <input id="nama" name="nama" maxlength="150" required
                   value="<?= e($nama) ?>">

            <label for="harga">Harga (Rp)</label>
            <input id="harga" name="harga" type="number"
                   min="0" max="9999999999.99" step="0.01" required
                   value="<?= e($harga) ?>">

            <label for="stok">Stok</label>
            <input id="stok" name="stok" type="number"
                   min="0" step="1" required value="<?= e($stok) ?>">

            <label for="kategori_id">Kategori</label>
            <select id="kategori_id" name="kategori_id" required>
                <?php foreach ($kategori as $item): ?>
                    <option value="<?= e($item['id']) ?>"
                        <?= (string) $kategoriId === (string) $item['id']
                            ? 'selected' : '' ?>>
                        <?= e($item['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="supplier_id">Supplier</label>
            <select id="supplier_id" name="supplier_id" required>
                <?php foreach ($supplier as $item): ?>
                    <option value="<?= e($item['id']) ?>"
                        <?= (string) $supplierId === (string) $item['id']
                            ? 'selected' : '' ?>>
                        <?= e($item['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button class="btn btn-primary btn-full" type="submit">
                Simpan Perubahan
            </button>
        </form>
    </section>
</main>
</body>
</html>