<?php
include "../connect.php"; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งไอดีของคอร์สมาหรือไม่
if (isset($_POST["course_id"])) {
    $courseId = $_POST["course_id"];
    $purchaseId = $_POST["purchase_id"];

    // ดำเนินการลบคอร์สออกจากตาราง purchase
    $stmt_purchase = $pdo->prepare("DELETE FROM purchase WHERE purchase_id = ?");
    $stmt_purchase->execute([$purchaseId]);

    // ดำเนินการลบคอร์สออกจากตาราง patient_course
    $stmt_patient_course = $pdo->prepare("DELETE FROM patient_course WHERE patient_course_id = ?");
    $stmt_patient_course->execute([$courseId]);

    // ตรวจสอบว่าลบคอร์สได้สำเร็จหรือไม่
    if ($stmt_purchase && $stmt_patient_course) {
        echo "success"; // ส่งค่ากลับไปยัง JavaScript เพื่อแสดงข้อความ "success"
    } else {
        echo "error"; // ส่งค่ากลับไปยัง JavaScript เพื่อแสดงข้อความ "error"
    }
} else {
    echo "invalid request"; // ส่งค่ากลับไปยัง JavaScript เพื่อแสดงข้อความ "invalid request" ในกรณีที่ไม่มีไอดีของคอร์ส
}
?>
