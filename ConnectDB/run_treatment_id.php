<?php
include "../connect.php";

$sql = "SELECT MAX(treatment_id) AS max_treatment_id FROM treatment";
$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$max_treatment_id = $row['max_treatment_id'];

if ($max_treatment_id) {
    // เริ่มต้นที่ 1 และเพิ่มขึ้นไปเรื่อย ๆ
    $next_treatment_id = intval($max_treatment_id) + 1;
} else {
    // หากไม่มี treatment_id ให้เริ่มต้นที่ 1
    $next_treatment_id = 1;
}

echo $next_treatment_id;
?>
