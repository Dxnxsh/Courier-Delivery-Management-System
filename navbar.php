<?php
// Check if the 'role' session variable is set
if (isset($_SESSION['role'])) {
    $sidebarClass = ($_SESSION['role'] === 'rider') ? 'sidebar rider' : 'sidebar admin';
} else {
    // If 'role' is not set, you can handle it accordingly
    $sidebarClass = 'sidebar';
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/css/nav.css">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php if ($_SESSION['role'] === 'rider'): ?>
        <div class="sidebar open rider">
            <div class="logo-details" style="margin-top: 25px">
                <img style="object-fit: contain; max-width: 100%" src="assets/img/Group%201.png">
            </div>

            <ul class="nav-list">
                <li>
                    <a href="riderDashboard.php">
                        <i class='bx bx-grid-alt'></i>
                        <span class="links_name">Dashboard</span>
                    </a>
                    <span class="tooltip">Dashboard</span>
                </li>
                <li>
                    <a href="selSes.php">
                        <i class='bx bx-coin-stack'></i>
                        <span class="links_name">Session Select</span>
                    </a>
                    <span class="tooltip">Session Select</span>
                </li>
                <li>
                    <a href="curSes.php">
                        <i class='bx bx-list-ul'></i>
                        <span class="links_name">Current Session</span>
                    </a>
                    <span class="tooltip">Current Session</span>
                </li>
                <li class="profile">
                    <div class="profile-details">
                        <div class="name_job">
                            <div class="name">
                                <?php echo $_SESSION['name']; ?>
                            </div>
                            <div class="job">
                                <?php echo $_SESSION['role']; ?>
                            </div>
                        </div>
                    </div>
                    <i class='bx bx-log-out' id="log_out" onclick="window.location.href = 'logout.php';"></i>
                </li>
            </ul>
        </div>
    <?php elseif ($_SESSION['role'] === 'admin'): ?>
        <div class="sidebar open admin">
            <div class="logo-details" style="margin-top: 25px">
                <img style="object-fit: contain; max-width: 100%" src="assets/img/Group%201.png">
            </div>
            <ul class="nav-list">
                <li>
                    <a href="adminDashboard.php">
                        <i class='bx bx-grid-alt'></i>
                        <span class="links_name">Dashboard</span>
                    </a>
                    <span class="tooltip">Dashboard</span>
                </li>
                <li>
                    <a href="selSes.php">
                        <i class='bx bx-coin-stack'></i>
                        <span class="links_name">View Session</span>
                    </a>
                    <span class="tooltip">View Session</span>
                </li>
                <li>
                    <a href="genPage.php">
                        <i class='bx bxs-cloud-upload'></i>
                        <span class="links_name">Generate Session</span>
                    </a>
                    <span class="tooltip">Generate Session</span>
                </li>
                <li>
                    <a href="requestlist.php">
                        <i class='bx bx-list-ul'></i>
                        <span class="links_name">Request List</span>
                    </a>
                    <span class="tooltip">Request List</span>
                </li>
                <li>
                    <a href="signup.php">
                        <i class='bx bx-user-plus'></i>
                        <span class="links_name">Register Staff</span>
                    </a>
                    <span class="tooltip">Register Staff</span>
                </li>
                <li class="profile">
                    <div class="profile-details">
                        <div class="name_job">
                            <div class="name">
                                <?php echo $_SESSION['name']; ?>
                            </div>
                            <div class="job">
                                <?php echo $_SESSION['role']; ?>
                            </div>
                        </div>
                    </div>
                    <i class='bx bx-log-out' id="log_out" onclick="window.location.href = 'logout.php';"></i>
                </li>
            </ul>
        </div>
    <?php endif; ?>
    <script>
        let sidebar = document.querySelector(".sidebar");
        let closeBtn = document.querySelector("#btn");
        let searchBtn = document.querySelector(".bx-search");

        // following are the code to change sidebar button(optional)
        function menuBtnChange() {
            if (sidebar.classList.contains("open")) {
                closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");//replacing the iocns class
            } else {
                closeBtn.classList.replace("bx-menu-alt-right", "bx-menu");//replacing the iocns class
            }
        }
    </script>
</body>

</html>