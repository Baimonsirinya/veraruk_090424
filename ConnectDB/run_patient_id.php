
<?php
include "../connect.php" ; 

$sql = "SELECT MAX(patient_id) AS max_patient_id FROM medical_records WHERE patient_id LIKE 'HN%'";
$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$max_patient_id = $row['max_patient_id'];

// ดึงปีปัจจุบัน
$current_year = date('Y');


if ($max_patient_id && substr($max_patient_id, -4) == $current_year) {
    // ตัด 'HN-' และปีออกจากค่า max_patient_id เพื่อให้ได้เฉพาะเลขที่ต้องการ
    $only_number = substr($max_patient_id, 7, 6);
    // เพิ่มเลขต่อไป โดยเปลี่ยนปีถ้ามีการเปลี่ยนปี
    $next_patient_number = sprintf("%06d", intval($only_number) + 1);
    // สร้างค่าใหม่
    $next_patient_id = "HN-" . $next_patient_number . "-$current_year";
} else {
    // ถ้าเป็นปีใหม่ ให้เริ่มต้นเลขใหม่
    $next_patient_id = sprintf("HN-000001-%s", $current_year);
}

echo $next_patient_id;
?>