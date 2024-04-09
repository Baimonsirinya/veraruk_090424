<?php
include "../connect.php";

$sql = "SELECT MAX(purchase_id) AS max_purchase_id FROM purchase";
$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$max_purchase_id = $row['max_purchase_id'];

if ($max_purchase_id) {
    // เริ่มต้นที่ 1 และเพิ่มขึ้นไปเรื่อย ๆ
    $next_purchase_id = intval($max_purchase_id) + 1;
} else {
    // หากไม่มี purchase_id ให้เริ่มต้นที่ 1
    $next_purchase_id = 1;
}

echo $next_purchase_id;
?>
