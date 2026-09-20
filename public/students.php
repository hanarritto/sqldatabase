<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/auth.php';
require_admin();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'add') {
            $stmt = $pdo->prepare('INSERT INTO students (student_id, username, face_image, register_date, is_active) VALUES (?, ?, ?, NOW(), ?)');
            $stmt->execute([
                trim($_POST['student_id']),
                trim($_POST['username']),
                trim($_POST['face_image'] ?? ''),
                isset($_POST['is_active']) ? 1 : 0
            ]);
            $message = 'Student added successfully.';
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare('DELETE FROM students WHERE student_id = ?');
            $stmt->execute([$_POST['student_id']]);
            $message = 'Student deleted successfully.';
        }
    } catch (PDOException $e) {
        $error = 'Could not save the student. The Student ID or Username may already exist.';
    }
}

$students = $pdo->query('SELECT * FROM students ORDER BY register_date DESC')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Students - Dorm Access System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="assets/style.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<div class="container-fluid"><div class="row">
<?php include __DIR__ . '/partials/sidebar.php'; ?>
<main class="col-lg-10 p-4">
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h1 class="fw-bold">Students</h1><p class="text-muted">Manage registered student records.</p></div>
</div>

<?php if ($message): ?><div class="alert alert-success"><?= h($message) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= h($error) ?></div><?php endif; ?>

<div class="card border-0 shadow-sm mb-4">
<div class="card-header bg-white"><h5 class="mb-0">Add Student</h5></div>
<div class="card-body">
<form method="post" class="row g-3">
<input type="hidden" name="action" value="add">
<div class="col-md-3"><label class="form-label">Student ID</label><input name="student_id" class="form-control" required></div>
<div class="col-md-3"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
<div class="col-md-4"><label class="form-label">Face Image Filename</label><input name="face_image" class="form-control" placeholder="student.jpg"></div>
<div class="col-md-2 d-flex align-items-end"><div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_active" checked><label class="form-check-label">Active</label></div></div>
<div class="col-12"><button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Student</button></div>
</form>
</div></div>

<div class="card border-0 shadow-sm">
<div class="table-responsive">
<table class="table table-hover align-middle mb-0">
<thead><tr><th>Student ID</th><th>Username</th><th>Face Image</th><th>Register Date</th><th>Status</th><th></th></tr></thead>
<tbody>
<?php foreach ($students as $s): ?>
<tr>
<td><?= h($s['student_id']) ?></td><td><?= h($s['username']) ?></td><td><?= h($s['face_image']) ?></td>
<td><?= h($s['register_date']) ?></td><td><?= $s['is_active'] ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge text-bg-secondary">Inactive</span>' ?></td>
<td>
<form method="post" onsubmit="return confirm('Delete this student?')">
<input type="hidden" name="action" value="delete"><input type="hidden" name="student_id" value="<?= h($s['student_id']) ?>">
<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
</form>
</td>
</tr>
<?php endforeach; ?>
<?php if (!$students): ?><tr><td colspan="6" class="text-center text-muted py-4">No student records yet.</td></tr><?php endif; ?>
</tbody>
</table>
</div></div>
</main></div></div>
</body></html>
