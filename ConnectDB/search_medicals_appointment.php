<?php
// เชื่อมต่อกับฐานข้อมูล
include "../connect.php";

// รับค่าคำค้นหาจาก AJAX
$keyword = $_POST['keyword'];

// สร้างคำสั่ง SQL เพื่อค้นหาข้อมูล
$stmt = $pdo->prepare("SELECT * FROM medical_records WHERE name_patient LIKE :keyword");
$stmt->bindValue(':keyword', '%' . $keyword . '%');
$stmt->execute();


if ($stmt->rowCount() > 0) {
// สร้าง HTML เพื่อแสดงผลลัพธ์
$output = "<tr>
                <th>รหัสผู้ป่วย</th>
                <th>ชื่อผู้ป่วย</th>
                <th></th>
            </tr>";
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $output .= "<tr>";
        $output .= "<td>" . $row["patient_id"] . "</td>"; 
        $output .= "<td>" . $row["name_patient"] . "</td>"; 
        $output .= "<td><button class='button-booking' onclick='showPopup(\"" . $row['patient_id'] . "\", \"" . $row['name_patient'] . "\", \"" . $row['tel'] . "\")'>เพิ่มลงในการนัดหมาย</button></td>";
    }
} else {
    // ถ้าไม่มีข้อมูล
    $output = "<tr><td colspan='3'>ไม่พบข้อมูล</td></tr>";
}

echo $output;
?>
