<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/auth.php';
require_admin();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    if ($username && $password) {
        try {
            $stmt = $pdo->prepare('INSERT INTO admins (username, password_hash, role, created_at, is_active) VALUES (?, ?, ?, NOW(), 1)');
            $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $role]);
            $message = 'Admin added successfully.';
        } catch (PDOException $e) {
            $message = 'Username already exists.';
        }
    }
}
$admins = $pdo->query('SELECT admin_id, username, role, created_at, is_active FROM admins ORDER BY created_at DESC')->fetchAll();
?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admins</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="assets/style.css" rel="stylesheet"></head><body>
<?php include __DIR__ . '/partials/navbar.php'; ?><div class="container-fluid"><div class="row"><?php include __DIR__ . '/partials/sidebar.php'; ?>
<main class="col-lg-10 p-4"><h1 class="fw-bold">Admins</h1><p class="text-muted mb-4">Manage administrator accounts.</p>
<?php if($message): ?><div class="alert alert-info"><?=h($message)?></div><?php endif; ?>
<div class="card border-0 shadow-sm mb-4"><div class="card-header bg-white"><h5 class="mb-0">Add Admin</h5></div><div class="card-body">
<form method="post" class="row g-3"><input type="hidden" name="action" value="add">
<div class="col-md-4"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
<div class="col-md-4"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
<div class="col-md-3"><label class="form-label">Role</label><select name="role" class="form-select"><option>Staff</option><option>SuperAdmin</option></select></div>
<div class="col-md-1 d-flex align-items-end"><button class="btn btn-primary">Add</button></div>
</form></div></div>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover mb-0">
<thead><tr><th>Admin ID</th><th>Username</th><th>Role</th><th>Created At</th><th>Status</th></tr></thead><tbody>
<?php foreach($admins as $a): ?><tr><td><?=h((string)$a['admin_id'])?></td><td><?=h($a['username'])?></td><td><?=h($a['role'])?></td><td><?=h($a['created_at'])?></td><td><?= $a['is_active']?'<span class="badge text-bg-success">Active</span>':'<span class="badge text-bg-secondary">Inactive</span>' ?></td></tr><?php endforeach; ?>
</tbody></table></div></div></main></div></div></body></html>
