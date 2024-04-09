<?php
// เชื่อมต่อกับฐานข้อมูล
include "../connect.php";

// ตรวจสอบว่ามีการส่งข้อมูลแบบ POST มาหรือไม่
if(isset($_POST['treatment_id']) && isset($_POST['appointment_id']) && isset($_POST['patient_id']) && isset($_POST['doctor']) && isset($_POST['date']) && isset($_POST['weight']) && isset($_POST['height']) && isset($_POST['systolic']) && isset($_POST['diastolic']) && isset($_POST['temperature']) && isset($_POST['pluse_rate'])) {
    // ดำเนินการเฉพาะเมื่อมีข้อมูลทั้งหมดถูกส่งมา
    $treatment_id = $_POST['treatment_id'];
    $appointment_id = $_POST['appointment_id'];
    $patient_id = $_POST['patient_id'];
    $doctor = $_POST['doctor'];
    $date = $_POST['date'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $systolic = $_POST['systolic'];
    $diastolic = $_POST['diastolic'];
    $temperature = $_POST['temperature'];
    $pluse_rate = $_POST['pluse_rate'];

    try {
        // เตรียมคำสั่ง SQL สำหรับการเพิ่มข้อมูลในตาราง treatment
        $sql = "INSERT INTO treatment (treatment_id, patient_id, doctor, date, weight, height, systolic, diastolic, temperature, pluse_rate) VALUES (:treatment_id, :patient_id, :doctor, :date, :weight, :height, :systolic, :diastolic, :temperature, :pluse_rate)";

        // เตรียมและ execute คำสั่ง SQL
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':treatment_id', $treatment_id);
        $stmt->bindParam(':patient_id', $patient_id);
        $stmt->bindParam(':doctor', $doctor);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':height', $height);
        $stmt->bindParam(':systolic', $systolic);
        $stmt->bindParam(':diastolic', $diastolic);
        $stmt->bindParam(':temperature', $temperature);
        $stmt->bindParam(':pluse_rate', $pluse_rate);
        $stmt->execute();

        // ตรวจสอบว่ามีการเพิ่มข้อมูลสำเร็จหรือไม่
        if ($stmt->rowCount() > 0) {

            $sql_update = "UPDATE appointment_admin_page SET treatment_id = :treatment_id WHERE appointment_id = :appointment_id";

            // เตรียมและ execute คำสั่ง SQL
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->bindParam(':treatment_id', $treatment_id);
            $stmt_update->bindParam(':appointment_id', $appointment_id);
            $stmt_update->execute();
    
            // ตรวจสอบว่ามีข้อมูลถูกเพิ่มเข้าไปในฐานข้อมูลหรือไม่
            if ($stmt_update->rowCount() > 0) {
                echo 'เรียบร้อย' ; 
            } else {
                echo "ไม่สามารถอัปเดตข้อมูลได้"; 
            }
        }

    } catch(PDOException $e) {
        // ส่งคำตอบกลับไปยัง AJAX ว่ามีข้อผิดพลาดในการเชื่อมต่อกับฐานข้อมูล
        echo "error: " . $e->getMessage();
    }
} else {
    // ส่งคำตอบกลับไปยัง AJAX ว่าไม่พบข้อมูลที่ส่งมา
    echo "ไม่พบข้อมูลที่ส่งมา";
}
?>
