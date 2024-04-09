<?php
// เรียกใช้ไฟล์เชื่อมต่อกับฐานข้อมูล
include "../connect.php";

// รับข้อมูลจากฟอร์ม
$patient_name = $_POST['patient_name_new'];
$tel = $_POST['tel_new'];
$date_for_new = $_POST['date_for_new'];
$doctor_for_new = $_POST['doctor_for_new'];
$time_for_new = $_POST['time_for_new'];

// เตรียมคำสั่ง SQL เพื่อบันทึกข้อมูลใน appointment_admin_page โดยใช้ PDO
$sql_appointment = "INSERT INTO appointment_admin_page (date, time, patient_name, tel, doctor, status, patient_status)
                    VALUES (:date_for_new, :time_for_new, :patient_name, :tel, :doctor_for_new, 'รอเข้ารับบริการ', 'ผู้ป่วยใหม่')";

        
// เตรียมและ execute คำสั่ง SQL สำหรับ appointment_admin_page
$stmt_appointment = $pdo->prepare($sql_appointment);
$stmt_appointment->bindParam(':date_for_new', $date_for_new);
$stmt_appointment->bindParam(':time_for_new', $time_for_new);
$stmt_appointment->bindParam(':patient_name', $patient_name);
$stmt_appointment->bindParam(':tel', $tel);
$stmt_appointment->bindParam(':doctor_for_new', $doctor_for_new);

// ทำการ execute คำสั่ง SQL สำหรับ appointment_admin_page
if ($stmt_appointment->execute()) {
    echo "success";
} else {
    echo "Error: " . $sql_appointment . "<br>" . $stmt_appointment->errorInfo(); // แสดงข้อผิดพลาดหากเกิดข้อผิดพลาดในการ execute คำสั่ง SQL สำหรับ appointment_admin_page
}
?>
