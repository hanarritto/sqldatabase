<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/auth.php';
require_admin();

$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    try {
        $stmt = $pdo->prepare('INSERT INTO security_reports (log_id, admin_id, report_type, report_detail, action_taken, report_date) VALUES (?, ?, ?, ?, ?, NOW())');
        $stmt->execute([(int)$_POST['log_id'], (int)$_SESSION['admin_id'], trim($_POST['report_type']), trim($_POST['report_detail']), trim($_POST['action_taken'])]);
        $message = 'Security report saved.';
    } catch (PDOException $e) {
        $error = 'Could not save the security report.';
    }
}
$logs = $pdo->query("SELECT log_id, student_id, login_time, status FROM access_logs ORDER BY login_time DESC")->fetchAll();
$reports = $pdo->query("SELECT r.*, a.username AS admin_username FROM security_reports r LEFT JOIN admins a ON a.admin_id=r.admin_id ORDER BY r.report_date DESC")->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Security Reports</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="assets/style.css" rel="stylesheet"></head><body>
<?php include __DIR__ . '/partials/navbar.php'; ?><div class="container-fluid"><div class="row"><?php include __DIR__ . '/partials/sidebar.php'; ?>
<main class="col-lg-10 p-4"><h1 class="fw-bold">Security Reports</h1><p class="text-muted">Record failed attempts and unauthorized access incidents.</p>
<?php if($message): ?><div class="alert alert-success"><?=h($message)?></div><?php endif; ?><?php if($error): ?><div class="alert alert-danger"><?=h($error)?></div><?php endif; ?>
<div class="card border-0 shadow-sm mb-4"><div class="card-header bg-white"><h5 class="mb-0">Create Security Report</h5></div><div class="card-body"><form method="post" class="row g-3">
<input type="hidden" name="action" value="add">
<div class="col-md-3"><label class="form-label">Access Log</label><select name="log_id" class="form-select" required><?php foreach($logs as $l): ?><option value="<?=h((string)$l['log_id'])?>">#<?=h((string)$l['log_id'])?> - <?=h((string)($l['student_id']??'Unknown'))?> - <?=h($l['status'])?></option><?php endforeach; ?></select></div>
<div class="col-md-3"><label class="form-label">Report Type</label><select name="report_type" class="form-select"><option>Face Not Recognized</option><option>Unauthorized Access</option><option>Other</option></select></div>
<div class="col-md-3"><label class="form-label">Report Detail</label><input name="report_detail" class="form-control" required></div>
<div class="col-md-3"><label class="form-label">Action Taken</label><input name="action_taken" class="form-control" required></div>
<div class="col-12"><button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Save Report</button></div>
</form></div></div>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
<thead><tr><th>Report ID</th><th>Log ID</th><th>Admin</th><th>Type</th><th>Detail</th><th>Action</th><th>Date</th></tr></thead>
<tbody><?php foreach($reports as $r): ?><tr><td><?=h((string)$r['report_id'])?></td><td><?=h((string)$r['log_id'])?></td><td><?=h((string)($r['admin_username']??''))?></td><td><span class="badge text-bg-danger"><?=h($r['report_type'])?></span></td><td><?=h($r['report_detail'])?></td><td><?=h($r['action_taken'])?></td><td><?=h($r['report_date'])?></td></tr><?php endforeach; ?>
<?php if(!$reports): ?><tr><td colspan="7" class="text-center text-muted py-4">No security reports yet.</td></tr><?php endif; ?></tbody></table></div></div>
</main></div></div></body></html>
