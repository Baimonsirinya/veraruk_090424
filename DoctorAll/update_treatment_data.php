<?php

include "../connect.php";

if(isset($_POST['treatment_id'])) {
    $treatment_id = $_POST['treatment_id'];
    $treatmentDetails = $_POST['treatment_details'];
    $medicineName = $_POST['medicine_name'];
    $doctor = $_POST['doctor'];

    $stmt = $pdo->prepare("UPDATE treatment SET doctor = ?, treatment_details = ?, medicine_name = ? WHERE treatment_id = ?");
    $stmt->bindParam(1, $doctor, PDO::PARAM_STR);
    $stmt->bindParam(2, $treatmentDetails, PDO::PARAM_STR);
    $stmt->bindParam(3, $medicineName, PDO::PARAM_STR);
    $stmt->bindParam(4, $treatment_id, PDO::PARAM_INT);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        // อัปเดตค่า status ในฐานข้อมูล
        $updateStatus = $pdo->prepare("UPDATE treatment SET status = 'บันทึกข้อมูลแล้ว' WHERE treatment_id = ?");
        $updateStatus->bindParam(1, $treatment_id, PDO::PARAM_INT);
        $updateStatus->execute();

        echo "บันทึกข้อมูลเรียบร้อยแล้ว";
    } else {
        echo "มีข้อผิดพลาดในการบันทึกข้อมูล";
    }
} else {
    echo "ไม่พบค่า treatment_id ที่ส่งมา";
}

?>
