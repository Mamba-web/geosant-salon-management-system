<div class="topbar">

    <div>
    <h4><?php echo isset($page_title) ? $page_title : "Dashboard"; ?></h4>
    </div>

    <div class="d-flex align-items-center gap-3">



<div class="d-flex align-items-center gap-3">

    <span>
        <i class="fas fa-user-circle me-1"></i>
        Welcome,
        <strong><?php echo $_SESSION['full_name']; ?></strong>
    </span>

    <button id="themeToggle" class="btn btn-outline-secondary btn-sm">
    <i class="fas fa-moon"></i>
</button>

    <a href="../auth/logout.php"
       class="btn btn-sm btn-outline-danger">
        <i class="fas fa-sign-out-alt"></i>
        Logout
    </a>

</div>
</div>

</div>