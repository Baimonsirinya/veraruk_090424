<?php
include "../connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากแบบฟอร์ม
    $patient_id = $_POST["patient_id"];
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

    // Prepare SQL statement to insert data into database
    $sql = "INSERT INTO medical_records (patient_id, name_patient, date_of_birth, status, religion, id_card, age, ethnicity, gender, career,  nationality,  address, sub_district, province,  zip_code, district,  tel, congenital_disease, allergy, surgery_history, accident_history) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(1, $patient_id);
        $stmt->bindParam(2, $name_patient);
        $stmt->bindParam(3, $date_of_birth);
        $stmt->bindParam(4, $status);
        $stmt->bindParam(5, $religion);
        $stmt->bindParam(6, $id_card);
        $stmt->bindParam(7, $age);
        $stmt->bindParam(8, $ethnicity);
        $stmt->bindParam(9, $gender);
        $stmt->bindParam(10, $career);
        $stmt->bindParam(11, $nationality);
        $stmt->bindParam(12, $address);
        $stmt->bindParam(13, $sub_district);
        $stmt->bindParam(14, $province);
        $stmt->bindParam(15, $zip_code);
        $stmt->bindParam(16, $district);
        $stmt->bindParam(17, $tel);
        $stmt->bindParam(18, $congenital_disease);
        $stmt->bindParam(19, $allergy);
        $stmt->bindParam(20, $surgery_history);
        $stmt->bindParam(21, $accident_history);
    

    // ทำการ execute คำสั่ง SQL
    if ($stmt->execute()) {
        echo "success"; // ส่งกลับข้อความเพื่อแสดงว่าการเพิ่มข้อมูลสำเร็จ
    } else {
        echo "error"; // ส่งกลับข้อความเพื่อแสดงว่าเกิดข้อผิดพลาดในการเพิ่มข้อมูล
    }
    }
}
?>