<nav class="navbar navbar-dark bg-primary sticky-top shadow-sm"><div class="container-fluid">
<a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-shield-lock"></i> Dorm Access System</a>
<div class="d-flex align-items-center gap-3 text-white"><span class="small"><?=h($_SESSION['admin_username'] ?? '')?> (<?=h($_SESSION['admin_role'] ?? '')?>)</span><a class="btn btn-light btn-sm" href="logout.php">Logout</a></div>
</div></nav>