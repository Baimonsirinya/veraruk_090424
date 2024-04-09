<?php
include "connect.php";
include "session/sessionlogin.php";
?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <!-- meta data -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!--font-family-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&amp;subset=devanagari,latin-ext" rel="stylesheet">

    <!-- title of site -->
    <title>รายการนัดหมาย</title>

    <!--responsive.css-->
    <link rel="stylesheet" href="css/showappointment.css">
</head>

<body>
<header>
    <?php include "Navbar_Customers.php" ?>
</header>

<?php
$stmt = $pdo->prepare("SELECT * FROM medical_records WHERE id_card = ?");
$stmt->execute([$_SESSION['citizen_id']]);
$medical_record = $stmt->fetch(PDO::FETCH_ASSOC);

if ($medical_record) {
    $patient_id = $medical_record['patient_id'];

    $stmt = $pdo->prepare("SELECT * FROM appointment_admin_page WHERE patient_id = ? AND date >= CURDATE()");
    $stmt->execute([$patient_id]);

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch()) :
?>
        <div class="name-patient">การนัดหมายของคุณ <?= $row["patient_name"] ?></div>
        <div class="container">
            <h3>นัดหมายที่ยังไม่ถึง</h3><br>
            <div class="position-data">
                <p>วันที่นัดหมาย : <?= $row["date"] ?></p>
                <p>เวลานัดหมาย : <?= $row["time"] ?></p>
                <p>แพทย์ : <?= $row["doctor"] ?></p>
            </div>
        </div>
        <hr>

        <?php
            // ดึงข้อมูลประวัติการนัดหมายจากตาราง treatment
            $stmt_treatment = $pdo->prepare("SELECT * FROM treatment WHERE patient_id = ?");
            $stmt_treatment->execute([$patient_id]);

            // ตรวจสอบว่ามีข้อมูลประวัติการเข้าพบแพทย์หรือไม่
            if ($stmt_treatment->rowCount() > 0) {
        ?>
            <div class="container">
                <h3>ประวัติการเข้าพบแพทย์</h3><br><br>
                <div class="position-history">
                    <table>
                        <tr>
                            <th>วันที่นัดหมาย</th>
                            <th>แพทย์</th>
                        </tr>
                        <?php while ($row_treatment = $stmt_treatment->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?= $row_treatment["date"] ?></td>
                                <td><?= $row_treatment["doctor"] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            </div>
        <?php
            } else {
                echo "<div style='margin-top: 20px; text-align: center;'>ไม่พบประวัติการเข้าพบแพทย์</div>";
            }
        ?>
<?php
        endwhile;
    } else {
        echo "<div class='name-patient'>การนัดหมายของคุณ " . $medical_record["name_patient"] . "</div>
                <div class='container'>
                    <h3 style='background-color: white;'></h3><br>
                    <div style='background-color: white;' class='position-data'>
                        <div style='text-align: center;'>ไม่มีการนัดหมายเร็วๆนี้</div>
                    </div>
                </div>
                <hr>";
    
        // ดึงข้อมูลประวัติการนัดหมายจากตาราง treatment
        $stmt_treatment = $pdo->prepare("SELECT * FROM treatment WHERE patient_id = ?");
        $stmt_treatment->execute([$patient_id]);
    
        // ตรวจสอบว่ามีข้อมูลประวัติการเข้าพบแพทย์หรือไม่
        if ($stmt_treatment->rowCount() > 0) {
            echo "<div class='container'>
                <h3>ประวัติการเข้าพบแพทย์</h3><br><br>
                <div class='position-history'>
                    <table>
                        <thead>
                            <tr>
                                <th>วันที่เข้าพบแพทย์</th>
                                <th>แพทย์</th>
                            </tr>
                        </thead>
                        <tbody>";
            
            // วนลูปเพื่อแสดงข้อมูลประวัติการเข้าพบแพทย์
            while ($row_treatment = $stmt_treatment->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>" . $row_treatment["date"] . "</td>
                        <td>" . $row_treatment["doctor"] . "</td>
                      </tr>";
            }
    
            // สิ้นสุดการสร้างตาราง
            echo "</tbody>
                </table>
                </div>
            </div>"; 
        } else {
            echo "<div style='margin-top: 50px; text-align: center;'>ไม่พบประวัติการเข้าพบแพทย์</div>";
        }
    }

} else {
    echo "<div style='margin-top: 200px; text-align: center;'>ไม่พบรายการนัดหมายเพราะคุณไม่มีประวัติการรักษา</div>";
}
?>

</body>
</html>
