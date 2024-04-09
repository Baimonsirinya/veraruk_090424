<?php
// เชื่อมต่อกับฐานข้อมูล
include "../connect.php";

// รับข้อมูลจาก JavaScript ผ่านการ POST
$procedure = $_POST['procedure'];
$treatmentId = $_POST['treatment_id'];

// ตรวจสอบว่าข้อมูลเดิมเป็นค่าว่างหรือไม่
if (empty($procedure)) {
    // หากข้อมูลเดิมเป็นค่าว่าง ให้ใช้ข้อมูลใหม่โดยไม่ต้องเพิ่มช่องว่าง
    $sql = "UPDATE treatment SET `procedure` = :procedure WHERE treatment_id = :treatment_id";
} else {
    $sql = "UPDATE treatment SET `procedure` = CONCAT(`procedure`, ' ', :procedure) WHERE treatment_id = :treatment_id";
}

$stmt = $pdo->prepare($sql);

// ใส่ค่า procedure และ treatment_id ลงใน statement
$stmt->bindValue(':procedure', $procedure, PDO::PARAM_STR);
$stmt->bindValue(':treatment_id', $treatmentId, PDO::PARAM_INT);

// ทำการ execute statement เพื่ออัปเดตข้อมูล procedure
if ($stmt->execute()) {
    echo "บันทึกข้อมูล procedure เรียบร้อยแล้ว";
} else {
    // ส่งข้อความกลับไปยัง JavaScript เมื่อเกิดข้อผิดพลาดในการบันทึกข้อมูล
    echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล procedure";
}

?>
