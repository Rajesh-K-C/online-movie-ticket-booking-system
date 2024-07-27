<?php
function navbar()
{
    ?>
    <nav>
        <div class="overlay"></div>
        <ul class="sidebar">
            <li class="close">
                <svg onclick="navHideUnhide();" width="20" height="20">
                    <line x1="0" y1="0" x2="20" y2="20" stroke-width="3" />
                    <line x1="0" y1="20" x2="20" y2="0" stroke-width="3" />
                </svg>
            </li>
            <li><a href="./">Home</a></li>
            <?php if (!isset ($_SESSION['role'])): ?>
                <li><a href="login.php">Login</a></li>
            <?php else: ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
        <ul class="navbar">
            <li><a href="./" class="logo">Movie Ticket Booking</a></li>
            <li class="hideOnMobile"><a href="./">Home</a></li>
            <?php if (!isset ($_SESSION['role'])): ?>
                <li class="hideOnMobile"><a href="login.php">Login</a></li>
            <?php else: ?>
                <?php if ($_SESSION['role'] == "admin"): ?>
                <?php endif; ?>
                <li class="hideOnMobile"><a href="dashboard.php">Dashboard</a></li>
                <li class="hideOnMobile"><a href="logout.php">Logout</a></li>
            <?php endif; ?>
            <li class="menu">
                <svg onclick="navHideUnhide();" width="20" height="15">
                    <rect x="0" y="0" width="20" height="3" />
                    <rect x="0" y="6" width="20" height="3" />
                    <rect x="0" y="12" width="20" height="3" />
                </svg>
            </li>
        </ul>
    </nav>
    <?php
}
?>