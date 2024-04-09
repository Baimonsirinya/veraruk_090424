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
  <link rel="stylesheet" href="doctor_selectpatient.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<script>

    function close_modal1(){
        document.getElementById('close')
        var popup = document.getElementById("popup-form-appoint");
        popup.style.display = "none"; 
    };


    function showPopup(patientId, patientName, TelPatient) {
        var popup = document.getElementById("popup-form-appoint");
        popup.style.display = "flex";
        var patientIdInput = document.getElementById("patient_id");
        patientIdInput.value = patientId;
        var patientNameInput = document.getElementById("patient_name");
        patientNameInput.value = patientName;
        var TelPatientInput = document.getElementById("tel");
        TelPatientInput.value = TelPatient;
    }

</script>


</head>

<header>
   <?php include('Navbar_Doctor.php');?>
</header>

<body>
    <div class="container">
        <div class="position-search">
            <input type="text" id="search-box" placeholder="ค้นหา">
        </div>
        <div class="content">
            <table id="all_appointment">
                <tr>
                    <th>รหัสผู้ป่วย</th>
                    <th>ชื่อผู้ป่วย</th>
                    <th>   </th>
                </tr>
                <?php while($row=$stmt->fetch()): ?>
                    <tr>
                        <td><?=$row ["patient_id"] ?></td>
                        <td><?=$row ["name_patient"] ?></td>
                        <td><button class="button-booking" onclick="showPopup('<?=$row['patient_id']?>', '<?=$row['name_patient']?>', '<?=$row['tel']?>')">เพิ่มลงในการนัดหมาย</button></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

<div id="popup-form-appoint" class="popup-form">
        <form id="patientForm" class="form-container">
            <span onclick="close_modal1()" class="close">&times;</span>
            <h2>กรอกข้อมูลเพื่อเพิ่มการนัดหมาย</h2>
            <hr>
            <div class="data-patient">
                <label>ชื่อผู้ป่วย : <input type="text" id ="patient_name" name="patient_name" class="input-field" style="width: 200px;" readonly></label><br>
                <label>รหัสผู้ป่วย : <input id = "patient_id" name="patient_id" class="input-field" style="width: 200px;" readonly></label><br>
                <input type="hidden" id ="tel" name="tel" class="input-field" style="width: 200px;" readonly>
                <label for="dateforBook">วันที่เข้ารับการรักษา :</label>
                <input type="text" id="dateforBook" name="date" class="input-date" placeholder="&#xf073; เลือกวันที่" 
                style="width: 100px; font-family: 'Font Awesome 5 Free';" readonly><br>
                <label for="doctor">เลือกแพทย์ : </label>
                <select id="doctor" name="doctor">
                    <option value="">-- ระบุแพทย์ --</option>
                    <?php
                        // ทำการสร้างคำสั่ง SQL เพื่อดึงชื่อหมอออกมาจากตาราง users โดยเลือกเฉพาะผู้ใช้ที่มีบทบาทเป็นหมอ (role = doctor)
                        $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'doctor'");
                        $stmt->execute();
                        // วนลูปเพื่อสร้างตัวเลือกใน dropdown โดยใช้ชื่อของหมอเป็นค่าในตัวเลือก
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='".$row['name'] . ' ' . $row['lastname']."'>".$row['name'] . ' ' . $row['lastname']."</option>";
                        }
                    ?>
                </select><br>
                <label for="time">ระบุเวลา : </label>
                <select id="time" name="time" class="text-box">
                    <option value="">-- ระบุเวลา --</option>
                    <option value="09.00 น.">09.00 น.</option>
                    <option value="10.00 น.">10.00 น.</option>
                    <option value="11.00 น.">11.00 น.</option>
                    <option value="12.00 น.">12.00 น.</option>
                    <option value="13.00 น.">13.00 น.</option>
                    <option value="14.00 น.">14.00 น.</option>
                    <option value="15.00 น.">15.00 น.</option>
                    <option value="16.00 น.">16.00 น.</option>
                    <option value="17.00 น.">17.00 น.</option>
                    <option value="18.00 น.">18.00 น.</option>
                    <option value="19.00 น.">19.00 น.</option>
                </select>
            </div>
            <div class="position-button">
                <button type="button" id="submitFormBtn" class="submit-btn" onclick="submitForm()">บันทึกข้อมูล</button>
            </div>
           
        </form>
    </div>

</body>    

<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    function refreshPage() {
        window.location.reload(); // รีเฟรชหน้า
    }

    flatpickr("#dateforBook", {
    dateFormat: "Y-m-d", // กำหนดรูปแบบวันที่ให้เป็น YYYY-MM-DD
    minDate: "today", // กำหนดให้สามารถเลือกวันที่เริ่มต้นได้ตั้งแต่วันนี้
    locale: "th"
    });

    document.getElementById('doctor').disabled = true;
    document.getElementById('time').disabled = true;

    document.getElementById('dateforBook').addEventListener('change', function() {
        // ดึงค่าวันที่ที่ผู้ใช้เลือก
        var selectedDate = new Date(this.value);

        // ตรวจสอบว่าวันที่มีการเปลี่ยนแปลงจริงหรือไม่
        if (this.value !== '') {
            // ตรวจสอบว่าเป็นวันพุธหรือไม่
            if (selectedDate.getDay() === 3) { // 3 คือวันพุธ
                alert('ไม่สามารถเลือกวันพุธได้เนื่องจากเป็นวันหยุดประจำสัปดาห์ ขออภัยในความไม่สะดวก');
                this.value = ''; // ลบค่าวันที่ออก
            }else{
                document.getElementById('doctor').disabled = false;
                document.getElementById('time').disabled = false;
            } 
        }
    });

    document.getElementById('doctor').addEventListener('change', function() {
        var doctorName =  this.value;
        var selectedDate = document.getElementById('dateforBook').value;
    
    // ทำการส่งค่าวันที่ไปยังไฟล์ PHP เพื่อค้นหาเวลาที่ถูกเลือกไปแล้ว
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../ConnectDB/CheckDate.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var selectedTimes = this.responseText;
            removeSelectedTimes(selectedTimes);
            // สร้างอาร์เรย์และแสดงค่าในคอนโซล
            var selectedTimesArray = selectedTimes.split(',');
        } else {
            console.error("เกิดข้อผิดพลาดในการส่งข้อมูล");
        }
    };

    xhr.send("selectedDate=" + selectedDate + "&doctorName=" + doctorName);
});


    function removeSelectedTimes(selectedTimes) {
    var timeDropdown = document.getElementById('time');
    var options = timeDropdown.options;
    for (var i = options.length - 1; i >= 0; i--) {
        if (selectedTimes.includes(options[i].value)) {
            console.log("ค่าใน dropdown ที่จะถูกลบ: ", options[i].value);
            options[i].disabled = true; // เพิ่มความสามารถที่ไม่สามารถเลือกได้
        }
    }
}



function submitForm() {
        // ดึงข้อมูลจากฟอร์ม
        var selectedDate = document.getElementById("dateforBook").value;
        var selectedTime = document.getElementById("time").value;
        var patientId = document.getElementById("patient_id").value;
        var patientName = document.getElementById("patient_name").value;
        var tel = document.getElementById("tel").value
        var doctorName = document.getElementById("doctor").value;
        

        // สร้างออบเจกต์ FormData เพื่อเก็บข้อมูล
        var formData = new FormData();
        formData.append("date", selectedDate);
        formData.append("time", selectedTime);
        formData.append("patient_id", patientId);
        formData.append("patient_name", patientName);
        formData.append("tel", tel);
        formData.append("doctor", doctorName);
        
        // ส่งข้อมูลไปยังไฟล์ PHP ด้วย XMLHttpRequest
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../ConnectDB/AddAppointment.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                var response = xhr.responseText;
                if (response === "บันทึกข้อมูลเรียบร้อยแล้ว") {
                    Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // ปิด popup form หลังจากบันทึกข้อมูลเรียบร้อย
                    close_modal1();
                });
            } else {
                // แสดง SweetAlert เมื่อเกิดข้อผิดพลาดในการบันทึกข้อมูล
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    timer: 1500 // แสดงข้อความผิดพลาดที่ถูกส่งกลับมาจาก PHP
                });
                console.log(response); // แสดงข้อความผิดพลาดในคอนโซลของเบราว์เซอร์
            }
            }
        };
        xhr.send(formData);
    }


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
        xhr.open("POST", "../ConnectDB/search_medicals_appointment.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // เมื่อข้อมูลส่งกลับมา
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var searchData = xhr.responseText;
                    // เอา searchData ไปแสดงแทนที่ all_appointment
                    document.getElementById("all_appointment").innerHTML = searchData;
                } else {
                    console.error("เกิดข้อผิดพลาดในการเรียกข้อมูล");
                }
            }
        };

        // ส่งคำค้นหาไปยังไฟล์ PHP
        xhr.send("keyword=" + keyword);
        }
    
</script>


</html>