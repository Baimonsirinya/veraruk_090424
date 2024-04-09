<?php
    include "../connect.php";
    include "../session/sessionlogin.php";
?>


<?php
    $stmt = $pdo->prepare("SELECT * FROM medical_records"); 
    $stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" href="admin_medrecords.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <script>
        document.addEventListener("DOMContentLoaded", function() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "../ConnectDB/run_patient_id.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    document.getElementById("patient_id_new").value = xhr.responseText;
                } else {
                    console.error("เกิดข้อผิดพลาดในการรับข้อมูล patient_id");
                }
            }
        };
        xhr.send();
    });

        function showform(){
                // generateAndSendNumberKey();
                var popup = document.getElementById("bd-modal");
                popup.style.display = "flex";
            };
        function close_modal(){
            document.getElementById('close')
            var popup = document.getElementById("bd-modal");
            popup.style.display = "none"; 
        };

  </script>

</head>

<header>
   <?php include('Navbar_Admin.php');?>
</header>


<body>
    <div class="position-search">
        <input type="text" id="search-box" placeholder="ค้นหา" ></input><br>
        <a href="#" id="button-add" class="button-add" onclick="showform()" > เพิ่มประวัติผู้ป่วยใหม่ + </a>
    </div>
    <div class="container">
    <table id = 'all_medical'>
        <tr>
            <th>รหัสผู้ป่วย</th>
            <th>ชื่อผู้ป่วย</th>
            <th>   </th>
        </tr>
        <?php while($row=$stmt->fetch()): ?>
            <tr>
                <td><?=$row ["patient_id"] ?></td>
                <td><?=$row ["name_patient"] ?></td>
                <td><a class="button-view" href="admin_general.php?patient_id=<?=$row ["patient_id"] ?>">view</a></td>    
            </tr>
        <?php endwhile; ?>
        </table>
</div>


    <div class="bg-modal"id="bd-modal" style="display:none;">
        <div class="modal-content">
            <form id="form_data" method="POST" >
            <span onclick="close_modal()" class="close">&times;</span><br>
                <label> รหัสผู้ป่วย: <input type="text"  id="patient_id_new" name="patient_id_new" readonly></label><br>
                <label>ชื่อ-สกุล :<input type="text" id="name_patient" name="name_patient"></label>   
                <label>เลขบัตรประชาชน : <input  type="text" id="id_card" name="id_card"></label><br>
                <label>วัน เดือน ปี เกิด : <input type="date" id="date_of_birth" name="date_of_birth"></label>
                <label>อายุ : <input type="number" id="age" name="age" readonly> ปี</label><br>
                <label>เพศ : <select id="typegender" name="gender">
                    <option value="หญิง">หญิง</option>
                    <option value="ชาย">ชาย</option></select></label>
                    <label>อาชีพ : <input  type="text" name="career">
                <label>สถานภาพ : <input type="checkbox" value="โสด" name="status"> โสด
                    <input type="checkbox" value="สมรส" name="status">สมรส
                    <input type="checkbox" value="หย่าร้าง" name="status"> หย่าร้าง</label><br>
                <label>เชื้อชาติ : <input type="text" name="ethnicity"></label>
                <label>สัญชาติ : <input type="text" name="nationality"></label>
                <label>ศาสนา : <input type="text" name="religion"></label><br>
                <label>ที่อยู่ : <input type="text" name="address"></label>
                <label>จังหวัด : <input type="text" name="province"></label>
                <label>อำเภอ : <input type="text" name="district"></label><br>
                <label>ตำบล : <input type="text" name="sub_district"></label>
                <label>รหัสไปรษณีย์ : <input type="text" name="zip_code"></label>
                <label>เบอร์โทรศัพท์ : <input type="text" pattern="[0-9]{10}" name="tel"></label><br>
                <label>โรคประจำตัว : <input type="text" name="congenital_disease"></label><br>
                <label>ประวัติแพ้ยา : <input type="text" name="allergy"></label><br>
                <label>ประวัติการผ่าตัด : <input type="text" name="surgery_history"></label><br>
                <label>ประวัติการประสบอุบัติเหตุ : <input type="text" name="accident_history"></label><br>
                <input id="submit" type="submit" value="เพิ่มประวัติผู้ป่วย">
            </form>
        </div>
    </div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    // เรียกใช้ฟังก์ชั่น calculateAge เมื่อมีการเปลี่ยนแปลงในวันเกิด
    document.getElementById("date_of_birth").addEventListener("change", calculateAge);
    document.getElementById("date_of_birth").addEventListener("change", calculateAge);

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('form_data').addEventListener('submit', function(event) {
            event.preventDefault(); // ป้องกันการ submit แบบฟอร์มแบบธรรมดา

            // รับข้อมูลจากแบบฟอร์ม
            var formData = new FormData(this);

            // ส่งข้อมูลไปยังไฟล์ PHP ด้วย AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../ConnectDB/save_medical_patient.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                    showConfirmButton: false,
                    timer: 1000
                }).then(() => {
                    close_modal();
                    location.reload();
                });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถบันทึกข้อมูลได้',
                    }).then(() => {
                        close_modal();
                        location.reload();
                    });

                    console.error('เกิดข้อผิดพลาดในการส่งข้อมูล: ' + xhr.status);
                }
            };
            xhr.onerror = function() {
                // กรณีเกิดข้อผิดพลาดในการส่งข้อมูล
                console.error('เกิดข้อผิดพลาดในการส่งข้อมูล');
            };
            xhr.send(formData);
        });
    });

       document.addEventListener("DOMContentLoaded", function() {
    // เมื่อมีการเปลี่ยนแปลงใน input type="date"
        document.getElementById("search-box").addEventListener("input", function() {
            // เรียกฟังก์ชัน fetchSearchData() เมื่อมีการเปลี่ยนแปลง
            fetchSearchData();
        });
    });

    function fetchSearchData() {
        // รับค่าคำค้นหา
        var keyword = document.getElementById("search-box").value;

        var xhr = new XMLHttpRequest();
        
        // กำหนดเมธอดและ URL
        xhr.open("POST", "../ConnectDB/search_medical.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // เมื่อข้อมูลส่งกลับมา
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var searchData = xhr.responseText;
                    // เอา searchData ไปแสดงแทนที่ all_appointment
                    document.getElementById("all_medical").innerHTML = searchData;
                } else {
                    console.error("เกิดข้อผิดพลาดในการเรียกข้อมูล");
                }
            }
        };

        // ส่งคำค้นหาไปยังไฟล์ PHP
        xhr.send("keyword=" + keyword);
        }


</script>

</body>    
</html>