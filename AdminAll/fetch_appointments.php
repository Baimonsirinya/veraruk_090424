<?php
    
    include "../connect.php";

    $selectedDoctor = $_POST['doctor'];
    $selectedDate = $_POST['date'];
    
    // ทำการ query ข้อมูลการนัดหมาย
    $stmt = $pdo->prepare("SELECT time FROM appointment_admin_page WHERE doctor = :doctor AND date = :date");
    $stmt->execute(['doctor' => $selectedDoctor, 'date' => $selectedDate]);
    
    // เตรียมข้อมูลเวลาที่มีการจองไว้
    $bookedTimes = [];
    
    // วนลูปผลลัพธ์และเก็บเวลาที่มีการจอง
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $bookedTimes[] = $row['time'];
    }
    
    // ส่งข้อมูลเวลาที่มีการจองกลับไปยังหน้าเว็บ
    echo implode(",", $bookedTimes);
    ?>