<?php
include "../connect.php";

// รับค่าคำค้นหา
$searchText = isset($_GET['search']) ? $_GET['search'] : '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'user' AND (citizen_id LIKE :search OR name LIKE :search OR lastname LIKE :search OR tel LIKE :search)");
$stmt->bindValue(':search', "%$searchText%", PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    echo "<div class='text-role'>บัญชีผู้ใช้ของลูกค้า</div>";
    echo "<table border='1'>
            <tr>
                <th>หมายเลขบัตรประชาชน</th>
                <th>Password</th>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
                <th>เบอร์โทร</th>
                <th> </th>
            </tr>";
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
    echo "<div style='text-align:center;'>ไม่พบข้อมูลลูกค้า</div>";
}

$pdo = null;
?>
