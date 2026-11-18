<?php
require_once __DIR__ . '/../database.php';
if (is_admin()) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = db()->prepare("SELECT id, username, password_hash FROM admins WHERE username = ?");
    $stmt->execute([trim($_POST['username'] ?? '')]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($_POST['password'] ?? '', $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['username'];
        header('Location: dashboard.php'); exit;
    }
    $error = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
}
?>
<!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Đăng nhập quản trị</title><link rel="stylesheet" href="../assets/css/style.css"></head><body class="admin-body">
<form class="login-card" method="post"><a href="../index.php">← Về website</a><h1>Quản trị viên</h1><p>Đăng nhập để quản lý nội dung bảo tàng.</p>
<?php if ($error): ?><p class="form-error"><?= e($error) ?></p><?php endif; ?>
<label>Tên đăng nhập<input name="username" required autocomplete="username"></label>
<label>Mật khẩu<input type="password" name="password" required autocomplete="current-password"></label>
<button class="btn btn-red" type="submit">Đăng nhập</button>
<p class="notice">Tài khoản mẫu sau khi import SQL: admin / Admin@12345. Hãy đổi mật khẩu ngay sau lần đăng nhập đầu tiên và không dùng mật khẩu mẫu trên hosting công khai.</p>
</form></body></html>
