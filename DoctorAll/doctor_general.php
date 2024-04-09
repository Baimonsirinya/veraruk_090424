<?php
    include "../connect.php";
    include "../session/sessionlogin.php";
?>
<?php
    $stmt = $pdo->prepare("SELECT * FROM medical_records WHERE patient_id = ?");
    $stmt->bindParam(1, $_GET["patient_id"]);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="doctor_general.css">
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
            <!-- ตรวจสอบว่ามีข้อมูลใน $row หรือไม่ก่อนแสดงผล -->
            <?php if($row && isset($row["patient_id"])): ?>
                รหัสผู้ป่วย: <?=$row["patient_id"]?><br>
                ชื่อ-สกุล: <?=$row["name_patient"]?>
            <?php endif; ?>
        </div>
        <div class="side-nav">
            <a href="doctor_general.php?patient_id=<?=$row ["patient_id"] ?>" class="sidebar-item" id="general">ข้อมูลทั่วไป</a>
            <a href="doctor_treatment.php?patient_id=<?=$row ["patient_id"] ?>" class="sidebar-item" id="treatment">การรักษา</a>
            <a href="doctor_history.php?patient_id=<?=$row ["patient_id"] ?>" class="sidebar-item" id="course">ประวัติการรักษา</a>
        </div>
    </div>

    <div class="content-result">
    <form id="form_data" method="post" >
        <div class="form-container">
            <p>ข้อมูลส่วนตัว</p>
            <div class="card-data-personal">
                <div class="column" style="width: 200px;">
                    <label>ชื่อ-สกุล : <?=$row["name_patient"]?></label>
                    <label>วัน เดือน ปี เกิด : <?=$row["date_of_birth"]?></label>
                    <label>สถานภาพ : <?=$row["status"]?></label>
                    <label>ศาสนา : <?=$row["religion"]?></label>
                </div>
                <div class="column">
                    <label>เลขบัตรประชาชน : <?=$row["id_card"]?></label>
                    <label>อายุ : <?=$row["age"]?> ปี</label>
                    <label>เชื้อชาติ : <?=$row["ethnicity"]?></label>
                </div>
                <div class="column">
                    <label>เพศ : <?=$row["gender"]?></label>
                    <label>อาชีพ : <?=$row["career"]?></label>
                    <label>สัญชาติ : <?=$row["nationality"]?></label>  
                </div>
            </div>

            <p>ข้อมูลที่อยู่</p>
            <div class="card-data-address">
                <div class="column" style="width: 100px;">
                    <label>ที่อยู่ : <?=$row["address"]?></label>
                    <label>ตำบล : <?=$row["sub_district"]?></label>
                </div>
                <div class="column" style="width: 100px;">
                    <label>จังหวัด : <?=$row["province"]?></label>
                    <label>รหัสไปรษณีย์ : <?=$row["zip_code"]?></label>
                </div>
                <div class="column">
                    <label>อำเภอ : <?=$row["district"]?></label>
                    <label>เบอร์โทรศัพท์ : <?=$row["tel"]?></label><br>
                </div>
            </div>

            <p>ข้อมูลอื่นๆ</p>
            <div class="card-data-other">
                <div class="column" style="width: 100px;">
                    <label>โรคประจำตัว : <?=$row["congenital_disease"]?></label>
                    <label>ประวัติแพ้ยา : <?=$row["allergy"]?></label>
                    <label>ประวัติการผ่าตัด : <?=$row["surgery_history"]?></label>
                    <label>ประวัติการประสบอุบัติเหตุ : <?=$row["accident_history"]?></label>
                    <!-- <div class="note">หมายเหตุ : หากไม่มีประวัติให้ใช้สัญลักษณ์ "-"</div> -->
                </div>
            </div>
    </form>
        </div>


</body>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>

    function refreshPage() {
        window.location.reload(); // รีเฟรชหน้า
    }

</script>
</html>
