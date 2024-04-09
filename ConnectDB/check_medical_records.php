<?php

include "../connect.php";

if(isset($_SESSION['citizen_id'])) {
    $citizen_id = $_SESSION['citizen_id'];
    
    // เตรียมคำสั่ง SQL
    $sql = "SELECT * FROM medical_records WHERE id_card = :citizen_id";

    // เตรียมและสร้าง statement
    $stmt = $pdo->prepare($sql);

    // ผูกค่าพารามิเตอร์
    $stmt->bindParam(':citizen_id', $id_card, PDO::PARAM_STR);

    // ประมวลผลคำสั่ง SQL
    $stmt->execute();

    // นับจำนวนแถวที่คืนค่า
    $rowCount = $stmt->rowCount();

    if($rowCount > 0) {
        // มีรหัสบัตรประชาชนในตาราง medical_records ให้นำไปยังหน้า RegisterAppointment.php
        echo '<a href="RegisterAppointment.php">นัดหมาย </a>';
    } else {
        // ไม่มีรหัสบัตรประชาชนในตาราง medical_records ให้ไปหน้าอื่น
        echo '<a href="Register.php">นัดหมาย</a>';
    }
}
// } else {
//     // ถ้าไม่มี Session citizen_id ให้ไปยังหน้าเข้าสู่ระบบ
//     echo '<a href="login.php">เข้าสู่ระบบ</a>|<a href="Register.php">สมัครใช้งาน</a>';
// }
?>