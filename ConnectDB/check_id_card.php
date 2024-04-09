<?php
include "../connect.php";

if (isset($_POST['id_card'])) {
    $idCard = $_POST['id_card'];

    // เตรียมคำสั่ง SQL ด้วย PDO
    $sql = "SELECT * FROM medical_records WHERE id_card = :id_card";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_card', $idCard);
    $stmt->execute();

    // นับจำนวนแถวที่พบ
    $rowCount = $stmt->rowCount();

    // ตรวจสอบว่ามีข้อมูลในฐานข้อมูลหรือไม่
    if ($rowCount > 0) {
        // ถ้าพบว่ามีข้อมูล ให้ดึงชื่อผู้ป่วยมา
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $patientName = $row['name_patient'];
        $patientID = $row['patient_id'];
        
        // ส่งข้อมูลชื่อผู้ป่วยพร้อมกับข้อความ "exists" กลับไปยัง JavaScript
        echo "exists:" . $patientName . "," . $patientID;
    } else {
        // ถ้าไม่พบข้อมูลในฐานข้อมูล ให้ส่งข้อความ "not_exists" กลับไปยัง JavaScript
        echo "not_exists";
    }
}

?>