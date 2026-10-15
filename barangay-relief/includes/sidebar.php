<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="col-md-3 col-lg-2 sidebar p-0">
    <div class="sidebar-header">
        <h4>🏡 Barangay Relief</h4>
        <small>Distribution Monitoring System</small>
    </div>

    <nav class="sidebar-menu">
        <a href="dashboard.php"
           class="<?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
            <span>🏠</span> Dashboard
        </a>

        <a href="beneficiaries.php"
           class="<?php echo in_array($current_page, ['beneficiaries.php', 'add_beneficiary.php', 'edit_beneficiary.php']) ? 'active' : ''; ?>">
            <span>👥</span> Beneficiaries
        </a>

        <a href="distribution.php"
           class="<?php echo $current_page === 'distribution.php' ? 'active' : ''; ?>">
            <span>📦</span> Distribution
        </a>

        <a href="records.php"
           class="<?php echo $current_page === 'records.php' ? 'active' : ''; ?>">
            <span>📋</span> Records
        </a>

        <a href="reports.php"
           class="<?php echo $current_page === 'reports.php' ? 'active' : ''; ?>">
            <span>📊</span> Reports
        </a>
    </nav>

    <div class="theme-switch-area">
        <button type="button" id="themeToggle" class="theme-toggle">
            🌙 Dark Mode
        </button>
    </div>
</aside>

<script src="assets/js/theme.js"></script>