
<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/functions.php';

// Koneksi database
$pdo = Database::getInstance()->getConnection();

// Ambil kata kunci pencarian
$keyword = trim($_GET['search'] ?? '');

// Ambil data produk, kategori, dan supplier
$sql = "
    SELECT
        p.id,
        p.nama,
        p.harga,
        p.stok,
        k.nama AS nama_kategori,
        s.nama AS nama_supplier
    FROM produk p
    LEFT JOIN kategori k ON p.kategori_id = k.id
    LEFT JOIN supplier s ON p.supplier_id = s.id
";

$params = [];

if ($keyword !== '') {
    $sql .= "
        WHERE p.nama LIKE ?
        OR k.nama LIKE ?
        OR s.nama LIKE ?
    ";

    $searchTerm = "%{$keyword}%";
    $params = [$searchTerm, $searchTerm, $searchTerm];
}

$sql .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Skincare</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">

    <header class="page-header">
        <h1>Inventaris Skincare</h1>
        <p>Kelola data produk skincare, kategori, dan supplier.</p>
    </header>

    <?php
    $flash = getFlash();

    if ($flash !== null):
    ?>
        <div class="alert <?= e($flash['type']) ?>">
            <?= e($flash['message']) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert success">
            <?= e($_GET['success']) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert error">
            <?= e($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <form method="GET" action="index.php" class="search-form">
        <input
            type="text"
            name="search"
            placeholder="Cari nama skincare, kategori, atau supplier..."
            value="<?= e($keyword) ?>"
        >
        <button type="submit">Cari</button>
    </form>

    <div class="action-bar">
        <a href="create.php" class="btn-primary">
            + Tambah Produk Skincare
        </a>
    </div>

    <h2>Daftar Produk Skincare</h2>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Supplier</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (count($products) > 0): ?>

                    <?php foreach ($products as $index => $product): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>

                            <td><?= e($product['nama']) ?></td>

                            <td>
                                <?= e($product['nama_kategori'] ?? '-') ?>
                            </td>

                            <td>
                                <?= e($product['nama_supplier'] ?? '-') ?>
                            </td>

                            <td>
                                Rp <?= number_format(
                                    (float) $product['harga'],
                                    0,
                                    ',',
                                    '.'
                                ) ?>
                            </td>

                            <td><?= e($product['stok']) ?></td>

                            <td class="action-buttons">
                                <a
                                    href="edit.php?id=<?= urlencode(
                                        (string) $product['id']
                                    ) ?>"
                                    class="btn-edit"
                                >
                                    Edit
                                </a>

                                <form
                                    action="delete.php"
                                    method="POST"
                                    onsubmit="return confirm(
                                        'Yakin ingin menghapus produk ini?'
                                    );"
                                >
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= e($product['id']) ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= e(csrfToken()) ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-delete"
                                    >
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty-message">
                            <?php if ($keyword !== ''): ?>
                                Produk skincare tidak ditemukan.
                            <?php else: ?>
                                Belum ada produk skincare.
                                Silakan tambahkan produk terlebih dahulu.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p class="total-products">
        Total produk:
        <strong><?= count($products) ?></strong>
    </p>

</div>

</body>
</html>
```
