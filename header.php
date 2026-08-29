<?php
/**
 * Shared site header/nav. Requires auth.php + helpers.php to already
 * be loaded, and require_login() to have already run in the including
 * page (so current_user() is guaranteed non-null here).
 */
$me = current_user();
?>
<header class="site-header">
    <div class="brand">
        <span class="brand-ar">المسافر لتأجير السيارات</span>
        <span class="brand-en">Al Musafir for Car Rental</span>
    </div>
    <nav>
        <a href="index.php">الرئيسية / Home</a>
        <a href="form.php">عقد جديد / New Contract</a>
        <a href="contracts.php">العقود المحفوظة / Saved Contracts</a>
        <?php if (is_admin()): ?>
            <a href="users.php">المستخدمون / Users</a>
        <?php endif; ?>
        <span style="margin-inline-start:18px; opacity:0.85;"><?= h($me['username']) ?></span>
        <a href="logout.php">خروج / Logout</a>
    </nav>
</header>
