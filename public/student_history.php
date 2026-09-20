<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/auth.php';
require_admin();

$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    try {
        $stmt = $pdo->prepare('INSERT INTO student_history (student_id, first_name, last_name, floor, room, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $_POST['student_id'], trim($_POST['first_name']), trim($_POST['last_name']),
            (int)$_POST['floor'], trim($_POST['room']), $_POST['start_date'],
            $_POST['end_date'] !== '' ? $_POST['end_date'] : null
        ]);
        $message = 'Residence history saved.';
    } catch (PDOException $e) {
        $error = 'Could not save the residence history.';
    }
}
$students = $pdo->query('SELECT student_id, username FROM students WHERE is_active=1 ORDER BY student_id')->fetchAll();
$history = $pdo->query("SELECT h.*, s.username FROM student_history h LEFT JOIN students s ON s.student_id=h.student_id ORDER BY h.start_date DESC")->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student History</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="assets/style.css" rel="stylesheet"></head><body>
<?php include __DIR__ . '/partials/navbar.php'; ?><div class="container-fluid"><div class="row"><?php include __DIR__ . '/partials/sidebar.php'; ?>
<main class="col-lg-10 p-4"><h1 class="fw-bold">Student History</h1><p class="text-muted">Store student residence history.</p>
<?php if($message): ?><div class="alert alert-success"><?=h($message)?></div><?php endif; ?><?php if($error): ?><div class="alert alert-danger"><?=h($error)?></div><?php endif; ?>
<div class="card border-0 shadow-sm mb-4"><div class="card-header bg-white"><h5 class="mb-0">Add Residence Record</h5></div><div class="card-body"><form method="post" class="row g-3">
<input type="hidden" name="action" value="add">
<div class="col-md-3"><label class="form-label">Student</label><select name="student_id" class="form-select" required><?php foreach($students as $s): ?><option value="<?=h($s['student_id'])?>"><?=h($s['student_id'].' - '.$s['username'])?></option><?php endforeach; ?></select></div>
<div class="col-md-2"><label class="form-label">First Name</label><input name="first_name" class="form-control" required></div>
<div class="col-md-2"><label class="form-label">Last Name</label><input name="last_name" class="form-control" required></div>
<div class="col-md-1"><label class="form-label">Floor</label><input type="number" name="floor" class="form-control" min="1" required></div>
<div class="col-md-1"><label class="form-label">Room</label><input name="room" class="form-control" required></div>
<div class="col-md-1"><label class="form-label">Start</label><input type="date" name="start_date" class="form-control" required></div>
<div class="col-md-2"><label class="form-label">End</label><input type="date" name="end_date" class="form-control"></div>
<div class="col-12"><button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Save History</button></div>
</form></div></div>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
<thead><tr><th>History ID</th><th>Student ID</th><th>Username</th><th>Name</th><th>Floor</th><th>Room</th><th>Start</th><th>End</th></tr></thead>
<tbody><?php foreach($history as $r): ?><tr><td><?=h((string)$r['history_id'])?></td><td><?=h($r['student_id'])?></td><td><?=h((string)($r['username']??''))?></td><td><?=h($r['first_name'].' '.$r['last_name'])?></td><td><?=h((string)$r['floor'])?></td><td><?=h($r['room'])?></td><td><?=h($r['start_date'])?></td><td><?=h((string)($r['end_date']??'NULL'))?></td></tr><?php endforeach; ?>
<?php if(!$history): ?><tr><td colspan="8" class="text-center text-muted py-4">No residence history yet.</td></tr><?php endif; ?></tbody></table></div></div>
</main></div></div></body></html>
