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
  <link rel="stylesheet" href="admin_selectpatient.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<script>

    function close_modal1(){
        document.getElementById('close')
        var popup = document.getElementById("popup-form-appoint");
        popup.style.display = "none"; 
        window.location.reload();
    };

    function close_modal2(){
        document.getElementById('close')
        var popup = document.getElementById("add_newpatient_appoint");
        popup.style.display = "none"; 
        window.location.reload();
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

    function showform(){
        var popup = document.getElementById("add_newpatient_appoint");
        popup.style.display = "flex";
    }

</script>


</head>

<header>
   <?php include('Navbar_Admin.php');?>
</header>

<body>
<div class="container">
        <div class="sidebar">
            <div class="side-nav">
                <a href="admin_appointment.php" class="sidebar-item" style="color: black;">การนัดหมาย</a>
                <a href="admin_seeDoctor.php" class="sidebar-item" style="color: black;">พบแพทย์</a>
                <a href="#" class="sidebar-item" style="color: black;">จ่ายยา</a>
            </div>
        </div>
<div class="container">
    <div class="content">
        <div class="position-search">
            <input type="text" id="search-box" placeholder="ค้นหา"><br>
            <a href="#" id="button-add" class="button-add" onclick="showform()" > เพิ่มการนัดหมายคนไข้ใหม่ + </a>
        </div>
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
                <input type="hidden" id ="tel" name="tel" class="input-field" style="width: 200px;" readonly><br>
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
                <label for="dateforBook">วันที่เข้ารับการรักษา :</label>
                <input type="text" id="dateforBook" name="date" class="input-date" placeholder="&#xf073; เลือกวันที่" 
                style="width: 100px; font-family: 'Font Awesome 5 Free';" readonly><br>
                <label for="time">ระบุเวลา : </label>
                <select id="time" name="time" class="text-box">
                    <option value="">-- ระบุเวลา --</option>
                    <option value="09.00 น.">09.00 น.</option>
                    <option value="10.00 น.">10.00 น.</option>
                    <option value="11.00 น.">11.00 น.</option>
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


    <div id="add_newpatient_appoint" class="popup_addnew">
        <form id="patientForm" class="form-container-new">
            <span onclick="close_modal2()" class="close">&times;</span>
            <h2>กรอกข้อมูลเพื่อเพิ่มการนัดหมาย</h2>
            <hr>
            <div class="data-patient">
                <label>ชื่อ-สกุล ผู้ป่วย : <input type="text" id ="patient_name_new" name="patient_name_new"  style="width: 200px;"></label><br>
                <label>เบอร์โทร : <input type="text" id ="tel_new" name="tel_new" style="width: 200px;"></label><br>
                <label for="doctor_for_new">เลือกแพทย์ : </label>
                <select id="doctor_for_new" name="doctor_for_new">
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
                <label for="dateforBook_for_new">วันที่เข้ารับการรักษา :</label>
                <input type="text" id="dateforBook_for_new" name="date_for_new" class="input-date" placeholder="&#xf073; เลือกวันที่" 
                style="width: 100px; font-family: 'Font Awesome 5 Free';" readonly><br>
                <label for="time_for_new">ระบุเวลา : </label>
                <select id="time_for_new" name="time_for_new" class="text-box">
                    <option value="">-- ระบุเวลา --</option>
                    <option value="09.00 น.">09.00 น.</option>
                    <option value="10.00 น.">10.00 น.</option>
                    <option value="11.00 น.">11.00 น.</option>
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
                <button type="button" id="submitFormBtnNew" class="submit-btn" onclick="submitFormNew()">บันทึกข้อมูล</button>
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

    document.getElementById('doctor').addEventListener('change', function() {
    var selectedDoctor = this.value;
    var nonDoctorInputs = document.querySelectorAll('.text-box');
    var dateInput = document.getElementById('dateforBook');

    if (selectedDoctor !== '') {
        // ถ้าเลือกหมอแล้วให้ undisable ทุกช่องยกเว้นช่องหมอ
        nonDoctorInputs.forEach(function(input) {
            if (input.id !== 'doctor') {
                input.removeAttribute('disabled');
            }
        });
    } else {
        // ถ้ายังไม่เลือกหมอให้ disable ทุกช่องรวมถึงช่องวันที่ด้วย
        nonDoctorInputs.forEach(function(input) {
            input.setAttribute('disabled', true);
        });
        // นอกจากนี้ยังต้อง disable ช่องวันที่ด้วย
        dateInput.setAttribute('disabled', true);
    }
    });

    flatpickr("#dateforBook", {
    dateFormat: "Y-m-d", // รูปแบบวันที่ที่ต้องการ
    minDate: "today", // กำหนดให้สามารถเลือกวันที่เริ่มต้นได้ตั้งแต่วันนี้
    locale: "th", // กำหนด locale เป็นภาษาไทย
    onChange: function(selectedDates, dateStr, instance) {
        var selectedDate = new Date(dateStr); // แปลงค่าวันที่ที่เลือกใหม่เป็นวัตถุ Date
        var currentTime = new Date(); // เวลาปัจจุบัน
        var currentHour = currentTime.getHours();
        var currentMinute = currentTime.getMinutes();
        var timeDropdown = document.getElementById('time'); 

        // ตรวจสอบว่าวันที่ที่เลือกนั้นเป็นวันที่ปัจจุบันหรือไม่
        if (selectedDate.toDateString() === currentTime.toDateString()) {
            // วนลูปผ่านตัวเลือกใน dropdown
            for (var i = 0; i < timeDropdown.options.length; i++) {
                var option = timeDropdown.options[i];
                var optionValue = option.value; // ดึงค่าเวลาจากตัวเลือก

                // เปรียบเทียบเวลา
                if (optionValue !== '' && isPastTime(optionValue)) {
                    // ถ้าเวลาผ่านไปแล้วให้ disable ตัวเลือก
                    option.disabled = true;
                } else {
                    option.disabled = false; // เปิดให้เลือก
                }
            }
        } else {
            // ถ้าไม่ใช่วันปัจจุบัน ให้เปิดให้เลือกทุกตัวเลือกใน dropdown
            for (var i = 0; i < timeDropdown.options.length; i++) {
                var option = timeDropdown.options[i];
                option.disabled = false; // เปิดให้เลือก
            }
        }
    }
});


    document.getElementById('doctor').addEventListener('change', function() {
        document.getElementById('dateforBook').value = '';
        document.getElementById('time').value = '';
    });

    document.getElementById('dateforBook').addEventListener('change', function() {
        document.getElementById('time').value = '';
    });

    // ฟังก์ชันที่ใช้ในการตรวจสอบว่าเวลาที่เลือกอยู่ในอดีตหรือไม่
    function isPastTime(timeString) {
        // ดึงเวลาปัจจุบัน
        var currentTime = new Date();
        var currentHour = currentTime.getHours();
        var currentMinute = currentTime.getMinutes();

        // แยกวันที่และเวลาจากเวลาที่กำหนด
        var timeParts = timeString.split(' ');
        var selectedHour = parseInt(timeParts[0].split('.')[0]);
        var selectedMinute = parseInt(timeParts[0].split('.')[1]);

        // เปรียบเทียบเวลา
        if (currentHour > selectedHour || (currentHour === selectedHour && currentMinute >= selectedMinute)) {
            return true; // เวลาที่กำหนดอยู่ในอดีต
        }

        return false; // เวลาที่กำหนดยังไม่ผ่านไป
    }


    document.getElementById('dateforBook').addEventListener('change', function() {
        // ดึงค่าวันที่ที่ผู้ใช้เลือก
        var selectedDate = new Date(this.value);

        // ตรวจสอบว่าวันที่มีการเปลี่ยนแปลงจริงหรือไม่
        if (this.value !== '') {
            // ตรวจสอบว่าเป็นวันพุธหรือไม่
            if (selectedDate.getDay() === 3) { // 3 คือวันพุธ
                alert('ไม่สามารถเลือกวันพุธได้เนื่องจากเป็นวันหยุดประจำสัปดาห์ ขออภัยในความไม่สะดวก');
                this.value = ''; // ลบค่าวันที่ออก
            } 
        }
    });

    document.getElementById('dateforBook').addEventListener('change', function() {
        var selectedDate = this.value;
        var doctorName = document.getElementById('doctor').value;

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



// --------------------------------------------------------- patient_new---------------------------------------------------------//

    flatpickr("#dateforBook_for_new", {
    dateFormat: "Y-m-d", // รูปแบบวันที่ที่ต้องการ
    minDate: "today", // กำหนดให้สามารถเลือกวันที่เริ่มต้นได้ตั้งแต่วันนี้
    locale: "th", // กำหนด locale เป็นภาษาไทย
    onChange: function(selectedDates, dateStr, instance) {
        var selectedDate = new Date(dateStr); // แปลงค่าวันที่ที่เลือกใหม่เป็นวัตถุ Date
        var currentTime = new Date(); // เวลาปัจจุบัน
        var currentHour = currentTime.getHours();
        var currentMinute = currentTime.getMinutes();
        var timeDropdown = document.getElementById('time_for_new'); 

        // ตรวจสอบว่าวันที่ที่เลือกนั้นเป็นวันที่ปัจจุบันหรือไม่
        if (selectedDate.toDateString() === currentTime.toDateString()) {
            // วนลูปผ่านตัวเลือกใน dropdown
            for (var i = 0; i < timeDropdown.options.length; i++) {
                var option = timeDropdown.options[i];
                var optionValue = option.value; // ดึงค่าเวลาจากตัวเลือก

                // เปรียบเทียบเวลา
                if (optionValue !== '' && isPastTime(optionValue)) {
                    // ถ้าเวลาผ่านไปแล้วให้ disable ตัวเลือก
                    option.disabled = true;
                } else {
                    option.disabled = false; // เปิดให้เลือก
                }
            }
        } else {
            // ถ้าไม่ใช่วันปัจจุบัน ให้เปิดให้เลือกทุกตัวเลือกใน dropdown
            for (var i = 0; i < timeDropdown.options.length; i++) {
                var option = timeDropdown.options[i];
                option.disabled = false; // เปิดให้เลือก
            }
        }
    }
});




    document.getElementById('doctor_for_new').addEventListener('change', function() {
        document.getElementById('dateforBook_for_new').value = '';
        document.getElementById('time_for_new').value = '';
    });

    document.getElementById('dateforBook_for_new').addEventListener('change', function() {
        document.getElementById('time_for_new').value = '';
    });

    // ฟังก์ชันที่ใช้ในการตรวจสอบว่าเวลาที่เลือกอยู่ในอดีตหรือไม่
    function isPastTime(timeString) {
        // ดึงเวลาปัจจุบัน
        var currentTime = new Date();
        var currentHour = currentTime.getHours();
        var currentMinute = currentTime.getMinutes();

        // แยกวันที่และเวลาจากเวลาที่กำหนด
        var timeParts = timeString.split(' ');
        var selectedHour = parseInt(timeParts[0].split('.')[0]);
        var selectedMinute = parseInt(timeParts[0].split('.')[1]);

        // เปรียบเทียบเวลา
        if (currentHour > selectedHour || (currentHour === selectedHour && currentMinute >= selectedMinute)) {
            return true; // เวลาที่กำหนดอยู่ในอดีต
        }

        return false; // เวลาที่กำหนดยังไม่ผ่านไป
    }


    document.getElementById('dateforBook_for_new').addEventListener('change', function() {
        // ดึงค่าวันที่ที่ผู้ใช้เลือก
        var selectedDate = new Date(this.value);

        // ตรวจสอบว่าวันที่มีการเปลี่ยนแปลงจริงหรือไม่
        if (this.value !== '') {
            // ตรวจสอบว่าเป็นวันพุธหรือไม่
            if (selectedDate.getDay() === 3) { // 3 คือวันพุธ
                alert('ไม่สามารถเลือกวันพุธได้เนื่องจากเป็นวันหยุดประจำสัปดาห์ ขออภัยในความไม่สะดวก');
                this.value = ''; // ลบค่าวันที่ออก
            } 
        }
    });

    document.getElementById('dateforBook_for_new').addEventListener('change', function() {
        var selectedDate = this.value;
        var doctorName = document.getElementById('doctor_for_new').value;

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../ConnectDB/CheckDate.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var selectedTimes = this.responseText;
                removeSelectedTimes_for_new(selectedTimes);
                // สร้างอาร์เรย์และแสดงค่าในคอนโซล
                var selectedTimesArray = selectedTimes.split(',');
            } else {
                console.error("เกิดข้อผิดพลาดในการส่งข้อมูล");
            }
        };

        xhr.send("selectedDate=" + selectedDate + "&doctorName=" + doctorName);
    });

    function removeSelectedTimes_for_new(selectedTimes) {
    var timeDropdown = document.getElementById('time_for_new');
    var options = timeDropdown.options;
    for (var i = options.length - 1; i >= 0; i--) {
        if (selectedTimes.includes(options[i].value)) {
            console.log("ค่าใน dropdown ที่จะถูกลบ: ", options[i].value);
            options[i].disabled = true; // เพิ่มความสามารถที่ไม่สามารถเลือกได้
        }
    }
}



    function updateAvailableTimes(selectedTimesArray) {
        var timeDropdown = document.getElementById('time_for_new');
        var options = timeDropdown.options;
        for (var i = 0; i < options.length; i++) {
            if (selectedTimesArray.includes(options[i].value)) {
                options[i].disabled = true;
            } else {
                options[i].disabled = false;
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
                console.log(response);
                console.log(xhr.readyState);
                if (response === "บันทึกข้อมูลเรียบร้อยแล้ว") {
                    Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // ปิด popup form หลังจากบันทึกข้อมูลเรียบร้อย
                    close_modal1();
                    location.reload();
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


    function submitFormNew() {
    // โค้ดส่วนอื่น ๆ ที่ใช้สำหรับรับข้อมูลจากฟอร์ม

    // ส่งข้อมูลไปยังไฟล์ PHP ด้วย XMLHttpRequest
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../ConnectDB/add_appointment_newpatient.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            // เมื่อการร้องขอเสร็จสมบูรณ์
            console.log(this.responseText); // แสดงข้อมูลที่ได้รับกลับมาจากไฟล์ PHP
            if (this.responseText === "success") {
                // ถ้าการบันทึกสำเร็จ
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลสำเร็จ',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
            } else {
                // ถ้าการบันทึกไม่สำเร็จ
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถบันทึกข้อมูลได้ โปรดลองอีกครั้ง',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
        }
    };
        // รับค่าของฟอร์มและส่งข้อมูลแบบ URL-encoded format
        var patientName = document.getElementById("patient_name_new").value;
        var tel = document.getElementById("tel_new").value;
        var dateForNew = document.getElementById("dateforBook_for_new").value;
        var doctorForNew = document.getElementById("doctor_for_new").value;
        var timeForNew = document.getElementById("time_for_new").value;
        xhr.send("&patient_name_new=" + encodeURIComponent(patientName) + "&tel_new=" + encodeURIComponent(tel) + "&date_for_new=" + encodeURIComponent(dateForNew) + "&doctor_for_new=" + encodeURIComponent(doctorForNew) + "&time_for_new=" + encodeURIComponent(timeForNew));
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