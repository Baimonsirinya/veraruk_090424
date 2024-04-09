<?php
    include "../connect.php";
?>

<?php
// Check if treatment_id is set and is not empty
if(isset($_GET['treatment_id'])){
    // Prepare SQL statement
    $treatment_id = htmlspecialchars($_GET['treatment_id']);
    $stmt = $pdo->prepare("SELECT * FROM treatment WHERE treatment_id = ? ");
    $stmt->execute([$_GET["treatment_id"]]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo "<div class='data-history'>";
        echo  "<div class='column'>";
        echo        "<label>รหัสผู้ป่วย : <input type='text' name='name_patient' value='" . $row['patient_id'] . "' readonly></label><br>";
        echo        "<label>วันเดือนปีที่รักษา : <input type='text' name='name_patient' value='" . $row['date'] . "' readonly></label><br>";
        echo   "</div>";
        echo    "<div class='input-row1'>";
        echo            "<label>systolic : <input type='text' class='input-field' name='name_patient' value='" . $row['systolic'] . "' readonly></label><br>";
        echo            "<label>diastolic : <input type='text'class='input-field' name='name_patient' value='" . $row['diastolic'] . "' readonly></label><br>";
        echo            "<label>temperature : <input type='text'class='input-field' name='name_patient' value='" . $row['temperature'] . "' readonly></label><br>";
        echo           "<label>pluse_rate : <input type='text' class='input-field' name='name_patient' value='" . $row['pluse_rate'] . "' readonly></label><br>";
        echo            "<label>weight : <input type='text' class='input-field' name='name_patient' value='" . $row['weight'] . "' readonly></label><br>";
        echo           "<label>height : <input type='text' class='input-field' name='name_patient' value='" . $row['height'] . "' readonly></label><br>";
        echo    "</div>";
        echo    "<div class='input-row'>";
        echo        "<label>แพทย์ : <input type='text' class='input-doctor' name='name_patient' value='" . $row['doctor'] . "' readonly></label><br>";
        echo         "<label class='detail'>หัตถการ : <input type='text' class='input-detail' style='width: 500px; height = 300px;' value='" . $row['procedure'] . "'</label><br>";
        echo         "<label class='detail'>รายละเอียดการรักษา : <input type='text' class='input-detail' style='width: 500px; height = 300px;' value='" . $row['treatment_details'] . "'</label><br>";
        echo         "<label>ยาที่จ่าย : <input type='text' class='input-detail' value='" . $row['medicine_name'] . "' readonly></label><br>";
        echo    "</div>";
        echo   "</div>";
    } else {
        echo "ไม่พบข้อมูลการรักษา";
    }
}
?>