<?php
    include "../connect.php";
    include "../session/sessionlogin.php";

    // ตรวจสอบว่ามีการรับพารามิเตอร์ patient_id ผ่านทาง URL หรือไม่
    if(isset($_GET["patient_id"])) {
        // ทำคำสั่ง SQL เพื่อคิวรี่ข้อมูลจากตาราง patient_course โดยใช้ patient_id
        $stmt_course = $pdo->prepare("SELECT * FROM patient_course WHERE patient_id = ?");
        $stmt_course->bindParam(1, $_GET["patient_id"]);
        $stmt_course->execute();
    } else {
        // ถ้าไม่มี patient_id ส่งมาให้กลับไปหน้าที่แสดงรายการผู้ป่วยหลัก
        header("Location: admin_general.php");
        exit; // ออกจากสคริปต์
    }

    $stmt = $pdo->prepare("SELECT * FROM medical_records WHERE patient_id = ?");
    $stmt->bindParam(1, $_GET["patient_id"]);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="admin_coursepatient.css">
    <!-- <link rel="stylesheet" href="../css/medical_records.css"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<header>
    <?php include('Navbar_Admin.php');?>
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
            <a href="admin_general.php?patient_id=<?=$row ["patient_id"] ?>" class="sidebar-item" id="general">ข้อมูลทั่วไป</a>
            <a href="fetch_history.php?patient_id=<?=$row ["patient_id"] ?>" class="sidebar-item" id="history">ประวัติการรักษา</a>
            <a href="admin_coursepatient.php?patient_id=<?=$row ["patient_id"] ?>" class="sidebar-item" id="course">คอร์สการรักษา</a>
        </div>
    </div>
    <div class="content">
    <h2>ข้อมูลคอร์สการรักษา</h2>

    <div class="course_remaining">
        <h4>คอร์สที่คงเหลืออยู่</h4>
        <?php
            // คิวรี่ข้อมูลจากตาราง patient_course อีกครั้ง
            $stmt_course->execute();
            $foundNonZeroQuantity = false; // สร้างตัวแปรเพื่อตรวจสอบว่ามีคอร์สที่เหลือเท่ากับ 0 หรือไม่
        ?>
        <table>
            <tr>
                <th>ชื่อคอร์ส</th>
                <th>จำนวนที่เหลือ</th>
            </tr>
            <?php while($row_course = $stmt_course->fetch(PDO::FETCH_ASSOC)): ?>
                <?php if($row_course["quantity"] > 0): ?>
                    <?php $foundNonZeroQuantity = true; ?>
                    <tr>
                        <td><?= $row_course["procedure"] ?></td>
                        <td><?= $row_course["quantity"] ?></td>
                    </tr>
                <?php endif; ?>
            <?php endwhile; ?>
        </table>
        <?php if(!$foundNonZeroQuantity): ?>
            <p>ไม่มีคอร์สที่เหลืออยู่</p>
        <?php endif; ?>
    </div>


    <div class="course_usedup">
        <h4>คอร์สที่เคยซื้อ</h4>
        <?php
            // คิวรี่ข้อมูลจากตาราง patient_course อีกครั้ง
            $stmt_course->execute();
            $foundZeroQuantity = false; // สร้างตัวแปรเพื่อตรวจสอบว่ามีคอร์สที่เหลือเท่ากับ 0 หรือไม่
        ?>
        <table>
            <tr>
            <th>ชื่อคอร์ส</th>
            <th>จำนวนที่เหลือ</th>
            </tr>
            <?php while($row_course = $stmt_course->fetch(PDO::FETCH_ASSOC)): ?>
                <?php if($row_course["quantity"] == 0): ?>
                    <?php $foundZeroQuantity = true; ?>
                    <tr>
                        <td><?= $row_course["procedure"] ?></td>
                        <td>-</td>
                    </tr>
                <?php endif; ?>
            <?php endwhile; ?>
        </table>
        <?php if(!$foundZeroQuantity): ?>
            <p>ไม่มีข้อมูล</p>
        <?php endif; ?>
    </div>


</div>
</div>

</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    
</script>
</html>
