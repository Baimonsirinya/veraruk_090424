<?php
    include "../connect.php";
    include "../session/sessionlogin.php";

 
    if(isset($_SESSION['username'])) {
        // ค้นหาข้อมูลในฐานข้อมูลที่ตรงกับ session
        $stmt = $pdo->prepare("SELECT * FROM appointment_admin_page WHERE doctor = :doctor_name AND status = 'เรียกพบแพทย์' ");
        $stmt->bindValue(':doctor_name', $_SESSION['username'], PDO::PARAM_STR);
        $stmt->execute();

    } else {
        echo "Session username is not set."; // ถ้า session ไม่ถูกตั้งค่า
    }
?>

<?php
    $stmt_patient = $pdo->prepare("SELECT * FROM medical_records"); 
    $stmt_patient->execute();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" href="doctor_checkpatient.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <header>
    <?php include "Navbar_Doctor.php"?>
  </header>
    
        <!-- <div class="position-search">
            <input type="text" id="search-box" placeholder="ค้นหา">
        </div> -->
        <div class="content">
        <?php
        if ($stmt->rowCount() > 0) {
        ?>
              <table id="all_appointment">
                    <tr>
                        <th>รหัสผู้ป่วย</th>
                        <th>ชื่อผู้ป่วย</th>
                        <th>แพทย์</th>
                        <th>check in </th>
                    </tr>
                <?php while($row=$stmt->fetch()): ?>
                      <tr>
                        <td><?=$row ["patient_id"] ?></td>
                        <td><?=$row ["patient_name"] ?></td>
                        <td><?=$row ["doctor"] ?></td>
                        <td><a class="button-check" href="doctor_general.php?patient_id=<?=$row ["patient_id"] ?>">คลิกเพื่อบันทึกข้อมูลการรักษา</a></td><br>     
                      </tr>
                <?php endwhile; ?>
                </table>
              <?php
            } else {
              echo "<p style='text-align: center; margin-top: 150px'>ไม่มีคิวตรวจ</p>";
            }
            ?>
          </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

// document.addEventListener("DOMContentLoaded", function() {
//     // เมื่อมีการเปลี่ยนแปลงใน input type="text" ค้นหา
//     document.getElementById("search-box").addEventListener("input", function() {
//         // เรียกฟังก์ชัน fetchSearchData() เมื่อมีการเปลี่ยนแปลง
//         fetchSearchData();
//     });
// });

// function fetchSearchData() {
//     // รับค่าคำค้นหา
//     var keyword = document.getElementById("search-box").value;

//     var xhr = new XMLHttpRequest();
    
//     // กำหนดเมธอดและ URL
//     xhr.open("POST", "../ConnectDB/search_appointment_doctor.php", true);
//     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

//     // เมื่อข้อมูลส่งกลับมา
//     xhr.onreadystatechange = function() {
//         if (xhr.readyState === XMLHttpRequest.DONE) {
//             if (xhr.status === 200) {
//                 var searchData = xhr.responseText;
//                 // เอา searchData ไปแสดงแทนที่ all_appointment
//                 document.getElementById("all_appointment").innerHTML = searchData;
//             } else {
//                 console.error("เกิดข้อผิดพลาดในการเรียกข้อมูล");
//             }
//         }
//     };

//     // ส่งคำค้นหาไปยังไฟล์ PHP
//     xhr.send("keyword=" + keyword);
// }

        
</script>
</html>