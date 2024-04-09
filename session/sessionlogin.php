<?php

session_start();


if(isset($_SESSION['citizen_id']) && isset($_SESSION['role'])) {
    // ค้นหาชื่อของผู้ใช้จากฐานข้อมูล
    $stmt = $pdo->prepare("SELECT name , lastname FROM users WHERE citizen_id = :citizen_id");
    $stmt->bindParam(':citizen_id', $_SESSION['citizen_id']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบว่าค้นพบชื่อผู้ใช้หรือไม่
    if ($user) {
        $username = $user['name'] . ' ' . $user['lastname'];
        $_SESSION['username'] = $username;
    }
}
if(isset($_POST['logout'])) {
    // ล้าง session
    session_destroy();
    // ล้างค่าของ $username
    $username = '';
    if($_SESSION['role']==='user' || $_SESSION['role']==='manager'){
        header("Location: HomePageCustomers.php");
    } else {
        header("Location: ../HomePageCustomers.php");
    }
    
    exit;
}

?>