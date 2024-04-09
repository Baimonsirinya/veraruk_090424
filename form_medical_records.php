<?php

session_start();
include "connect.php";
include "session/sessionlogin.php";
?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <!-- meta data -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!--font-family-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&amp;subset=devanagari,latin-ext" rel="stylesheet">
    <link rel="stylesheet" href="css/medical_records.css">
    <!-- title of site -->
    <title>รายละเอียดเวชระบียน</title>

      <script>
        document.addEventListener("DOMContentLoaded", function() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "ConnectDB/run_patient_id.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    document.getElementById("patient_id").value = xhr.responseText;
                } else {
                    console.error("เกิดข้อผิดพลาดในการรับข้อมูล patient_id");
                }
            }
        };
        xhr.send();
        });
        </script>

</head>

<body>
    <header>
        <?php include "Navbar_Customers.php" ?>
    </header>

    <form id="form_data" method="post" >
        <div class="position-id">
            <label> รหัสผู้ป่วย : <input type="text"  id="patient_id" name="patient_id" readonly></label><br>
        </div>
        <div class="form-container">
            <p>ข้อมูลส่วนตัว</p>
            <div class="card-data-personal">
                <div class="column" style="width: 200px;">
                    <label>ชื่อ-สกุล : <input type="text" name="name_patient" value="<?php echo $username; ?>" readonly></label>
                    <label>วัน เดือน ปี เกิด : <input type="date" id="date_of_birth" name="date_of_birth"></label>
                    <label>สถานภาพ : <input type="checkbox" value="โสด" name="status"> โสด
                        <input type="checkbox" value="สมรส" name="status"> สมรส
                        <input type="checkbox" value="หย่าร้าง" name="status"> หย่าร้าง
                    </label>
                    <label>ศาสนา : <input type="text" name="religion" style="width: 50px;"></label>
                </div>
                <div class="column">
                    <label>เลขบัตรประชาชน : <input type="text" name="id_card" value="<?php echo $citizen_id; ?>" style="width: 120px;" readonly></label>
                    <label>อายุ : <input type="text" name="age" id="age" pattern="\d{1,2}" title="กรุณากรอกเป็นตัวเลข" style="width: 30px;"> ปี</label>
                    <label>เชื้อชาติ : <input type="text" name="ethnicity" style="width: 50px;"></label>
                </div>
                <div class="column">
                    <label>เพศ : <select id="typegender" name="gender">
                        <option value="หญิง">หญิง</option>
                        <option value="ชาย">ชาย</option>
                    </select></label>
                    <label>อาชีพ : <input type="text" name="career"></label>
                    <label>สัญชาติ : <input type="text" name="nationality" style="width: 50px;"></label>  
                </div>
            </div>

            <p>ข้อมูลที่อยู่</p>
            <div class="card-data-address">
                <div class="column" style="width: 100px;">
                    <label>ที่อยู่ : <input type="text" name="address"></label>
                    <label>ตำบล : <input type="text" name="sub_district"></label>
                </div>
                <div class="column" style="width: 100px;">
                    <label>จังหวัด : <input type="text" name="province"></label>
                    <label>รหัสไปรษณีย์ : <input type="text" name="zip_code" pattern="\d{5}" title="กรุณากรอกเป็นตัวเลข 5 หลัก" style="width: 100px;"></label>
                </div>
                <div class="column">
                    <label>อำเภอ : <input type="text" name="district"></label>
                    <label>เบอร์โทรศัพท์ : <input type="text" pattern="[0-9]{10}" title="กรุณากรอกเป็นตัวเลข 10 หลัก "name="tel"></label><br>
                </div>
            </div>

            <p>ข้อมูลอื่นๆ</p>
            <div class="card-data-other">
                <div class="column" style="width: 100px;">
                    <label>โรคประจำตัว : <input type="text" name="congenital_disease"></label>
                    <label>ประวัติแพ้ยา : <input type="text" name="allergy"></label>
                    <label>ประวัติการผ่าตัด : <input type="text" name="surgery_history"></label>
                    <label>ประวัติการประสบอุบัติเหตุ : <input type="text" name="accident_history"></label>
                    <div class="note">หมายเหตุ : หากไม่มีประวัติให้ใช้สัญลักษณ์ "-"</div>
                </div>
            </div>
            <div class="center">
                <button id="submit" type="submit">เพิ่มประวัติผู้ป่วย</button>
            </div>
    </form>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>

    function calculateAge() {
        // รับค่าวันเดือนปีเกิด
        var dob = new Date(document.getElementById("date_of_birth").value);
        // รับวันที่ปัจจุบัน
        var now = new Date();
        // คำนวณอายุโดยลบปีเกิดจากปีปัจจุบัน
        var age = now.getFullYear() - dob.getFullYear();
        // ตรวจสอบว่าผ่านเดือนเกิดแล้วหรือยัง
        var monthDiff = now.getMonth() - dob.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && now.getDate() < dob.getDate())) {
            age--;
        }
        // แสดงผลลัพธ์ใน input ของอายุ
        document.getElementById("age").value = age;
    }

    document.getElementById("date_of_birth").addEventListener("change", calculateAge);


        document.addEventListener("DOMContentLoaded", function() {
            var form = document.getElementById("form_data");
            form.addEventListener("submit", function(event) {
                event.preventDefault(); 
                
                
                var allFieldsFilled = true;
                var formInputs = form.querySelectorAll("input[type='text'], input[type='date'], input[type='checkbox'], select");
                formInputs.forEach(function(input) {
                    if (input.value.trim() === "" && !input.disabled) {
                        allFieldsFilled = false;
                        return;
                    }
                });
                
                if (!allFieldsFilled) {
                    // Show alert if any field is empty
                    Swal.fire({
                        icon: 'error',
                        title: 'กรุณากรอกข้อมูลให้ครบทุกช่อง',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }

                var formData = new FormData(form);

                // AJAX request
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "ConnectDB/add_medical_records.php", true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Show success message using SweetAlert
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                history.back();
                            });
                        } else {
                            console.error("เกิดข้อผิดพลาดในการบันทึกข้อมูล");
                        }
                    }
                };
                xhr.send(formData);
            });
        });
    </script>
</body>
</html>




