<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/auth.php';
require_admin();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    try {
        $studentId = trim($_POST['student_id'] ?? '');
        $stmt = $pdo->prepare('INSERT INTO access_logs (student_id, login_time, access_type, status, device_id, user_type) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $studentId !== '' ? $studentId : null,
            $_POST['login_time'] ?: date('Y-m-d H:i:s'),
            $_POST['access_type'],
            $_POST['status'],
            trim($_POST['device_id']),
            $_POST['user_type']
        ]);
        $message = 'Access log saved.';
    } catch (PDOException $e) {
        $error = 'Could not save the access log. Check the Student ID if you entered one.';
    }
}

$students = $pdo->query('SELECT student_id, username FROM students WHERE is_active=1 ORDER BY student_id')->fetchAll();
$logs = $pdo->query("SELECT a.*, s.username FROM access_logs a LEFT JOIN students s ON s.student_id=a.student_id ORDER BY a.login_time DESC")->fetchAll();
?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Access Logs</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="assets/style.css" rel="stylesheet"></head><body>
<?php include __DIR__ . '/partials/navbar.php'; ?><div class="container-fluid"><div class="row"><?php include __DIR__ . '/partials/sidebar.php'; ?>
<main class="col-lg-10 p-4"><h1 class="fw-bold">Access Logs</h1><p class="text-muted">Record entry and exit events from access devices.</p>
<?php if($message): ?><div class="alert alert-success"><?=h($message)?></div><?php endif; ?><?php if($error): ?><div class="alert alert-danger"><?=h($error)?></div><?php endif; ?>
<div class="card border-0 shadow-sm mb-4"><div class="card-header bg-white"><h5 class="mb-0">Add Access Log</h5></div><div class="card-body"><form method="post" class="row g-3">
<input type="hidden" name="action" value="add">
<div class="col-md-3"><label class="form-label">Student</label><select name="student_id" class="form-select"><option value="">Unknown person</option><?php foreach($students as $s): ?><option value="<?=h($s['student_id'])?>"><?=h($s['student_id'].' - '.$s['username'])?></option><?php endforeach; ?></select></div>
<div class="col-md-2"><label class="form-label">Date & Time</label><input type="datetime-local" name="login_time" class="form-control"></div>
<div class="col-md-2"><label class="form-label">Access Type</label><select name="access_type" class="form-select"><option>Entry</option><option>Exit</option></select></div>
<div class="col-md-2"><label class="form-label">Status</label><select name="status" class="form-select"><option>SUCCESS</option><option>FAILED</option></select></div>
<div class="col-md-2"><label class="form-label">Device ID</label><input name="device_id" class="form-control" placeholder="SCAN01" required></div>
<div class="col-md-1"><label class="form-label">User</label><select name="user_type" class="form-select"><option>Student</option><option>Unknown</option><option>Staff</option></select></div>
<div class="col-12"><button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Save Log</button></div>
</form></div></div>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
<thead><tr><th>Log ID</th><th>Student ID</th><th>Username</th><th>Login Time</th><th>Access Type</th><th>Status</th><th>Device</th><th>User Type</th></tr></thead>
<tbody><?php foreach($logs as $r): ?><tr><td><?=h((string)$r['log_id'])?></td><td><?=h((string)($r['student_id']??'NULL'))?></td><td><?=h((string)($r['username']??'Unknown'))?></td><td><?=h($r['login_time'])?></td><td><?=h($r['access_type'])?></td><td><span class="badge <?=$r['status']==='SUCCESS'?'text-bg-success':'text-bg-danger'?>"><?=h($r['status'])?></span></td><td><?=h($r['device_id'])?></td><td><?=h($r['user_type'])?></td></tr><?php endforeach; ?>
<?php if(!$logs): ?><tr><td colspan="8" class="text-center text-muted py-4">No access logs yet.</td></tr><?php endif; ?></tbody></table></div></div>
</main></div></div></body></html>
