<?php
// เชื่อมต่อกับฐานข้อมูล
include "../connect.php";


// รับค่าคำค้นหา
$searchText = isset($_GET['search']) ? $_GET['search'] : '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'admin' AND (citizen_id LIKE :search OR name LIKE :search OR lastname LIKE :search OR Tel LIKE :search)");
$stmt->bindValue(':search', "%$searchText%", PDO::PARAM_STR);
$stmt->execute();

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($stmt->rowCount() > 0) {
    echo "<div class='text-role'>บัญชีผู้ใช้ของพนักงาน</div>";
    echo "<table border='1'>
            <tr>
                <th>หมายเลขบัตรประชาชน</th>
                <th>Password</th>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
                <th>เบอร์โทร</th>
                <th> </th>
            </tr>";
    // วนลูปเพื่อแสดงข้อมูล
    while ($row = $stmt->fetch()) {
        echo "<tr>
                <td>".$row["citizen_id"]."</td>
                <td>".$row["password"]."</td>
                <td>".$row["name"]."</td>
                <td>".$row["lastname"]."</td>
                <td>".$row["tel"]."</td>
                <td><button class='button-delete' citizen_id='".$row["citizen_id"]."' onclick='deleteUser(\"".$row["citizen_id"]."\")'>ลบ</button></td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<div style='text-align:center;'>ไม่พบข้อมูลพนักงาน</div>";
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$pdo = null;
?>
