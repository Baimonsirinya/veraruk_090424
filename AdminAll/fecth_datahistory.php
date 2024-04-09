<?php include "../connect.php";?>
<?php
$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'user'");
$stmt->execute();

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($stmt->rowCount() > 0) {
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
            <td>".$row["Tel"]."</td>
            <td><button class='button-delete' citizen_id='".$row["citizen_id"]."' onclick='deleteUser(\"".$row["citizen_id"]."\")'>ลบ</button></td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "ไม่พบข้อมูลลูกค้า";
}

// ปิดการเชื่อมต่อกับฐานข้อมูล
$pdo = null;
?>
