<?php
include "connect.php";
// คำสั่ง SQL สำหรับดึงข้อมูลแพทย์
$sql = "SELECT * FROM users WHERE role = 'doctor'";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// วนลูปเพื่อสร้างรายการแพทย์
$html = ''; // สร้างตัวแปรเพื่อเก็บข้อมูล HTML ที่ได้จากลูป
$html .= '<div class="container-doctors">';
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $html .= '<div class="container" >'; // เพิ่ม onclick ให้เรียกใช้ฟังก์ชัน selectDoctor() เมื่อคลิก
    $html .= '<div class="square">';
    $html .= '<img src="imagedoctor/' . $row["img"] . '" class="mask">';
    $html .= '<div class="h1">' . $row["name"] . ' ' . $row["lastname"] . '</div><br><br>';
    $html .= '<button class="button-appoint" onclick="makeAppointment(this)" data-doctor-id="' .($row["name"] . ' ' . $row["lastname"]) . '"><i class="fa-solid fa-calendar-days"></i>นัดหมาย</button>';
    $html .= '</div>';
    $html .= '</div>';
}

$html .= '</div>';
echo $html; // พิมพ์ข้อมูล HTML ทั้งหมดที่เก็บไว้ในตัวแปร $html
$pdo = null;
?>