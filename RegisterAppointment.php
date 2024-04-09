<?php

session_start();
include "connect.php";
include "session/sessionlogin.php";

if(isset($_SESSION['citizen_id'])) {
    $citizen_id = $_SESSION['citizen_id'];

    try {
        
        $sql = "SELECT patient_id FROM medical_records WHERE id_card = :citizen_id";
        
        // เตรียมและ execute คำสั่ง SQL
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':citizen_id', $citizen_id, PDO::PARAM_STR);
        $stmt->execute();

        // ดึงข้อมูล patient_id ออกมาจากผลลัพธ์
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result) {
            $patient_id = $result['patient_id'];
        } 
    } catch(PDOException $e) {
        echo "เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: " . $e->getMessage();
    }
    // ปิดการเชื่อมต่อฐานข้อมูล
    $pdo = null;
} 
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/RegisterAppointment.css">
   
</head>
<title>การนัดหมาย</title>
<body>
    <header>
        <?php include "Navbar_Customers.php" ?>
    </header>
    <div class="imageposition">
        <img src="images/01.jpg" width="350" height="300" >
    </div>
    
    <div class="step-appoint">
        <ul class="step">
            <li class="current" data-step="1"><span>ขั้นตอนที่ 1</span></li>
            <li data-step="2"><span>ขั้นตอนที่ 2</span></li>
            <li data-step="3"><span>ขั้นตอนที่ 3</span></li>
        </ul>
    </div>


    <div id="ContainerDoctor">
        <!-- เนื้อหา HTML จากไฟล์ที่โหลดจะแสดงที่นี่ -->
    </div>
    <div id="ShowTextSuccess">
        <p>การนัดหมายเสร็จสิ้นแล้ว ขอบคุณที่นัดหมายค่ะ</p>
    </div>

    <div class="card" id="showFormAppoint">
        <div class="card-header">นัดหมายและพบแพทย์</div>
        
        <form class="container-ID" id="formAppoint" method="post" onsubmit="return validateForm();">
            <div class="positon-doctor">
                <div class="doctorname-style" id="DoctorNameDiv"></div>
            </div>

            <input type="hidden" id="DoctorName" name="doctor" class="text-box" style="width: 150px;" readonly>
            <input type="hidden" id="patient-id" name="patient_id" class="text-box" style="width: 150px;" value="<?php echo $patient_id; ?>" readonly>


            <p id="font-ID">วันที่ทำการนัดหมาย :
                <!-- ใช้ input แบบ text และเรียกใช้ Flatpickr library เพื่อแสดงปฏิทิน -->
                <input type="text" id="dateforBook" name="date" class="text-box" placeholder="&#xf073; เลือกวันที่" 
                style="width: 120px; font-family: 'Font Awesome 5 Free';" readonly>
            </p>
            <p id="font-ID">เวลานัดหมาย :
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
            </p>
            <p id="font-ID">ชื่อ-สกุล :
                <input type="text" id="patientName" name="patient_name" class="text-box" placeholder="กรุณากรอกข้อมูล" style="width: 250px;" 
                pattern="[ก-๙ะ-์A-Za-z\s]+" title="กรุณากรอกเป็นตัวอักษรเท่านั้น" value="<?php echo $_SESSION['username']; ?>" readonly>
            </p>
            <p id="font-ID">เบอร์โทร :
                <input type="tel" id="phoneNumber" name="tel" class="text-box" placeholder="กรุณากรอกเบอร์โทรศัพท์" style="width: 150px;">
            </p>
            <button class="button-submit">นัดหมาย</button><br><br>
            <a class="button-goback" onclick="goBack()"><i class="fa-solid fa-circle-chevron-left"></i>ย้อนกลับ<a><br><br>
        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    function checkNonDateInputs() {
        // เลือกทุกช่อง input ที่ไม่ใช่วันที่
        var nonDateInputs = document.querySelectorAll('.text-box:not(#dateforBook)');
        var selectedDate = document.getElementById('dateforBook').value;

        // ถ้ายังไม่มีการเลือกวันที่
        if (selectedDate === '') {
            // ให้ทำการ disable ทุกช่อง input ที่ไม่ใช่วันที่
            nonDateInputs.forEach(function(input) {
                input.setAttribute('disabled', true);
            });
        } else {
            // ถ้ามีการเลือกวันที่ ให้ทำการ enable ทุกช่อง input ที่ไม่ใช่วันที่
            nonDateInputs.forEach(function(input) {
                input.removeAttribute('disabled');
            });
        }
    }

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

        // ตรวจสอบและปรับปรุง attribute "disabled" ในช่องที่ไม่ใช่วันที่
        checkNonDateInputs();

    }
});

    // เรียกใช้ฟังก์ชัน checkNonDateInputs() เมื่อโหลดหน้าเว็บเสร็จสมบูรณ์
    checkNonDateInputs();

    // เรียกใช้ฟังก์ชัน checkNonDateInputs() เมื่อมีการเปลี่ยนแปลงในวันที่
    document.getElementById('dateforBook').addEventListener('change', function() {
        document.getElementById('time').value = '';
        checkNonDateInputs();
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




    document.addEventListener("DOMContentLoaded", function() {
    // โหลดเนื้อหาที่ต้องการแสดงลงใน div id="ContainerDoctor"
    loadFormContent('get_doctors.php'); // เปลี่ยน URL ตามที่เหมาะสม
    });

    // ฟังก์ชันเพื่อโหลดเนื้อหา HTML หรือ PHP ลงใน div id="ContainerDoctor"
    function loadFormContent(url) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("ContainerDoctor").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", url, true);
        xhttp.send();
    }

    function makeAppointment(button) {
    var doctorName = button.getAttribute('data-doctor-id');
    // ตรวจสอบค่า id ของแพทย์ที่ถูกส่งมา
    console.log('นัดหมายกับแพทย์: ' + doctorName);

    document.getElementById('DoctorName').value = doctorName;
    document.getElementById('DoctorNameDiv').innerText = doctorName;

    document.querySelector('.step [data-step="2"]').classList.add('current');
    document.getElementById("ContainerDoctor").style.display = "none";
    document.getElementById("showFormAppoint").style.display = "block";

}

    function validateForm() {
        var selectedDate = document.getElementById('dateforBook').value;
        var selectedTime = document.getElementById('time').value;
        var patientName = document.getElementById('patientName').value;
        var phoneNumber = document.getElementById('phoneNumber').value;

        if (selectedDate === '' || selectedTime === '' || patientName === '' || phoneNumber === '') {
            Swal.fire({
                icon: 'error',
                title: 'กรุณากรอกข้อมูลให้ครบทุกช่อง',
                showConfirmButton: false,
                timer: 1500
            });
            return false;
        }

        // if (selectedTime === '--ระบุเวลา--') {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'กรุณาเลือกเวลานัดหมาย',
        //         showConfirmButton: false,
        //         timer: 1500
        //     });
        //     return false;
        // }

        var phonePattern = /^[0-9]{10}$/;
        if (!phonePattern.test(phoneNumber)) {
            Swal.fire({
                icon: 'error',
                title: 'กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง (10 หลัก)',
                showConfirmButton: false,
                timer: 1500
            });
            return false;
        }

        return true;
    }

    //ส่วนบันทึกข้อมูล

    document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('formAppoint'); // หรือใช้ document.querySelector('#formAppoint');
    
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // ป้องกันการส่งฟอร์มโดยปกติ
        
        // ตรวจสอบความถูกต้องของข้อมูลก่อนที่จะทำการส่งข้อมูล
        if (validateForm()) {
            // สร้าง FormData object เพื่อรวบรวมข้อมูลจากฟอร์ม
            var formData = new FormData(form);

            // ส่งข้อมูลไปยังไฟล์ PHP สำหรับการบันทึก
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'ConnectDB/AddAppointment.php', true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกข้อมูลการนัดหมายแล้ว',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // เมื่อ SweetAlert ปิด
                        // ให้ทำการเปลี่ยนไปยัง Step ที่ 3
                        document.querySelector('.step [data-step="3"]').classList.add('current');
                        document.getElementById("showFormAppoint").style.display = "none";
                        document.getElementById("ShowTextSuccess").style.display = "block"; // แสดง div ที่มี id เป็น "ShowTextSuccess"
                    });
                } else {
                    alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                }
            };

            xhr.send(formData); // ส่งข้อมูลฟอร์ม
        }
    });
});

    function goBack() {
    // ใช้ window.location.reload() เพื่อโหลดหน้าเดิมใหม่
        window.location.reload();
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
        var doctorName = document.getElementById('DoctorName').value;

        // ทำการส่งค่าวันที่ไปยังไฟล์ PHP เพื่อค้นหาเวลาที่ถูกเลือกไปแล้ว
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "ConnectDB/CheckDate.php", true);
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
    document.addEventListener("DOMContentLoaded", function() {
        // ดึงอิลิเมนต์ dropdown โดยใช้ ID
        var timeDropdown = document.getElementById('time');

        // กำหนดการใช้งานปฏิทินด้วย flatpickr โดยใช้ ID
        flatpickr("#dateforBook", {
            dateFormat: "Y-m-d", // รูปแบบวันที่ที่ต้องการ
            minDate: "today", // กำหนดให้สามารถเลือกวันที่เริ่มต้นได้ตั้งแต่วันนี้
            locale: "th", // กำหนด locale เป็นภาษาไทย
            onChange: function(selectedDates, dateStr, instance) {
                var selectedDate = new Date(dateStr); // แปลงค่าวันที่ที่เลือกใหม่เป็นวัตถุ Date
                var currentTime = new Date(); // เวลาปัจจุบัน
                var currentHour = currentTime.getHours();
                var currentMinute = currentTime.getMinutes();

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
    });


    // checkNonDateInputs();

    // // ฟังก์ชันที่ใช้ในการตรวจสอบว่าเวลาที่เลือกอยู่ในอดีตหรือไม่
    // function isPastTime(timeString) {
    //     // ดึงเวลาปัจจุบัน
    //     var currentTime = new Date();
    //     var currentHour = currentTime.getHours();
    //     var currentMinute = currentTime.getMinutes();

    //     // แยกวันที่และเวลาจากเวลาที่กำหนด
    //     var timeParts = timeString.split(' ');
    //     var selectedHour = parseInt(timeParts[0].split('.')[0]);
    //     var selectedMinute = parseInt(timeParts[0].split('.')[1]);

    //     // เปรียบเทียบเวลา
    //     if (currentHour > selectedHour || (currentHour === selectedHour && currentMinute >= selectedMinute)) {
    //         return true; // เวลาที่กำหนดอยู่ในอดีต
    //     }

    //     return false; // เวลาที่กำหนดยังไม่ผ่านไป
    // }
</script>

</body>
</html>
