<?php
    // เชื่อมต่อฐานข้อมูล
    include "../connect.php";

    // ตรวจสอบว่ามีการส่งค่ามาจากฟอร์มหรือไม่
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // ดึงค่าจากฟอร์ม
        $name_patient = $_POST["name_patient"];
        $date_of_birth = $_POST["date_of_birth"];
        $status = $_POST["status"];
        $religion = $_POST["religion"];
        $id_card = $_POST["id_card"];
        $age = $_POST["age"];
        $ethnicity = $_POST["ethnicity"];
        $gender = $_POST["gender"];
        $career = $_POST["career"];
        $nationality = $_POST["nationality"];
        $address = $_POST["address"];
        $sub_district = $_POST["sub_district"];
        $province = $_POST["province"];
        $zip_code = $_POST["zip_code"];
        $district = $_POST["district"];
        $tel = $_POST["tel"];
        $congenital_disease = $_POST["congenital_disease"];
        $allergy = $_POST["allergy"];
        $surgery_history = $_POST["surgery_history"];
        $accident_history = $_POST["accident_history"];

        // เตรียมคำสั่ง SQL สำหรับการแก้ไขข้อมูลในฐานข้อมูล
        $stmt = $pdo->prepare("UPDATE medical_records SET 
            name_patient = ?, date_of_birth = ?, status = ?, religion = ?, 
            age = ?, ethnicity = ?, gender = ?, career = ?, nationality = ?, 
            address = ?, sub_district = ?, province = ?, zip_code = ?, 
            district = ?, tel = ?, congenital_disease = ?, allergy = ?, 
            surgery_history = ?, accident_history = ? WHERE id_card = ?");

        // ทำการผูกค่ากับพารามิเตอร์
        $stmt->bindParam(1, $name_patient);
        $stmt->bindParam(2, $date_of_birth);
        $stmt->bindParam(3, $status);
        $stmt->bindParam(4, $religion);
        $stmt->bindParam(5, $age);
        $stmt->bindParam(6, $ethnicity);
        $stmt->bindParam(7, $gender);
        $stmt->bindParam(8, $career);
        $stmt->bindParam(9, $nationality);
        $stmt->bindParam(10, $address);
        $stmt->bindParam(11, $sub_district);
        $stmt->bindParam(12, $province);
        $stmt->bindParam(13, $zip_code);
        $stmt->bindParam(14, $district);
        $stmt->bindParam(15, $tel);
        $stmt->bindParam(16, $congenital_disease);
        $stmt->bindParam(17, $allergy);
        $stmt->bindParam(18, $surgery_history);
        $stmt->bindParam(19, $accident_history);
        $stmt->bindParam(20, $id_card);

        // ทำการ execute คำสั่ง SQL
        $stmt->execute();

        // ตรวจสอบว่ามีแถวที่ถูกแก้ไขในฐานข้อมูลหรือไม่
        if ($stmt->rowCount() > 0) {
            // ถ้ามีให้แสดงข้อความว่า "แก้ไขข้อมูลสำเร็จ"
            echo "แก้ไขข้อมูลสำเร็จ";
        } else {
            // ถ้าไม่มีให้แสดงข้อความว่า "ไม่สามารถแก้ไขข้อมูลได้"
            echo "ไม่สามารถแก้ไขข้อมูลได้";
        }
    }
?>
