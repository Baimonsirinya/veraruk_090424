<?php
    // เชื่อมต่อกับฐานข้อมูล
    include "../connect.php";

    // รับค่าวันที่จากแบบฟอร์ม
    $date = $_POST['date'];
    $time = $_POST['time'];
    $patient_id = $_POST['patient_id'];
    $patient_name = $_POST['patient_name'];
    $tel = $_POST['tel'];
    $doctor = $_POST['doctor'];

    // ไม่จำเป็นต้องแปลงรูปแบบวันที่เพราะ $selectedDate ได้รับรูปแบบมาแล้ว
    // $formattedDate = date("d-m-Y", strtotime($selectedDate));

    // เตรียมคำสั่ง SQL สำหรับการแทรกข้อมูล
    $sql = "INSERT INTO appointment_admin_page (date, time, patient_id, patient_name, tel, doctor, status) VALUES (:date, :time, :patient_id, :patient_name, :tel, :doctor , 'รอเข้ารับบริการ')";
    
    // กำหนดคำสั่ง SQL เพื่อใช้พรีแพร์ดเตอร์
    $stmt = $pdo->prepare($sql);

    // ผูกค่าพารามิเตอร์กับค่าข้อมูล
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':patient_id', $patient_id);
    $stmt->bindParam(':patient_name', $patient_name);
    $stmt->bindParam(':tel', $tel);
    $stmt->bindParam(':doctor', $doctor);

    if ($stmt->execute()) {
        echo "บันทึกข้อมูลเรียบร้อยแล้ว";
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
    }

    // ปิดการเชื่อมต่อกับฐานข้อมูล
    $pdo = null;
?>
