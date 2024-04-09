<?php
include "../connect.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientID = $_POST['patient_id'];
    $appointmentID = $_POST['appointment_id'];

    // เตรียมคำสั่ง SQL ในการอัปเดต patient_id ในตาราง appointment_admin_page โดยใช้เงื่อนไข appointment_id
    $sql = "UPDATE appointment_admin_page SET patient_id = :patient_id WHERE appointment_id = :appointment_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':patient_id', $patientID);
    $stmt->bindParam(':appointment_id', $appointmentID);

    // ทำการ execute คำสั่ง SQL
    if ($stmt->execute()) {
        
        $sqlUpdateStatus = "UPDATE appointment_admin_page SET patient_status = '' WHERE appointment_id = :appointment_id";
        $stmtUpdateStatus = $pdo->prepare($sqlUpdateStatus);
        $stmtUpdateStatus->bindParam(':appointment_id', $appointmentID);
        
        // ทำการ execute คำสั่ง SQL ในการอัปเดตค่า patient_status
        $stmtUpdateStatus->execute();

        echo "success"; 
    } else {
        echo "error"; 
    }
}
?>