<?php
    include "../connect.php";
    include "../session/sessionlogin.php";
   


    $stmt = $pdo->prepare("SELECT * FROM appointment_admin_page WHERE status = 'รอเข้ารับบริการ' "); 
    $stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" href="admin_appointment.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data.min.js"></script>



<script>
    function close_modal1(){
        document.getElementById('close');
        var popup = document.getElementById("popup-form-check");
        popup.style.display = "none"; 
    };

    function close_modal2(){
        document.getElementById('close');
        var popup = document.getElementById("popup-form-checkdoctor");
        popup.style.display = "none"; 
    };

    function showformcheck(appointmentid, patientid, patientname, Doctorname) {
        var popup = document.getElementById("popup-form-check");
        popup.style.display = "flex";
        var appointmentIdInput = document.getElementById("appointment_id_check"); // เปลี่ยนเป็น appointmentIdInput
        appointmentIdInput.value = appointmentid; // เปลี่ยนเป็น appointmentIdInput.value
        var patientIdInput = document.getElementById("patient_id_check");
        patientIdInput.value = patientid;
        var DoctorNameInput = document.getElementById("doctor_check");
        DoctorNameInput.value = Doctorname;
    }



    function showformcheckDoctor(){
        var popup = document.getElementById("popup-form-checkdoctor");
         popup.style.display = "flex";
    }

    document.addEventListener("DOMContentLoaded", function() {
    // ดึงวันที่ปัจจุบันในรูปแบบของเมืองไทย
    var currentDateThailand = moment().tz("Asia/Bangkok").format('YYYY-MM-DD');
    // กำหนดค่าให้กับ input element
    document.getElementById('date_present').value = currentDateThailand;
    });

    document.addEventListener("DOMContentLoaded", function() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "../ConnectDB/run_treatment_id.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    document.getElementById("treatment_id_input").value = xhr.responseText;
                } else {
                    console.error("เกิดข้อผิดพลาดในการรับข้อมูล treatment_id");
                }
            }
        };
        xhr.send();
    });

    document.addEventListener("DOMContentLoaded", function() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "../ConnectDB/run_patient_id.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    document.getElementById("patient_id_new").value = xhr.responseText;
                } else {
                    console.error("เกิดข้อผิดพลาดในการรับข้อมูล patient_id_new");
                }
            }
        };
        xhr.send();
    });

    function showform_add_medical(appointmentId, patientName){
        var popup = document.getElementById("bd-modal");
        popup.style.display = "flex";
        var appointmentIdInput = document.getElementById("appointment_id"); 
        appointmentIdInput.value = appointmentId; 
        var patientNameInput = document.getElementById("name_patient");
        patientNameInput.value = patientName;
    };

    function close_modal_medical(){
        document.getElementById('close')
        var popup = document.getElementById("bd-modal");
        popup.style.display = "none"; 
    };


</script>

</head>
<body>
  <header>
   <?php include('Navbar_Admin.php');?>
  </header>

    <div class="container">
        <div class="sidebar">
            <div class="side-nav">
                <a href="admin_appointment.php" class="sidebar-item" style="color: black;">การนัดหมาย</a>
                <a href="admin_seeDoctor.php" class="sidebar-item" style="color: black;">พบแพทย์</a>
                <a href="admin_medicine.php" class="sidebar-item" style="color: black;">จ่ายยา</a>
            </div>
        </div>
        <div class="content" id="table-appointments">
            <div class="select_date">
                <h3>ตารางการนัดหมาย</h3>
                <div>
                    <a href="admin_selectpatient.php" class="button-add-booking" id="button-add-booking"> เพิ่มการนัดหมาย + </a>
                    <button href="#" class="check-doctor" onclick="showformcheckDoctor()">เช็คเวลาว่างของหมอ</button ><br>
                    <label for="date_appoint">วันเดือนปีที่นัดหมาย :<input type="date" id="date_appoint" min="<?php echo date('Y-m-d'); ?>"></label><br>
                    <input type="text" id="search-box" placeholder="ค้นหา">
                </div>
            </div>
            <table id="all_appointment">
                <tr>
                    <th>วันเดือนปีที่นัดหมาย</th>
                    <th>เวลาที่นัดหมาย</th>
                    <th>ชื่อผู้ป่วย</th>
                    <th>แพทย์</th>
                    <th>ซักประวัติ</th>
                    <th> </th>
                </tr>
                <?php while($row = $stmt->fetch()): ?>
                    <tr>
                        <td><?= $row["date"] ?></td>
                        <td><?= $row["time"] ?></td>
                        <td><?= $row["patient_name"] ?></td>
                        <td><?= $row["doctor"] ?></td>
                        <td>
                            <?php if ($row['patient_status'] === 'ผู้ป่วยใหม่'): ?>
                                <button class="add-data-medical" onclick="showform_add_medical('<?= $row['appointment_id'] ?>','<?= $row['patient_name'] ?>')">
                                    เพิ่มเวชระเบียน <i class='far fa-edit'></i>
                                </button>
                            <?php else: ?>
                                <button class="add-data" onclick="showformcheck('<?= $row['appointment_id'] ?>','<?= $row['patient_id'] ?>','<?= $row['patient_name'] ?>','<?= $row['doctor'] ?>')">
                                    เพิ่มข้อมูล <i class='far fa-edit'></i>
                                </button>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="delete-data" onclick="deleteAppointment(<?= $row['appointment_id'] ?>)">ลบ <i class='far fa-trash-alt'></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
            </div>
    </div>

    <div class="bg-modal"id="bd-modal" style="display:none;">
        <div class="modal-content">
            <form id="form_data" method="POST" >
            <span onclick="close_modal_medical()" class="close">&times;</span><br>
                <label><input type="hidden" id="appointment_id" readonly></label>
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



      <div id="popup-form-check" class="popup-form">
        <form id="patientForm" class="form-container">
            <span onclick="close_modal1()" class="close">&times;</span>
            <h2>ซักประวัติ</h2>
            <hr>
            <input type="hidden" id="treatment_id_input" name="treatment_id" class="input-field" readonly>
            <input type="hidden" id="appointment_id_check" name="appointment_id_check" class="appointment_id" readonly>
            <div class="data-patient">
                <input type="hidden" id="doctor_check" name="doctor_check" class="input-field" readonly>
                <label>รหัสผู้ป่วย : <input id = "patient_id_check" name="patient_id_check" class="input-field" readonly></label><br>
                <label>วันที่เข้ารับการรักษา : <input type="text" id="date_present" name="date" class="input-date" readonly></label><br>
            </div>
            <div class="input-row1">
                <label>weight : <input type="float" id="weight" name="weight" class="input-field">kg</label>
                <label>height : <input type="int" id="height" name="height" class="input-field">cm</label><br>
            </div>
            <div class="input-row">
                <label>BP : <input type="float" id="diastolic" name="diastolic" class="input-field">/<input type="text" id="systolic" name="systolic" class="input-field">mmhg</label>
                <label>T : <input type="float" id="temperature" name="temperature" class="input-field">c</label>
                <label>PR : <input type="int" id="pluse_rate" name="pluse_rate" class="input-field">/min</label>
            </div>
            <div class="position-button">
                <button type="button" id="submitFormBtn" class="submit-btn">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>



    <div id="popup-form-checkdoctor" class="popup-form">
    <form class="form-container">
        <span onclick="close_modal2()" class="close">&times;</span>
        <div class="position-checkdoctor">
            <label for="doctor_select">เลือกหมอ : </label>
            <select id="doctor_select" name="doctor_select">
                <?php
                    // ทำการสร้างคำสั่ง SQL เพื่อดึงชื่อหมอออกมาจากตาราง users โดยเลือกเฉพาะผู้ใช้ที่มีบทบาทเป็นหมอ (role = doctor)
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'doctor'");
                    $stmt->execute();
                    // วนลูปเพื่อสร้างตัวเลือกใน dropdown โดยใช้ชื่อของหมอเป็นค่าในตัวเลือก
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='".$row['name'] . ' ' . $row['lastname']."'>".$row['name'] . ' ' . $row['lastname']."</option>";
                    }
                ?>
            </select>
            <label for="appointment_date">เลือกวันที่ : </label>
            <input type="date" id="appointment_date" name="appointment_date">
        </div>
         <div class="button-fecth-appoint">          
            <button type="button" id="button-fecth" onclick="fetchAppointments()">ค้นหา</button>
        </div>

        <div id="appointment_results">
            <!-- ผลลัพธ์จะแสดงที่นี่ -->
        </div>
    
    </form>
    </div>

</body>

        

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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


    document.getElementById('id_card').addEventListener('change', function() {
    var idCard = this.value;
    var appointmentID = document.getElementById('appointment_id').value;

    // ส่งคำขอ AJAX ไปยัง PHP เพื่อตรวจสอบว่ามีเลขบัตรประชาชนนี้ใน medical_records หรือไม่
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../ConnectDB/check_id_card.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        // รับข้อมูลที่คืนมาจากเซิร์ฟเวอร์
        var response = this.responseText;
        if (response.startsWith("exists")) {
            // หาชื่อของผู้ป่วยจากข้อมูลที่คืนมา
            var patientInfo = response.split(':')[1].split(',');
            var patientName = patientInfo[0];
            var patientID = patientInfo[1];
            
            Swal.fire({
                icon: 'error',
                title: 'มีเลขบัตรประชาชนอยู่ในเวชระเบียนอยู่แล้ว',
                text: 'คุณ ' + patientName,
                showCancelButton: true,
                confirmButtonText: 'เชื่อมข้อมูล',
                cancelButtonText: 'ไม่เชื่อมข้อมูล',
                customClass: {
                    popup: 'sweetalert-zindex'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // ทำการเชื่อมข้อมูล
                    var xhrSave = new XMLHttpRequest();
                    xhrSave.open("POST", "../ConnectDB/save_patient_id.php", true);
                    xhrSave.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhrSave.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            // รับข้อมูลที่คืนมาจากเซิร์ฟเวอร์ (ถ้ามี)
                            var responseSave = this.responseText;
                            if (responseSave === "success") {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'เชื่อมต่อข้อมูลเรียบร้อยแล้ว',
                                    showConfirmButton: false,
                                    timer: 1000
                                }).then(() => {
                                    // ปิด popup form หลังจากบันทึกข้อมูลเรียบร้อย
                                    close_modal_medical();
                                    location.reload();
                                });

                            } else {
                                Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'ไม่สามารถเชื่อมต่อข้อมูลได้'
                            });
                            }
                        }
                    };
                    console.log(patientID, appointmentID);
                    xhrSave.send("patient_id=" + patientID + "&appointment_id=" + appointmentID);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถทำรายการต่อได้เพราะเลขบัตรประชาชนต้องไม่ซ้ำกัน'
                    }).then(() => {
                        // ปิด popup form หลังจากบันทึกข้อมูลเรียบร้อย
                        close_modal_medical();
                        location.reload();
                    });
                }
            });

        } 
    } 
    };
    xhr.send("id_card=" + idCard);
});


document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('form_data').addEventListener('submit', function(event) {
        event.preventDefault(); // ป้องกันการ submit แบบฟอร์มแบบธรรมดา

        var appointmentId = document.getElementById('appointment_id').value;
        var patientId = document.getElementById('patient_id_new').value;
        var formData = new FormData(this);

        // ส่งข้อมูลไปยังไฟล์ PHP ด้วย AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../ConnectDB/save_medical_patient.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                // หากการส่งข้อมูลไปยัง save_medical_patient.php สำเร็จ

                // สร้าง FormData object เพื่อส่ง appointment_id และ patient_id ไปยังไฟล์ save_patient_id.php
                var formDataPatientId = new FormData();
                formDataPatientId.append('appointment_id', appointmentId);
                formDataPatientId.append('patient_id', patientId);
                
                // ส่งข้อมูลไปยังไฟล์ PHP สำหรับบันทึก patient_id
                var xhrSavePatientId = new XMLHttpRequest();
                xhrSavePatientId.open('POST', '../ConnectDB/save_patient_id.php', true);
                xhrSavePatientId.onload = function() {
                    if (xhrSavePatientId.status === 200) {
                        // หากการบันทึก patient_id เสร็จสิ้น
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            close_modal_medical();
                            location.reload();
                        });
                    } else {
                        // หากเกิดข้อผิดพลาดในการบันทึก patient_id
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถบันทึกข้อมูลได้',
                        }).then(() => {
                            close_modal_medical();
                            location.reload();
                        });
                        console.error('เกิดข้อผิดพลาดในการส่งข้อมูล: ' + xhrSavePatientId.status);
                    }
                };
                xhrSavePatientId.onerror = function() {
                    // กรณีเกิดข้อผิดพลาดในการส่งข้อมูล
                    console.error('เกิดข้อผิดพลาดในการส่งข้อมูล');
                };
                xhrSavePatientId.send(formDataPatientId); // ส่งข้อมูลไปยัง save_patient_id.php
            }
        };
        xhr.onerror = function() {
            // กรณีเกิดข้อผิดพลาดในการส่งข้อมูล
            console.error('เกิดข้อผิดพลาดในการส่งข้อมูล');
        };
        xhr.send(formData); // ส่งข้อมูลไปยัง save_medical_patient.php
    });
});


    function refreshPage() {
        window.location.reload(); // รีเฟรชหน้า
    }

    function deleteAppointment(appointmentId) {
    // แสดง Confirm Dialog ก่อนลบ
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: 'คุณต้องการลบนัดหมายนี้หรือไม่',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ลบ!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // สร้าง XMLHttpRequest object
            var xhr = new XMLHttpRequest();
            
            // กำหนดการเชื่อมต่อ
            xhr.open('POST', '../ConnectDB/delete_appointment.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            // ตรวจสอบการเปลี่ยนสถานะของการเชื่อมต่อ
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = xhr.responseText;
                        // ตรวจสอบคำตอบจากเซิร์ฟเวอร์
                        if (response === 'success') {
                            // แสดงข้อความสำเร็จ
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบสำเร็จ!',
                                text: 'นัดหมายได้ถูกลบออกจากระบบแล้ว',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // รีเฟรชหน้าหลังจากลบสำเร็จ
                                location.reload();
                            });
                        } else {
                            // แสดงข้อความเมื่อมีข้อผิดพลาด
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                text: 'ไม่สามารถลบนัดหมายได้'
                            });
                        }
                    } else {
                        // แสดงข้อความเมื่อมีข้อผิดพลาดในการเชื่อมต่อ
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
                        });
                    }
                }
            };

            // ส่งค่า ID ของนัดหมายไปยังไฟล์ PHP
            xhr.send('id=' + appointmentId);
        }
    });
    }
    



    // เมื่อโหลดหน้าเว็บเสร็จสิ้น
document.addEventListener("DOMContentLoaded", function() {
    // เพิ่มการฟังก์ชันเมื่อคลิกที่ปุ่ม "บันทึกข้อมูล"
    document.getElementById("submitFormBtn").addEventListener("click", function() {
        // เรียกใช้ฟังก์ชันสำหรับส่งข้อมูล
        submitForm();
    });
});

// ฟังก์ชันสำหรับการส่งข้อมูลไปยังไฟล์ PHP ด้วย AJAX
function submitForm() {
    // รับค่าจากฟอร์ม
    var treatment_id = document.getElementById("treatment_id_input").value;
    var appointment_id = document.getElementById("appointment_id_check").value;
    var patient_id = document.getElementById("patient_id_check").value;
    var doctor = document.getElementById("doctor_check").value;
    var date = document.getElementById("date_present").value;
    var weight = document.getElementById("weight").value;
    var height = document.getElementById("height").value;
    var systolic = document.getElementById("systolic").value;
    var diastolic = document.getElementById("diastolic").value;
    var temperature = document.getElementById("temperature").value;
    var pluse_rate = document.getElementById("pluse_rate").value;


    var data = "treatment_id=" + encodeURIComponent(treatment_id) +
               "&appointment_id=" + encodeURIComponent(appointment_id) +
               "&patient_id=" + encodeURIComponent(patient_id) +
               "&doctor=" + encodeURIComponent(doctor) +
               "&date=" + encodeURIComponent(date) +
               "&weight=" + encodeURIComponent(weight) +
               "&height=" + encodeURIComponent(height) +
               "&systolic=" + encodeURIComponent(systolic) +
               "&diastolic=" + encodeURIComponent(diastolic) +
               "&temperature=" + encodeURIComponent(temperature) +
               "&pluse_rate=" + encodeURIComponent(pluse_rate);

    // สร้าง AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'admin_addtreatment.php', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
    if (this.readyState === 4 && this.status === 200) {
        var response = xhr.responseText;
        if (response === 'เรียบร้อย') {
            console.log("1");
            Swal.fire({
                icon: 'success',
                title: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                console.log(appointment_id);
                close_modal1();
                updateStatus(appointment_id);
            });
        } else {
            // แสดง SweetAlert เมื่อเกิดข้อผิดพลาดในการบันทึกข้อมูล
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: response // แสดงข้อความผิดพลาดที่ถูกส่งกลับมาจาก PHP
            });
            // แสดงข้อความผิดพลาดในคอนโซลของเบราว์เซอร์
        }
    } else {
        // แสดง SweetAlert เมื่อเกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
        });
    }
};
    // ส่งข้อมูลฟอร์มไปยังไฟล์ PHP
    xhr.send(data);
}


    function updateStatus(appointment_id) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                // ตรวจสอบว่าการอัปเดตสำเร็จหรือไม่
                if (this.responseText === 'success') {
                    console.log('อัปเดตข้อมูลเรียบร้อย');
                    location.reload();
                } else {
                    console.log('เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
                }
            }
        };
        xhttp.open("POST", "../ConnectDB/update_status_appoint.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("appointment_id=" + appointment_id);
    }


    function fetchAppointments() {
    var selectedDoctor = document.getElementById("doctor_select").value;
    var selectedDate = document.getElementById("appointment_date").value;

    // เช็คว่ามีการเลือกวันที่หรือไม่
    if(selectedDate === "") {
        alert("โปรดเลือกวันที่");
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "fetch_appointments.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var appointments = xhr.responseText;
                console.log(appointments);
                displayAppointments(appointments);
            } else {
                console.error("เกิดข้อผิดพลาดในการเรียกข้อมูล");
            }
        }
    };
    // ส่งข้อมูลเพื่อค้นหา
    xhr.send("doctor=" + selectedDoctor + "&date=" + selectedDate);
    }


    function displayAppointments(appointments) {
        // กำหนดช่วงเวลาที่ต้องการแสดงผล
        var startTime = 8; // เริ่มต้นที่ 8.00 น.
        var endTime = 20; // สิ้นสุดที่ 20.00 น.
        var appointmentResultsHTML = "";

        // แยกสตริงเป็นอาร์เรย์ของเวลาที่ถูกจอง
        var bookedTimes = appointments.split(",");
        
        // วนลูปเพื่อสร้างตารางเวลา
        for (var i = startTime; i <= endTime; i++) {
            var timeFormatted = i < 10 ? "0" + i + ".00 น." : i + ".00 น.";
            if (bookedTimes.includes(timeFormatted.trim())) {
                // ถ้ามีการจองแล้วให้แสดงเป็นสีเทา
                appointmentResultsHTML += "<div id='time_booking'>" + timeFormatted + "</div>";
            } else {
                // ถ้ายังไม่มีการจองให้แสดงเป็นสีดำ
                appointmentResultsHTML += "<div id='time_free'>" + timeFormatted + "</div>";
            }
        }


        // นำผลลัพธ์ HTML ไปแสดงผลที่ตำแหน่งที่กำหนดไว้
        document.getElementById("appointment_results").innerHTML = appointmentResultsHTML;
    }

    document.addEventListener("DOMContentLoaded", function() {
    // เมื่อมีการเปลี่ยนแปลงใน input type="date"
    document.getElementById("date_appoint").addEventListener("change", function() {
        // เรียกฟังก์ชัน fetchAppointments() เมื่อมีการเปลี่ยนแปลง
        fetchFindDate();
    });
    });

    function fetchFindDate() {
    // รับค่าวันที่ที่เลือก
        var selectedDate = document.getElementById("date_appoint").value;


        var xhr = new XMLHttpRequest();
        
        // กำหนดเมธอดและ URL
        xhr.open("POST", "../ConnectDB/fetch_findDate.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // เมื่อข้อมูลส่งกลับมา
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var appointments = xhr.responseText;
                    // เอา appointments ไปแสดงแทนที่ all_appointment
                    document.getElementById("all_appointment").innerHTML = appointments;
                    

                } else {
                    console.error("เกิดข้อผิดพลาดในการเรียกข้อมูล");
                }
            }
        };

        // ส่งข้อมูลวันที่ไปยังไฟล์ PHP
        xhr.send("date=" + selectedDate);
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
        xhr.open("POST", "../ConnectDB/search_appointment.php", true);
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