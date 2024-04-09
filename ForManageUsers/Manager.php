<?php
// เชื่อมต่อกับฐานข้อมูล
include "../connect.php";

$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'manager'");
$stmt->execute();

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($stmt->rowCount() > 0) {
    echo "<div class='text-role'>บัญชีผู้ใช้ของผู้จัดการ</div>";
    echo "<table border='1'>
        <tr>
            <th>หมายเลขบัตรประชาชน</th>
            <th>Password</th>
            <th>ชื่อ</th>
            <th>นามสกุล</th>
            <th>เบอร์โทร</th>
            <th></th>
        </tr>";
    // วนลูปเพื่อแสดงข้อมูล
    while ($row = $stmt->fetch()) {
    echo 
        "<tr>
            <td>".$row["citizen_id"]."</td>
            <td>".$row["password"]."</td>
            <td>".$row["name"]."</td>
            <td>".$row["lastname"]."</td>
            <td>".$row["tel"]."</td>
            <td><button class='button-delete'>ลบ</button></td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "ไม่พบข้อมูล";
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$pdo = null;
?>


