<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/config/database.php';
if(!empty($_SESSION['admin_id'])){header('Location:index.php');exit;}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $stmt=$pdo->prepare('SELECT * FROM admins WHERE username=? AND is_active=1 LIMIT 1');
  $stmt->execute([trim($_POST['username']??'')]); $admin=$stmt->fetch();
  if($admin && password_verify($_POST['password']??'',$admin['password_hash'])){
    $_SESSION['admin_id']=$admin['admin_id'];$_SESSION['admin_username']=$admin['username'];$_SESSION['admin_role']=$admin['role'];
    header('Location:index.php');exit;
  } $error='Invalid username or password.';
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login | Dorm Access System</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="assets/style.css" rel="stylesheet"></head><body class="login-page"><div class="login-card"><div class="text-center mb-4"><div class="brand-icon">DB</div><h2 class="fw-bold">Dorm Access System</h2><p class="text-muted">Database Management Portal</p></div><?php if($error):?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif;?><form method="post"><label class="form-label">Username</label><input class="form-control mb-3" name="username" required><label class="form-label">Password</label><input class="form-control mb-4" type="password" name="password" required><button class="btn btn-primary w-100">Sign in</button></form><div class="small text-muted mt-3 text-center">Demo: admin_main / admin123</div></div></body></html>