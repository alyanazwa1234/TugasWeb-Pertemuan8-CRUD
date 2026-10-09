```php
<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Metode permintaan tidak diizinkan.');
}

verifyCsrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    setFlash('error', 'ID produk tidak valid.');
    redirect('index.php');
}

try {
    $pdo = Database::getInstance()->getConnection();

    $stmt = $pdo->prepare(
        "DELETE FROM produk WHERE id = :id"
    );

    $stmt->execute(['id' => $id]);

    if ($stmt->rowCount() > 0) {
        setFlash('success', 'Produk berhasil dihapus.');
    } else {
        setFlash('error', 'Produk tidak ditemukan.');
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    setFlash(
        'error',
        'Produk gagal dihapus. Periksa relasi dan koneksi database.'
    );
}

redirect('index.php');

