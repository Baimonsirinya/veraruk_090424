<?php
// เชื่อมต่อกับฐานข้อมูล
include "../connect.php";

if(isset($_POST['selectedDate']) && isset($_POST['doctorName'])) {
    // ค้นหาเวลาที่ถูกเลือกไปแล้วในฐานข้อมูล
    $selectedDate = $_POST['selectedDate'];
    $doctorName = $_POST['doctorName'];

    $stmt = $pdo->prepare("SELECT * FROM appointment_admin_page WHERE date = :selectedDate AND doctor = :doctorName");
    $stmt->execute([':selectedDate' => $selectedDate, ':doctorName' => $doctorName]);

    // ดึงข้อมูลเวลาทั้งหมดจากการ query แล้วเก็บไว้ในตัวแปร $selectedTimes
    $selectedTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // สร้างสตริงเวลาจากข้อมูลใน $selectedTimes
    $timeArray = array_column($selectedTimes, 'time');
    $selectedTimesString = implode(',', $timeArray);

    // ส่งค่าเวลากลับไปยัง HTML
    echo $selectedTimesString;

} else {
    // หรือส่งข้อความผิดพลาดกลับไปยัง JavaScript
    echo "ไม่พบข้อมูลวันที่ที่ร้องขอ";
}

// ปิดการเชื่อมต่อฐานข้อมูล
$pdo = null;
?>
