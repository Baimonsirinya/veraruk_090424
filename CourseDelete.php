<?php
    // เชื่อมต่อกับฐานข้อมูล
    include "connect.php";

    // ตรวจสอบว่ามีการส่งค่า id ของคอร์สมาหรือไม่
    if(isset($_GET['id'])) {
        // รับค่า id ของคอร์ส
        $course_id = $_GET['id'];

        // เตรียมและ execute คำสั่ง SQL เพื่อลบข้อมูลคอร์ส
        $stmt = $pdo->prepare("DELETE FROM course WHERE course_id = ?");
        $stmt->execute([$course_id]);

        // ตรวจสอบว่าลบข้อมูลสำเร็จหรือไม่
        if($stmt->rowCount() > 0) {
            // ส่งค่า 'success' กลับไปยัง JavaScript เพื่อแสดงว่าการลบสำเร็จ
            echo 'success';
        } else {
            // ส่งค่า 'error' กลับไปยัง JavaScript เพื่อแสดงว่ามีข้อผิดพลาดเกิดขึ้น
            echo 'error';
        }
    } else {
        // ถ้าไม่มีการส่งค่า id มา
        echo 'error';
    }
?>
