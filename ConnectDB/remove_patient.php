<?php
    include "../connect.php";

    // ตรวจสอบว่ามีการส่งค่า patient_id และ date มาหรือไม่
    if(isset($_POST['treatment_id'])) {
        // รับค่า patient_id และ date จากการ POST
        $treatment_id = $_POST['treatment_id'];

        try {
            // เตรียมและ execute คำสั่ง SQL เพื่อลบข้อมูลคนไข้
            $stmt = $pdo->prepare("DELETE FROM appointment_admin_page WHERE treatment_id = ?");
            $stmt->execute([$treatment_id]);

            // เช็คว่ามีแถวที่ถูกลบออกไปหรือไม่
            if($stmt->rowCount() > 0) {
                // หากลบสำเร็จ ส่งค่า "success" กลับไปให้กับ JavaScript
                echo "success";

                // อัพเดทสถานะในตาราง treatment เมื่อคนไข้ได้รับการตรวจเสร็จสิ้น
                $updateStmt = $pdo->prepare("UPDATE treatment SET status = 'ตรวจเสร็จแล้ว' WHERE treatment_id = ?");
                $updateStmt->execute([$treatment_id]);
            } else {
                // หากไม่มีแถวที่ถูกลบออกไป
                echo "ไม่สามารถลบข้อมูลคนไข้ได้";
            }
        } catch (PDOException $e) {
            // หากเกิดข้อผิดพลาดในการทำงานกับฐานข้อมูล
            echo "เกิดข้อผิดพลาด: " . $e->getMessage();
        }
    } else {
        // หากไม่ได้รับค่า patient_id หรือ date มา
        echo "ไม่สามารถทำรายการได้ เนื่องจากไม่มีรหัสคนไข้หรือวันที่ที่ระบุ";
    }
?>
