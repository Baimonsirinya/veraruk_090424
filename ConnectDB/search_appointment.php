<?php
// เชื่อมต่อกับฐานข้อมูล
include "../connect.php";

// รับค่าคำค้นหาจาก AJAX
$keyword = $_POST['keyword'];

// สร้างคำสั่ง SQL เพื่อค้นหาข้อมูล
$stmt = $pdo->prepare("SELECT * FROM appointment_admin_page WHERE patient_name LIKE :keyword OR doctor LIKE :keyword");
$stmt->bindValue(':keyword', '%' . $keyword . '%');
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $output = "<tr>
                    <th>วันเดือนปีที่นัดหมาย</th>
                    <th>เวลาที่นัดหมาย</th>
                    <th>ชื่อผู้ป่วย</th>
                    <th>แพทย์</th>
                    <th>ซักประวัติ</th>
                    <th> </th>
                </tr>";
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $output .= "<tr>";
        $output .= "<td>" . $row["date"] . "</td>";
        $output .= "<td>" . $row["time"] . "</td>";
        $output .= "<td>" . $row["patient_name"] . "</td>";
        $output .= "<td>" . $row["doctor"] . "</td>";
        if ($row['patient_status'] === 'ผู้ป่วยใหม่') {
            $output .= "<td><button class='add-data-medical' onclick='showform_add_medical(\"" . $row['appointment_id'] . "\", \"" . $row['patient_name'] . "\")'>เพิ่มเวชระเบียน <i class='far fa-edit'></i></button></td>";
        } else {
            $output .= "<td><button class='add-data' onclick='showformcheck(\"" . $row['appointment_id'] . "\", \"" . $row['patient_id'] . "\", \"" . $row['patient_name'] . "\", \"" . $row['doctor'] . "\", \"" . $row['time'] . "\")'>เพิ่มข้อมูล <i class='far fa-edit'></i></button></td>";
        }
        $output .= "<td><button class='delete-data' onclick='deleteAppointment(" . $row['appointment_id'] . ")'>ลบ <i class='far fa-trash-alt'></i></button></td>";
        $output .= "</tr>";
    }
} else {
    // ถ้าไม่มีข้อมูล
    $output = "<tr><td colspan='3'>ไม่พบข้อมูล</td></tr>";
}
// ส่ง HTML ที่สร้างไปยัง JavaScript ผ่าน AJAX
echo $output;
?>
