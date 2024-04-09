<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/Navbar.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" ></script>
</head>

<header>
        <img class="logo" src="../images/logo.png"></img>
        <div class="position-menu">
        <nav>
            <ul>
                <li><a href="report.php">รายงานผล</a></li>
                <li><a href="admin_appointment.php">จัดการการนัดหมาย</a></li>
                <li><a href="admin_medrecords.php">เวชระเบียน</a></li>
                <li class="user-profile">
                <?php if(isset($username) && !empty($username)): ?>
                    <a href="#"><i class="fa-solid fa-user"></i><?php echo $username; ?><i class="fa-solid fa-caret-down"></i></a>
                    <ul class="dropdown">
                        <li>
                            <form method="post" action="">
                                <button class="logout-button" type="submit" name="logout">Logout</button>
                            </form>
                        </li>
                    </ul>
                    <?php else: ?>
                        <a href="../login.php">เข้าสู่ระบบ</a>|<a href="../Register.php">สมัครใช้งาน</a>
                    <?php endif; ?>
            </li>
            </ul>
        </nav>
    </div>
    </header>
    <div class="content">
        <!-- Your page content goes here -->
    </div>
</body>
</html>

