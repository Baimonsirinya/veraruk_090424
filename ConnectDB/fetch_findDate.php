<?php

include "../connect.php";

// ตรวจสอบว่ามีการส่งค่าวันที่มาหรือไม่
if(isset($_POST['date'])) {
    // รับค่าวันที่จากการ POST
    $date = $_POST['date'];

    // คิวรี่ฐานข้อมูลเพื่อดึงข้อมูลการนัดหมายในวันที่ที่กำหนด
    $stmt = $pdo->prepare("SELECT * FROM appointment_admin_page WHERE date = ?");
    $stmt->execute([$date]);

    if ($stmt->rowCount() > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>วันเดือนปีที่นัดหมาย</th>";
        echo "<th>เวลาที่นัดหมาย</th>";
        echo "<th>ชื่อผู้ป่วย</th>";
        echo "<th>แพทย์</th>";
        echo "<th>ซักประวัติ</th>";
        echo "<th> </th>";
        echo "</tr>";

        // วนลูปเพื่อดึงข้อมูลการนัดหมายและแสดงผลในรูปแบบของตาราง HTML
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row["date"] . "</td>";
            echo "<td>" . $row["time"] . "</td>";
            echo "<td>" . $row["patient_name"] . "</td>";
            echo "<td>" . $row["doctor"] . "</td>";
            echo "<td>";
            if ($row['patient_status'] === 'ผู้ป่วยใหม่') {
                echo "<button class='add-data-medical' onclick=\"showform_add_medical('" . $row['appointment_id'] . "','" . $row['patient_name'] . "')\">";
                echo "เพิ่มเวชระเบียน <i class='far fa-edit'></i>";
                echo "</button>";
            } else {
                echo "<button class='add-data' onclick=\"showformcheck('" . $row['appointment_id'] . "','" . $row['patient_id'] . "','" . $row['patient_name'] . "','" . $row['doctor'] . "','" . $row['time'] . "')\">";
                echo "เพิ่มข้อมูล <i class='far fa-edit'></i>";
                echo "</button>";
            }
            echo "</td>";
            echo "<td>";
            echo "<button class='delete-data' onclick=\"deleteAppointment(" . $row['appointment_id'] . ")\">ลบ <i class='far fa-trash-alt'></i></button>";
            echo "</td>";
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
