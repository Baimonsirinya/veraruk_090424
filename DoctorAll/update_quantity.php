<?php
include "../connect.php";

if(isset($_POST['rowId'])) {
    $rowId = $_POST['rowId'];
    
    $stmt = $pdo->prepare("UPDATE patient_course SET quantity = quantity - 1 WHERE patient_course_id= ?");
    $stmt->execute([$rowId]);
    
    if($stmt->rowCount() > 0) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
