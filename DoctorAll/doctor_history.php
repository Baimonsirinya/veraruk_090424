<?php
    include "../connect.php";
    include "../session/sessionlogin.php";

    // ดึงวันปัจจุบันของเซิร์ฟเวอร์
    $currentDate = date("Y-m-d");

    // ดึงชื่อคนไข้จากตาราง medical_records
    $stmt_patient = $pdo->prepare("SELECT name_patient FROM medical_records WHERE patient_id = ?");
    $stmt_patient->execute([$_GET["patient_id"]]);
    $patient = $stmt_patient->fetch(PDO::FETCH_ASSOC);

    // เช็คว่าพบชื่อคนไข้หรือไม่
    if ($patient) {
        $patientName = $patient["name_patient"];

        // เตรียมและ execute คำสั่ง SQL เพื่อดึงข้อมูลการรักษา
        $stmt = $pdo->prepare("SELECT * FROM treatment WHERE patient_id = ?");
        $stmt->execute([$_GET["patient_id"]]);

    } else {
        // ไม่พบชื่อคนไข้
        echo "ไม่พบข้อมูลคนไข้";
    }
?>


<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="doctor_history.css">
    <!-- <link rel="stylesheet" href="../css/medical_records.css"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<header>
    <?php include('Navbar_Doctor.php');?>
</header>

<div class="container">
    <div class="sidebar">
        <div class="sidebar-1">
            <!-- ตรวจสอบว่ามีข้อมูลใน $patient หรือไม่ก่อนแสดงผล -->
            <?php if($patient): ?>
                รหัสผู้ป่วย: <?=$_GET["patient_id"] ?><br>
                ชื่อ-สกุล: <?=$patient["name_patient"]?>
            <?php endif; ?>
        </div>
        <div class="side-nav">
            <a href="doctor_general.php?patient_id=<?=$_GET["patient_id"] ?>" class="sidebar-item" id="general">ข้อมูลทั่วไป</a>
            <a href="doctor_treatment.php?patient_id=<?=$_GET["patient_id"] ?>" class="sidebar-item" id="treatment">การรักษา</a>
            <a href="doctor_history.php?patient_id=<?=$_GET["patient_id"] ?>" class="sidebar-item" id="history">ประวัติการรักษา</a>
        </div>
    </div>
    <div class="content-table">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM treatment WHERE patient_id = ?");
            $stmt->bindParam(1, $_GET["patient_id"]);
            $stmt->execute();
                ?>
        <div class="table-date">
            <table>
                        <tr>
                            <th>วันเดือนปีที่รักษา</th>
                            <th>ดูข้อมูล</th>
                        </tr>
                <?php while($row=$stmt->fetch()): ?>
                    <tr>
                        <td><?=$row ["date"] ?></td>
                        <td><button id="view-data" onclick="openPopup(<?=$row ['treatment_id'] ?>)">ดูข้อมูล</button></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>   
    </div>
</div>


<div id="popup-view-history" class="popup-data">
    <div class="bg-popup">
        <span onclick="close_popup()" class="close">&times;</span>
        <h2>ประวัติการรักษา</h2>
        <hr>
        <div id="popup-content" class="popup-content"></div>
    </div>
</div>


</body>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    // เมื่อมีการเปลี่ยนแปลงใน input type="date"
    document.getElementById("date_appoint").addEventListener("change", function() {
        // เรียกฟังก์ชัน fetchAppointments() เมื่อมีการเปลี่ยนแปลง
        fetchFindDate();
    });
    });

    function fetchFindDate() {
    // รับค่าวันที่ที่เลือก
        var selectedDate = document.getElementById("date_appoint").value;


        var xhr = new XMLHttpRequest();
        
        // กำหนดเมธอดและ URL
        xhr.open("POST", "../ConnectDB/fetch_findDateHistory.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // เมื่อข้อมูลส่งกลับมา
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var history = xhr.responseText;
                    // เอา appointments ไปแสดงแทนที่ all_appointment
                    document.getElementById("all_history").innerHTML = history;

                } else {
                    console.error("เกิดข้อผิดพลาดในการเรียกข้อมูล");
                }
            }
        };

        // ส่งข้อมูลวันที่ไปยังไฟล์ PHP
        xhr.send("date=" + selectedDate);
    }

    //---------------------------pop-up data history-----------------------------------//
    function openPopup(treatment_id) {
        // console.log("Treatment ID: " + treatment_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // แสดง Popup
                var popup = document.getElementById("popup-view-history");
                popup.style.display = "flex";
                // แสดงข้อมูลใน Popup
                var popupContent = document.getElementById("popup-content");
                popupContent.innerHTML = this.responseText;
            }
        };
        
        xhttp.open("GET", "../ConnectDB/fetch_history_bytreatid.php?treatment_id=" + treatment_id, true);
        xhttp.send();
    }

    function close_popup(){
            document.getElementById('close')
            var popup = document.getElementById("popup-view-history");
            popup.style.display = "none"; 
        };
    
</script>

</html>