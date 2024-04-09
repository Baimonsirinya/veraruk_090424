<?php
    include "../connect.php"; // เชื่อมต่อกับฐานข้อมูล
    include "../session/sessionlogin.php"; // ตรวจสอบเซสชัน

  
    $patient_id = $_POST['patient_id'];
    $course_name = $_POST['procedure']; 
    $quantity = $_POST['quantity'];
    $purchase_id = $_POST['purchase_id'];
 
    $sql_patient_course = "INSERT INTO patient_course (patient_id, `procedure`, quantity, purchase_id) VALUES (?, ?, ?, ?)";


    $stmt_patient_course = $pdo->prepare($sql_patient_course);
    $stmt_patient_course->bindParam(1, $patient_id, PDO::PARAM_STR);
    $stmt_patient_course->bindParam(2, $course_name, PDO::PARAM_STR);
    $stmt_patient_course->bindParam(3, $quantity, PDO::PARAM_INT);
    $stmt_patient_course->bindParam(4, $purchase_id, PDO::PARAM_INT);

  
    $result_patient_course = $stmt_patient_course->execute();


    $sql_purchase = "INSERT INTO purchase (purchase_id, patient_id, `procedure`, quantity) VALUES (?, ?, ?, ?)";

   
    $stmt_purchase = $pdo->prepare($sql_purchase);
    $stmt_purchase->bindParam(1, $purchase_id, PDO::PARAM_INT);
    $stmt_purchase->bindParam(2, $patient_id, PDO::PARAM_STR);
    $stmt_purchase->bindParam(3, $course_name, PDO::PARAM_STR);
    $stmt_purchase->bindParam(4, $quantity, PDO::PARAM_INT);
    

    $result_purchase = $stmt_purchase->execute();

    if ($result_patient_course && $result_purchase) {
        echo "เพิ่มข้อมูลเรียบร้อยแล้ว";
    } else {
        echo "มีข้อผิดพลาดเกิดขึ้นในการเพิ่มข้อมูล";
    }
?>
