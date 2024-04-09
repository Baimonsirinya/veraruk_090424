<?php

include "connect.php";

// ตรวจสอบว่ามีการส่งข้อมูลแบบ POST มาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบและรับค่าจากฟอร์ม
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $tel = $_POST['tel'];
    $citizen_id = $_POST['citizen_id'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // ตรวจสอบ Role เพื่อกำหนดการเพิ่มข้อมูล
    if ($role === "doctor") {
        // หากเป็น Role "doctor" อาจมีการอัพโหลดรูปภาพ
        // ตรวจสอบว่ามีการอัพโหลดรูปภาพหรือไม่
        if(isset($_FILES['imageUpload'])) {
            $image_name = $_FILES['imageUpload']['name'];
            $image_tmp = $_FILES['imageUpload']['tmp_name'];
            $image_size = $_FILES['imageUpload']['size'];

            // ตรวจสอบขนาดของรูปภาพ (ตัวอย่างเท่านั้น)
            if ($image_size > 500000) {
                echo "รูปภาพมีขนาดใหญ่เกินไป";
                exit();
            }

            // เก็บรูปภาพลงในโฟลเดอร์ (ตัวอย่างเท่านั้น)
            move_uploaded_file($image_tmp, "imagedoctor/" . $image_name);
        }

        try {
            // เตรียมและสร้างคำสั่ง SQL
            $sql = "INSERT INTO users (name, lastname, tel, citizen_id, password, role, img) 
                    VALUES (:name, :lastname, :tel, :citizen_id, :password, :role, :image)";
            $stmt = $pdo->prepare($sql);
            
            // ผูกค่าพารามิเตอร์
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':citizen_id', $citizen_id);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);
            if ($role === "doctor") {
                $stmt->bindParam(':image', $image_name);
            } else {
                $image_name = null;
                $stmt->bindParam(':image', $image_name);
            }
            
            // ประมวลผลคำสั่ง SQL
            $stmt->execute();

            echo "เพิ่มข้อมูลเรียบร้อยแล้ว";
        } catch(PDOException $e) {
            echo "เกิดข้อผิดพลาดในการเพิ่มข้อมูล: " . $e->getMessage();
        }

        // ปิดการเชื่อมต่อฐานข้อมูล
        $pdo = null;

    } else {
        // หากไม่ใช่ Role "doctor" ไม่ต้องการอัพโหลดรูปภาพ
        try {
            // เตรียมและสร้างคำสั่ง SQL
            $sql = "INSERT INTO users (name, lastname, tel, citizen_id, password, role) 
                    VALUES (:name, :lastname, :tel, :citizen_id, :password, :role)";
            $stmt = $pdo->prepare($sql);
            
            // ผูกค่าพารามิเตอร์
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':citizen_id', $citizen_id);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);
            
            // ประมวลผลคำสั่ง SQL
            $stmt->execute();

            echo "เพิ่มข้อมูลเรียบร้อยแล้ว";
        } catch(PDOException $e) {
            echo "เกิดข้อผิดพลาดในการเพิ่มข้อมูล: " . $e->getMessage();
        }

        // ปิดการเชื่อมต่อฐานข้อมูล
        $pdo = null;
    }
}
?>
