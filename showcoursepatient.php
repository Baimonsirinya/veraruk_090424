<?php
include "connect.php";
include "session/sessionlogin.php";

// ตรวจสอบว่ามี session citizen_id หรือไม่
if (isset($_SESSION['citizen_id']) && !empty($_SESSION['citizen_id'])) {
    $citizen_id = $_SESSION['citizen_id'];
    
    // ทำการคิวรีข้อมูล patient_id จากตาราง medical_records โดยใช้ citizen_id
    $stmt_patient_id = $pdo->prepare("SELECT patient_id FROM medical_records WHERE id_card = ?");
    $stmt_patient_id->execute([$citizen_id]);
    $patient_id_row = $stmt_patient_id->fetch(PDO::FETCH_ASSOC);

    $stmt_course = $pdo->prepare("SELECT * FROM patient_course WHERE patient_id = ?");
    $stmt_course->bindParam(1, $patient_id);
    $stmt_course->execute();
?>    

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="css/showcoursepatient.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<header>
    <?php include('Navbar_Customers.php');?>
</header>
<body>
    <?php
        if ($patient_id_row) {
            $patient_id = $patient_id_row['patient_id'];

    ?>
        <div class="container">
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

    <?php
    } else {
        // ถ้าไม่พบ patient_id ให้แสดงข้อความหรือทำการปรับปรุงตามที่ต้องการ
        echo "<div style='margin-top: 200px; text-align: center;'>ไม่พบข้อมูลคอร์สของคุณ</div>";
    }
    ?>
    
<?php
} else {
    // ถ้าไม่มี session citizen_id ให้ทำการแสดงข้อความหรือทำการปรับปรุงตามที่ต้องการ
    echo "กรุณาเข้าสู่ระบบ";
}
?>




</body>
</html>
