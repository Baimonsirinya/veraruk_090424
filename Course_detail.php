<?php
// เชื่อมต่อกับฐานข้อมูล
include "connect.php";
include "session/sessionlogin.php";

// ตรวจสอบว่ามีการส่งค่า id ของคอร์สมาหรือไม่
if(isset($_GET['id'])) {
    // ดึงค่า id ของคอร์สจาก URL
    $course_id = $_GET['id'];

    // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลของคอร์ส
    $stmt = $pdo->prepare("SELECT c.*, t.number_of_times, t.price , t.times_id FROM course c 
                           LEFT JOIN times_of_course t ON c.course_id = t.course_id WHERE c.course_id = ?");
    $stmt->execute([$course_id]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Detail</title>
    
    <link rel="stylesheet" href="css/Course_detail.css">
    
</head>
<header>
    <?php include "Navbar_Customers.php" ?>
</header>
<body>
    <div class="container">
        <?php if(isset($courses) && count($courses) > 0): ?>
            <?php foreach($courses as $key => $course): ?>
                <?php if($key === 0): ?>
                    <img src="images/<?= $course['image']; ?>" alt="<?= $course['course_name']; ?>">
                    <div>
                        <h1><?= $course['course_name']; ?></h1>
                    <?php endif; ?>
                        <form name="checkoutForm" method="GET" action="omise/index.php">
                            <input type="hidden" name="times_id" id="times_id" value="<?= $course['times_id']; ?>">
                            <?php if(isset($_SESSION['citizen_id'])): ?>
                                <?php // ตรวจสอบว่า citizen_id มีใน medical_records หรือไม่ ?>
                                <?php $stmt = $pdo->prepare("SELECT * FROM medical_records WHERE id_card = ?");
                                      $stmt->execute([$_SESSION['citizen_id']]);
                                      $record = $stmt->fetch(PDO::FETCH_ASSOC);
                                      if($record): ?>
                                        <button type="submit" class="button-sale">
                                            <p><?= $course['number_of_times']; ?> ครั้ง ราคา <?= $course['price']; ?> บาท</p>
                                        </button>
                                      <?php else: ?>
                                        <a class="button-sale" onclick="showAlert_form()"><p><?= $course['number_of_times']; ?> ครั้ง ราคา <?= $course['price']; ?> บาท</p></a>
                                      <?php endif; ?>
                            <?php else: ?>
                                <a class="button-sale" onclick="showAlert_login()"><p><?= $course['number_of_times']; ?> ครั้ง ราคา <?= $course['price']; ?> บาท</p></a>
                            <?php endif; ?>
                        </form>
            <?php endforeach; ?>
                    </div>
    </div>
    <div class = "card-detail">
        <h3>รายละเอียด</h3>
        <p class = "text-detail"><?= $course['course_detail']; ?></p>
    </div>
    <?php else: ?>
        <p>Course not found.</p>
    <?php endif; ?>

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
