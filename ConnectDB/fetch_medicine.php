<?php
// Include ไฟล์ connect.php เพื่อเชื่อมต่อกับฐานข้อมูล
include "../connect.php";

// ตรวจสอบว่ามีการส่งค่า treatment_id มาหรือไม่
if(isset($_POST['treatmentId'])) {
    // รับค่า treatment_id จาก AJAX request
    $treatment_id = $_POST['treatmentId'];

    // คิวรีข้อมูลใบสั่งยาจากฐานข้อมูล
    $stmt = $pdo->prepare("SELECT * FROM treatment WHERE treatment_id = ?");
    $stmt->execute([$treatment_id]);

    // ตรวจสอบว่ามีข้อมูลใบสั่งยาที่ตรงกับ treatment_id ที่รับมาหรือไม่
    if($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $treatmentId = $row['treatment_id'];
        $medicineName = $row['medicine_name'];
        $patientId = $row['patient_id'];
        $currentDate = $row['date'];

        // คิวรีข้อมูลชื่อผู้ป่วยจากตาราง medical_records
        $stmt2 = $pdo->prepare("SELECT name_patient FROM medical_records WHERE patient_id = ?");
        $stmt2->execute([$patientId]);
        $medicalRecordRow = $stmt2->fetch(PDO::FETCH_ASSOC);
        $patientName = $medicalRecordRow['name_patient'];

        // สร้าง HTML สำหรับข้อมูลใบสั่งยา
        if(!empty($medicineName)) {
            // ถ้ามีชื่อยา
            $response = "<div class='data-medicine'>
            <input type='hidden' value='$treatmentId'>
            รหัสผู้ป่วย: $patientId<br>
            ชื่อผู้ป่วย: $patientName<br>
            วันที่: $currentDate<br>
            ยาที่จ่าย: $medicineName<br>
        </div>
        ";
        } else {
            // ถ้าไม่มีชื่อยา
            $response = "<div class='data-medicine'>ยังไม่มีการจ่ายยา</div>";
        }
    } else {
        // ถ้าไม่พบข้อมูลใบสั่งยาที่ตรงกับ treatment_id ที่รับมา
        $response = "<div class='data-medicine'>ไม่พบข้อมูลใบสั่งยา</div>";
    }

    // ส่งข้อความ HTML กลับไปยัง JavaScript
    echo $response;
} else {
    // ถ้าไม่มีการส่งค่า treatment_id มา
    echo "<div class='data-medicine'>ไม่พบข้อมูลใบสั่งยา</div>";
}
?>
