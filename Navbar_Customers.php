<?php include "connect.php"; ?>// เชื่อมต่อฐานข้อมูล

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/Navbar.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" ></script>
</head>

<header>
    <img class="logo" src="images/logo.png"></img>
    <div class="position-menu">
    <nav>
        <ul>
            <li><a href="HomePageCustomers.php">หน้าหลัก</a></li>
            <li><a href="homepage_contact.php">เกี่ยวกับเรา</a></li>

            <?php
                if(isset($_SESSION['citizen_id'])) {
                    $citizen_id = $_SESSION['citizen_id'];
                    
                    // ตรวจสอบว่ามีรหัสบัตรใน medical_records หรือไม่
                    $sql = "SELECT * FROM medical_records WHERE id_card = :citizen_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':citizen_id', $citizen_id, PDO::PARAM_STR);
                    $stmt->execute();
                    $rowCount = $stmt->rowCount();

                    // ตรวจสอบจำนวนแถวที่คืนค่า
                    if($rowCount > 0) {
                        // มีรหัสบัตรใน medical_records ให้นำทางไปยังหน้า RegisterAppointment.php
                        echo '<li><a href="RegisterAppointment.php">นัดหมาย</a></li>';
                    } else {
                        // ไม่มีรหัสบัตรใน medical_records 
                        echo '<li><a onclick="showAlert_form()">นัดหมาย</a></li>';
                    }
                } else {
                    // ถ้าไม่มี Session citizen_id ให้นำทางไปยังหน้าเข้าสู่ระบบ login.php
                    echo '<li><a onclick="showAlert_login()">นัดหมาย</a></li>';
                }
            ?>
            <li class="user-profile">
                    <?php if(isset($username) && !empty($username)): ?>
                        <a href="#"><i class="fa-solid fa-user"></i><?php echo $username; ?><i class="fa-solid fa-caret-down"></i></a>
                        <ul class="dropdown">
                        <li><a href="showappointment.php">รายการนัดหมาย</a></li>
                        <li><a href="showcoursepatient.php">คอร์สที่มีอยู่</a></li>
                        <li>
                            <form method="post" action="">
                                <button class="logout-button" type="submit" name="logout">Logout</button>
                            </form>
                        </li>
                    </ul>

                    <?php else: ?>
                        <a href="login.php">เข้าสู่ระบบ</a>|<a href="Register.php">สมัครใช้งาน</a>
                    <?php endif; ?>
            </li>
        </ul>
    </nav>
    </div>
</header>
    <div class="content">
        <!-- Your page content goes here -->
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showAlert_login() {
            Swal.fire({
                title: "กรุณาเข้าสู่ระบบก่อน",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "ตกลง",
                cancelButtonText: "ยกเลิก",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "login.php"; // ไปยังหน้า login
                }
            });
        }


        function showAlert_form() {
            Swal.fire({
                title: "กรุณากรอกข้อมูลผู้ป่วยก่อน",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "ตกลง",
                cancelButtonText: "ยกเลิก",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "form_medical_records.php"; // ไปยังหน้า login
                }
            });
        }
    </script>
</body>
</html>
