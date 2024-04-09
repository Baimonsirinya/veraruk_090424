<?php
    include "../connect.php";
    include "../session/sessionlogin.php";

 
    if(isset($_SESSION['username'])) {
        // ค้นหาข้อมูลในฐานข้อมูลที่ตรงกับ session
        $stmt = $pdo->prepare("SELECT * FROM appointment_admin_page WHERE doctor = :doctor_name");
        $stmt->bindValue(':doctor_name', $_SESSION['username'], PDO::PARAM_STR);
        $stmt->execute();

    } else {
        echo "Session username is not set."; // ถ้า session ไม่ถูกตั้งค่า
    }
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" href="doctor_all_appointment.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ดึงวันที่ปัจจุบันในรูปแบบของเมืองไทย
            var currentDateThailand = moment().tz("Asia/Bangkok").format('YYYY-MM-DD');
            // กำหนดค่าให้กับ input element
            document.getElementById('date_appoint').value = currentDateThailand;
        });
    </script>

</head>
<body>
  <header>
    <?php include "Navbar_Doctor.php"?>
  </header>
        <div class="position-search">
            <label for="date_appoint">วันเดือนปีที่นัดหมาย :<input type="date" id="date_appoint" min="<?= date('Y-m-d') ?>" max="9999-12-31"></label><br>
            <input type="text" id="search-box" placeholder="ค้นหา">
            <a href="doctor_selectpatient.php" class="button-add-booking" id="button-add-booking"> เพิ่มการนัดหมาย + </a>
        </div>
        <div class="content">
            <table id="all_appointment">
                <tr>
                    <th>วันเดือนปีที่นัดหมาย</th>
                    <th>เวลา</th>
                    <th>รหัสผู้ป่วย</th>
                    <th>ชื่อผู้ป่วย</th>
                </tr>
                <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?=$row["date"] ?></td>     
                    <td><?=$row["time"] ?></td> 
                    <td><?=$row["patient_id"] ?></td> 
                    <td><?=$row["patient_name"] ?></td>       
                </tr>
                <?php endwhile; ?>
            </table>       
    </div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
    document.getElementById("date_appoint").addEventListener("change", function() {
    // เมื่อมีการเปลี่ยนแปลงใน input type="date"
    // ให้เรียกใช้งานฟังก์ชัน fetchFindDate()

    // รับค่าวันที่ที่เลือกจาก input
    var selectedDate = document.getElementById("date_appoint").value;

    // แสดงค่าวันที่ที่ถูกเลือกใน console เพื่อตรวจสอบ
    console.log(selectedDate);

    // เตรียม XMLHttpRequest
    var xhr = new XMLHttpRequest();
    
    // กำหนดเมธอดและ URL
    xhr.open("POST", "../ConnectDB/fetch_findDate_doctor.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // เมื่อข้อมูลส่งกลับมา
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // รับข้อมูลที่ส่งกลับมาจากไฟล์ PHP
                var appointments = xhr.responseText;
                
                // เอาข้อมูลที่ได้ไปแสดงที่ all_appointment
                document.getElementById("all_appointment").innerHTML = appointments;

            } else {
                console.error("เกิดข้อผิดพลาดในการเรียกข้อมูล");
            }
        }
    };

    // ส่งข้อมูลวันที่ไปยังไฟล์ PHP
    xhr.send("date=" + selectedDate);
});


document.addEventListener("DOMContentLoaded", function() {
    // เมื่อมีการเปลี่ยนแปลงใน input type="date"
        document.getElementById("search-box").addEventListener("input", function() {
            // เรียกฟังก์ชัน fetchSearchData() เมื่อมีการเปลี่ยนแปลง
            fetchSearchData();
        });
    });

    function fetchSearchData() {
        // รับค่าคำค้นหา
        var keyword = document.getElementById("search-box").value;

        var xhr = new XMLHttpRequest();
        
        // กำหนดเมธอดและ URL
        xhr.open("POST", "../ConnectDB/search_appointment_doctor.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // เมื่อข้อมูลส่งกลับมา
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var searchData = xhr.responseText;
                    // เอา searchData ไปแสดงแทนที่ all_appointment
                    document.getElementById("all_appointment").innerHTML = searchData;
                } else {
                    console.error("เกิดข้อผิดพลาดในการเรียกข้อมูล");
                }
            }
        };

        // ส่งคำค้นหาไปยังไฟล์ PHP
        xhr.send("keyword=" + keyword);
        }
</script>
</body>
</html>