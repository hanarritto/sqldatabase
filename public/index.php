<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/auth.php';
require_admin();

$counts = [];
foreach ([
    'students' => 'SELECT COUNT(*) FROM students',
    'access_logs' => 'SELECT COUNT(*) FROM access_logs',
    'student_history' => 'SELECT COUNT(*) FROM student_history',
    'security_reports' => 'SELECT COUNT(*) FROM security_reports',
    'admins' => 'SELECT COUNT(*) FROM admins'
] as $key => $sql) {
    $counts[$key] = (int)$pdo->query($sql)->fetchColumn();
}

$recentLogs = $pdo->query("
    SELECT a.log_id, a.student_id, a.login_time, a.access_type, a.status, a.device_id, a.user_type,
           s.username
    FROM access_logs a
    LEFT JOIN students s ON s.student_id = a.student_id
    ORDER BY a.login_time DESC
    LIMIT 8
")->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dorm Access System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="assets/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-primary sticky-top shadow-sm">
<div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-shield-lock"></i> Dorm Access System</a>
    <div class="d-flex align-items-center gap-3 text-white">
        <span class="small"><?= h($_SESSION['admin_username']) ?> (<?= h($_SESSION['admin_role']) ?>)</span>
        <a class="btn btn-light btn-sm" href="logout.php">Logout</a>
    </div>
</div>
</nav>

<div class="container-fluid">
<div class="row">
<aside class="col-lg-2 sidebar p-3">
    <div class="nav flex-column nav-pills gap-1">
        <a class="nav-link active" href="index.php"><i class="bi bi-grid"></i> Dashboard</a>
        <a class="nav-link" href="students.php"><i class="bi bi-people"></i> Students</a>
        <a class="nav-link" href="access_logs.php"><i class="bi bi-door-open"></i> Access Logs</a>
        <a class="nav-link" href="student_history.php"><i class="bi bi-clock-history"></i> Student History</a>
        <a class="nav-link" href="security_reports.php"><i class="bi bi-exclamation-triangle"></i> Security Reports</a>
        <a class="nav-link" href="admins.php"><i class="bi bi-person-gear"></i> Admins</a>
    </div>
</aside>

<main class="col-lg-10 p-4">
    <div class="mb-4">
        <h1 class="fw-bold">Dashboard</h1>
        <p class="text-muted">Overview of the dormitory database.</p>
    </div>

    <div class="row g-3 mb-4">
        <?php
        $cards = [
            ['students','Students','bi-people','primary'],
            ['access_logs','Access Logs','bi-door-open','success'],
            ['student_history','Student History','bi-clock-history','warning'],
            ['security_reports','Security Reports','bi-exclamation-triangle','danger'],
            ['admins','Admins','bi-person-gear','dark']
        ];
        foreach ($cards as [$key,$label,$icon,$color]):
        ?>
        <div class="col-md-6 col-xl">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-muted small"><?= $label ?></div>
                            <div class="display-6 fw-bold"><?= $counts[$key] ?></div>
                        </div>
                        <div class="stat-icon text-<?= $color ?>"><i class="bi <?= $icon ?>"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Recent Access Logs</h5>
        </div>
        <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr>
                <th>Log ID</th><th>Student ID</th><th>Username</th><th>Time</th>
                <th>Type</th><th>Status</th><th>Device</th><th>User Type</th>
            </tr></thead>
            <tbody>
            <?php foreach ($recentLogs as $row): ?>
                <tr>
                    <td><?= h((string)$row['log_id']) ?></td>
                    <td><?= h((string)($row['student_id'] ?? 'NULL')) ?></td>
                    <td><?= h((string)($row['username'] ?? 'Unknown')) ?></td>
                    <td><?= h($row['login_time']) ?></td>
                    <td><?= h($row['access_type']) ?></td>
                    <td><span class="badge <?= $row['status']==='SUCCESS'?'text-bg-success':'text-bg-danger' ?>"><?= h($row['status']) ?></span></td>
                    <td><?= h($row['device_id']) ?></td>
                    <td><?= h($row['user_type']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$recentLogs): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">No access logs yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</main>
</div>
</div>
</body>
</html>
