<?php
    include "../connect.php";
    include "../session/sessionlogin.php";
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="fetch_history.css">
    <!-- <link rel="stylesheet" href="../css/medical_records.css"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<script>
    function openPopup(treatment_id) {
        console.log("Treatment ID: " + treatment_id);
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
<body>
<header>
    <?php include('Navbar_Admin.php');?>
</header>
<body>
<div class="container">
    <div class="sidebar">
        <div class="sidebar-1">
        <?php
            $stmt = $pdo->prepare("SELECT * FROM medical_records WHERE patient_id = ?");
            $stmt->bindParam(1, $_GET["patient_id"]);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
            <!-- ตรวจสอบว่ามีข้อมูลใน $row หรือไม่ก่อนแสดงผล -->
            <?php if($row && isset($row["patient_id"])): ?>
                รหัสผู้ป่วย: <?=$row["patient_id"]?><br>
                ชื่อ-สกุล: <?=$row["name_patient"]?>
            <?php endif; ?>
        </div>
        <div class="side-nav">
            <a href="admin_general.php?patient_id=<?=$row ["patient_id"] ?>" class="sidebar-item" id="general">ข้อมูลทั่วไป</a>
            <a href="fetch_history.php?patient_id=<?=$row ["patient_id"] ?>" class="sidebar-item" id="history">ประวัติการรักษา</a>
            <a href="admin_coursepatient.php?patient_id=<?=$row ["patient_id"] ?>" class="sidebar-item" id="course">คอร์สการรักษา</a>
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
</html>
