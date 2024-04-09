<?php
// เชื่อมต่อกับฐานข้อมูล
include "../connect.php";
include "../session/sessionlogin.php";

// รับค่าคำค้นหาจาก AJAX
$keyword = $_POST['keyword'];
$username = $_SESSION['username'];

// สร้างคำสั่ง SQL เพื่อค้นหาข้อมูล
$stmt = $pdo->prepare("SELECT * FROM appointment_admin_page WHERE patient_name LIKE :keyword AND doctor = :username");
$stmt->bindValue(':keyword', '%' . $keyword . '%');
$stmt->bindValue(':username', $username);
$stmt->execute();


if ($stmt->rowCount() > 0) {
    $output = "<tr>
                    <th>วันเดือนปีที่นัดหมาย</th>
                    <th>เวลาที่นัดหมาย</th>
                    <th>รหัสผู้ป่วย</th>
                    <th>ชื่อผู้ป่วย</th>
                </tr>";
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $output .= "<tr>";
        $output .= "<td>" . $row["date"] . "</td>";
        $output .= "<td>" . $row["time"] . "</td>";
        $output .= "<td>" . $row["patient_id"] . "</td>";
        $output .= "<td>" . $row["patient_name"] . "</td>";
        $output .= "</tr>";
    }
} else {
    // ถ้าไม่มีข้อมูล
    $output = "<tr><td colspan='3'>ไม่พบข้อมูล</td></tr>";
}

// ส่ง HTML ที่สร้างไปยัง JavaScript ผ่าน AJAX
echo $output;
?>
