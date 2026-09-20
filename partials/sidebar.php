<aside class="col-lg-2 sidebar p-3"><div class="nav flex-column nav-pills gap-1">
<a class="nav-link <?=basename($_SERVER['PHP_SELF'])==='index.php'?'active':''?>" href="index.php"><i class="bi bi-grid"></i> Dashboard</a>
<a class="nav-link <?=basename($_SERVER['PHP_SELF'])==='students.php'?'active':''?>" href="students.php"><i class="bi bi-people"></i> Students</a>
<a class="nav-link <?=basename($_SERVER['PHP_SELF'])==='access_logs.php'?'active':''?>" href="access_logs.php"><i class="bi bi-door-open"></i> Access Logs</a>
<a class="nav-link <?=basename($_SERVER['PHP_SELF'])==='student_history.php'?'active':''?>" href="student_history.php"><i class="bi bi-clock-history"></i> Student History</a>
<a class="nav-link <?=basename($_SERVER['PHP_SELF'])==='security_reports.php'?'active':''?>" href="security_reports.php"><i class="bi bi-exclamation-triangle"></i> Security Reports</a>
<a class="nav-link <?=basename($_SERVER['PHP_SELF'])==='admins.php'?'active':''?>" href="admins.php"><i class="bi bi-person-gear"></i> Admins</a>
</div></aside>