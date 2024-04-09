<?php

include "../connect.php";
include "../session/sessionlogin.php";

// ตรวจสอบว่ามีการส่งค่าวันที่มาหรือไม่
if(isset($_POST['date'])) {
    // รับค่าวันที่จากการ POST
    $date = $_POST['date'];
    $username = $_SESSION['username'];

    // คิวรี่ฐานข้อมูลเพื่อดึงข้อมูลการนัดหมายในวันที่ที่กำหนด
    $stmt = $pdo->prepare("SELECT * FROM appointment_admin_page WHERE date = :date AND doctor = :username");
    $stmt->execute(['date' => $date, 'username' => $username]);
    
    if ($stmt->rowCount() > 0) {
    // เริ่มต้นการสร้างตาราง HTML
    echo "<table>";
    echo "<tr>";
    echo "<th>วันเดือนปีที่นัดหมาย</th>";
    echo "<th>เวลาที่นัดหมาย</th>";
    echo "<th>รหัสผู้ป่วย</th>";
    echo "<th>ชื่อผู้ป่วย</th>";
    echo "</tr>";

    // วนลูปเพื่อดึงข้อมูลการนัดหมายและแสดงผลในรูปแบบของตาราง HTML
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row["date"] . "</td>";
        echo "<td>" . $row["time"] . "</td>";
        echo "<td>" . $row["patient_id"] . "</td>";
        echo "<td>" . $row["patient_name"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    } else {
        // ถ้าไม่มีข้อมูล
        echo  "<tr><td colspan='3'>ไม่พบข้อมูล</td></tr>";
    }

} else {
    // ถ้าไม่มีการส่งค่าวันที่มา ส่งคำตอบข้อผิดพลาดกลับไปยัง JavaScript
    echo "ไม่ได้รับข้อมูลวันที่";
}
?>
