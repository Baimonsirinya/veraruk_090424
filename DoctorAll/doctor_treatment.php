<?php
    include "../connect.php";
    include "../session/sessionlogin.php";
?>

<?php
    // ตั้งค่าโซนเวลาเป็น Asia/Bangkok
    date_default_timezone_set('Asia/Bangkok');

    // ดึงวันที่ปัจจุบันของเซิร์ฟเวอร์ (เวลาของไทย)
    $currentDate = date("Y-m-d");

    $stmt_course = $pdo->prepare("SELECT * FROM patient_course WHERE patient_id = ?");
    $stmt_course->execute([$_GET["patient_id"]]);
    $course = $stmt_course->fetchAll(PDO::FETCH_ASSOC);

    // ดึงชื่อคนไข้จากตาราง medical_records
    $stmt_patient = $pdo->prepare("SELECT name_patient FROM medical_records WHERE patient_id = ?");
    $stmt_patient->execute([$_GET["patient_id"]]);
    $patient = $stmt_patient->fetch(PDO::FETCH_ASSOC);

    // เช็คว่าพบชื่อคนไข้หรือไม่
    if ($patient) {
        $patientName = $patient["name_patient"];

        // เตรียมและ execute คำสั่ง SQL เพื่อดึงข้อมูลการรักษา
        $stmt = $pdo->prepare("SELECT * FROM treatment WHERE patient_id = ? AND Date = ?");
        $stmt->execute([$_GET["patient_id"], $currentDate]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

    } else {
        // ไม่พบชื่อคนไข้
        echo "ไม่พบข้อมูลคนไข้";
    }
?>



<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="doctor_treatment.css">
    <!-- <link rel="stylesheet" href="../css/medical_records.css"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "run_purchase_id.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        document.getElementById("purchase_id_input").value = xhr.responseText;
                    } else {
                        console.error("เกิดข้อผิดพลาดในการรับข้อมูล course_id");
                    }
                }
            };
            xhr.send();
        });
    </script>

</head>
<body>
<header>
    <?php include('Navbar_Doctor.php');?>
</header>
<div class="container">
    <div class="sidebar">
        <div class="sidebar-1">
            <!-- ตรวจสอบว่ามีข้อมูลใน $row หรือไม่ก่อนแสดงผล -->
            <?php if($row && isset($row["patient_id"])): ?>
                รหัสผู้ป่วย: <?=$row["patient_id"]?><br>
                ชื่อ-สกุล: <?=$patient["name_patient"]?>
            <?php endif; ?>
        </div>
        <div class="side-nav">
            <a href="doctor_general.php?patient_id=<?=$row ["patient_id"] ?>" class="sidebar-item" id="general">ข้อมูลทั่วไป</a>
            <a href="doctor_treatment.php?patient_id=<?=$row ["patient_id"] ?>" class="sidebar-item" id="treatment">การรักษา</a>
            <a href="doctor_history.php?patient_id=<?=$row ["patient_id"] ?>" class="sidebar-item" id="course">ประวัติการรักษา</a>
        </div>
    </div>

    <div class="content-result">
    <form id="form_data" method="post">
        <div class="form-container">
            <p>ข้อมูลการรักษา</p>
            <div class="card-data-patient">
                <div class="card-data-date">
                    <div class="column" style="width: 500px;">
                        <input type="hidden" id="doctor" name="doctor" value="<?php echo $username; ?>">
                        <input type="hidden" id="patient_id" name="patient_id" value="<?=$row["patient_id"]?>">
                        <input type="hidden" id="treatment_id" name="treatment_id" value="<?=$row["treatment_id"]?>">
                        <label>วันที่เข้ารักษา : <?=$row["date"]?></label>
                    </div>
                </div>
                <div class="card-data-treatment">
                    <div class="column" style="width: 50px;">
                        <label>BP : <?=$row["systolic"]?>/<?=$row["diastolic"]?> mmhg</label>
                        <label>Weight : <?=$row["weight"]?></label>
                    </div>
                    <div class="column" style="width: 50px;">
                        <label>T : <?=$row["temperature"]?></label>
                        <label>Height : <?=$row["height"]?></label>
                    </div>
                    <div class="column" style="width: 50px;">
                        <label>PR : <?=$row["pluse_rate"]?></label>
                    </div>
                </div>
            </div>
            <h3>การรักษา</h3>
            <div class="card-data-course">
                <div class="column" style="width: 200px;">
                    <?php if ($course && $stmt_course->rowCount() > 0): ?>
                        <div class="course-data">
                            <?php foreach ($course as $courses): ?>
                                <?php if ($courses["quantity"] > 0): ?>
                                    <a class="course_name"><?= $courses["procedure"] ?></a><br>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="column" style="width: 400px;">
                    <?php if ($course && $stmt_course->rowCount() > 0): ?>
                        <?php foreach ($course as $courses): ?>
                            <?php if ($courses["quantity"] > 0): ?>
                                <div class="course-quantity">
                                    <input type="hidden" id="treatment_id" name="treatment_id" value="<?=$row["treatment_id"]?>">
                                    <input type="hidden" id="purchase_id" name="purchase_id" value="<?= $courses["purchase_id"] ?>">
                                    จำนวนครั้ง : <input type="text" id="quantity" name="quantity" value="<?= $courses["quantity"] ?>">
                                    <button class="usecourse" type="button" data-id="<?= $courses["patient_course_id"] ?>" 
                                    data-procedure="<?= $courses["procedure"] ?>">ใช้</button>
                                    <button class="deletecourse" type="button" data-id="<?= $courses["patient_course_id"] ?>" data-delete="<?= $courses["purchase_id"] ?>"
                                    >ลบ</button>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>


            <div class="card-data-addcourse">
                <div class="column" style="width: 300px;">
                    <input type="hidden" id="purchase_id_input" name="purchase_id_input">
                    <label for="procedure_add">หัตถการ :  <br><input type="text" id="procedure_add" name="procedure_add"> จำนวนครั้ง : <input type="int" id="quantity_add" name="quantity_add">
                    <button id="submitProcedure" name ="submit">เพิ่ม</button>
                    <label for="treatment_details">รายละเอียดการรักษา :  <br><textarea type="text" id="treatment_details" name="treatment_details"><?=$row["treatment_details"]?></textarea></label>
                    <label for="medicine_name">จ่ายยา :  <br><textarea type="text" id="medicine_name" name="medicine_name"><?=$row["medicine_name"]?></textarea></label>
                </div>
            </div>

            <div class="center">
                <button id="submitOuter" type="submit">บันทึกข้อมูล</button>
            </div> 
            <div class="treatmentDone"><button id="submitDone" type="submit">ตรวจเสร็จแล้ว</button></div>
    </form>
        </div>

</body>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    function refreshPage() {
        window.location.reload(); // รีเฟรชหน้า
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('submitProcedure').addEventListener('click', function() {
        event.preventDefault();

    // เก็บค่าจากฟอร์ม
    var patient_id = document.getElementById('patient_id').value;
    var procedure = document.getElementById('procedure_add').value;
    var quantity = document.getElementById('quantity_add').value;
    var purchase_id = document.getElementById('purchase_id_input').value;
    
    // ตรวจสอบความถูกต้องของข้อมูล
    if (patient_id && procedure && quantity) {
        // สร้างข้อมูลที่จะส่งไปยังไฟล์ update_treatment_data.php
        var data = new FormData();
        data.append('patient_id', patient_id);
        data.append('procedure', procedure);
        data.append('quantity', quantity);
        data.append('purchase_id', purchase_id);


        // เรียกใช้งาน XMLHttpRequest
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'addcourse_patient.php', true); // เพิ่ม URL ของไฟล์ PHP ที่รับข้อมูล

        xhr.onload = function() {
            if (xhr.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                    showConfirmButton: false,
                    timer: 1500
               }).then(() => {
                    location.reload();
                });
            } else {
                // แสดง SweetAlert เมื่อเกิดข้อผิดพลาดในการบันทึกข้อมูล
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: xhr.responseText // แสดงข้อความผิดพลาดที่ถูกส่งกลับมาจาก PHP
                });
                console.log(xhr.responseText); // แสดงข้อความผิดพลาดในคอนโซลของเบราว์เซอร์
            }
        };
        // ส่งข้อมูล
        xhr.send(data);
    } else {
        // แสดงข้อความแจ้งเตือนถ้าข้อมูลไม่ครบ
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: 'กรุณากรอกข้อมูลให้ครบถ้วน'
        });
    }
});

    document.getElementById('submitOuter').addEventListener('click', function(event) {
    event.preventDefault();

    // เก็บค่าจากฟอร์ม
    var treatment_id = document.getElementById('treatment_id').value;
    var treatment_details = document.getElementById('treatment_details').value;
    var medicine_name = document.getElementById('medicine_name').value;
    var doctor = document.getElementById('doctor').value;

    // ตรวจสอบความถูกต้องของข้อมูล
    if (treatment_id && treatment_details && medicine_name && doctor) {
        // สร้างข้อมูลที่จะส่งไปยังไฟล์ update_treatment_data.php
        var data = new FormData();
        data.append('treatment_id', treatment_id);
        data.append('treatment_details', treatment_details);
        data.append('medicine_name', medicine_name);
        data.append('doctor', doctor);

        // เรียกใช้งาน XMLHttpRequest
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_treatment_data.php', true); // เพิ่ม URL ของไฟล์ PHP ที่รับข้อมูล

        // ส่งข้อมูลไปยังไฟล์ update_treatment_data.php
        xhr.onload = function() {
            if (xhr.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                // แสดง SweetAlert เมื่อเกิดข้อผิดพลาดในการบันทึกข้อมูล
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: xhr.responseText // แสดงข้อความผิดพลาดที่ถูกส่งกลับมาจาก PHP
                });
                console.log(xhr.responseText); // แสดงข้อความผิดพลาดในคอนโซลของเบราว์เซอร์
            }
        };
        // ส่งข้อมูล
        xhr.send(data);
    } else {
        // แสดงข้อความแจ้งเตือนถ้าข้อมูลไม่ครบ
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: 'กรุณากรอกข้อมูลให้ครบถ้วน'
        });
    }
});
});

    document.querySelectorAll('.usecourse').forEach(function(button) {
        button.addEventListener('click', function() {
            var quantityInput = this.previousElementSibling; // เลือก input ที่อยู่ก่อนปุ่ม "ใช้"
            var currentQuantity = parseInt(quantityInput.value);
            
            if (currentQuantity > 0) {
                quantityInput.value = currentQuantity - 1; // ลดค่า quantity ลง 1

                // ส่งข้อมูลไปยังไฟล์ PHP ด้วย XMLHttpRequest
                var rowId = this.getAttribute('data-id'); // รหัสแถวที่ต้องการอัปเดต

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_quantity.php'); // เปลี่ยนชื่อไฟล์ตามชื่อไฟล์ PHP ที่มีโค้ดอัปเดตฐานข้อมูล
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        if (xhr.responseText === 'success') {
                            // หากอัปเดตสำเร็จ คุณสามารถแจ้งผู้ใช้ด้วยวิธีที่คุณต้องการ
                            console.log('อัปเดตข้อมูลสำเร็จ');
                        } else {
                            // หากมีข้อผิดพลาดในการอัปเดต
                            console.log('เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
                        }
                    } else {
                        // หากมีข้อผิดพลาดในการเชื่อมต่อ
                        console.log('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์');
                    }
                };
                xhr.send('rowId=' + rowId); // ส่งข้อมูลไปยังไฟล์ PHP
            } else {
                alert('ไม่สามารถลดจำนวนครั้งได้เนื่องจากมีค่าเป็น 0 หรือต่ำกว่านั้น');
            }
        });
    });
    document.querySelectorAll('.usecourse').forEach(function(button) {
    button.addEventListener('click', function() {
        var procedure = this.getAttribute('data-procedure'); // ดึงข้อมูล procedure จาก attribute data-procedure
        var treatmentId = <?php echo $row["treatment_id"]; ?>;

        // สร้าง FormData และเพิ่มข้อมูล procedure และ treatment_id เข้าไป
        var formData = new FormData();
        formData.append('procedure', procedure);
        formData.append('treatment_id', treatmentId);

        // ส่งข้อมูลไปยังไฟล์ PHP ด้วย XMLHttpRequest
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'save_procedures.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                // ทำสิ่งที่ต้องการเมื่อบันทึกข้อมูลสำเร็จ
            } else {
                // แสดงข้อความหรือกระทำอื่นๆ เมื่อมีข้อผิดพลาด
            }
        };
        xhr.send(formData); // ส่งข้อมูลไปยังไฟล์ PHP
    });
    });

    document.addEventListener("DOMContentLoaded", function() {
    // เมื่อมีการคลิกที่ปุ่ม "ลบ"
    document.querySelectorAll(".deletecourse").forEach(button => {
        button.addEventListener("click", function() {
            // รับค่าไอดีของคอร์สที่ต้องการลบ
            var courseId = this.dataset.id;
            var purchaseId = this.dataset.delete;

            // แสดง SweetAlert เพื่อยืนยันการลบ
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการลบคอร์สนี้ใช่หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบคอร์ส!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ส่งคำขอลบไปยังไฟล์ PHP ด้วย AJAX
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "delete_course.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                // ดำเนินการหลังจากลบคอร์สเรียบร้อย
                                var response = xhr.responseText;
                                if (response === "success") {
                                    // แสดง SweetAlert เมื่อลบสำเร็จ
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'ลบคอร์สเรียบร้อยแล้ว',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        // รีโหลดหน้าหลังจากลบคอร์ส
                                        window.location.reload();
                                    });
                                } else {
                                    // แสดง SweetAlert เมื่อเกิดข้อผิดพลาดในการลบ
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'เกิดข้อผิดพลาด',
                                        text: 'ไม่สามารถลบคอร์สได้'
                                    });
                                }
                            } else {
                                // แสดง SweetAlert เมื่อเกิดข้อผิดพลาดในการส่งคำขอ
                                Swal.fire({
                                    icon: 'error',
                                    title: 'เกิดข้อผิดพลาด',
                                    text: 'ไม่สามารถส่งคำขอไปยังเซิร์ฟเวอร์ได้'
                                });
                            }
                        }
                    };
                    // ส่งไอดีของคอร์สไปยังไฟล์ PHP เพื่อดำเนินการลบ
                    xhr.send("course_id=" + courseId + "&purchase_id=" + purchaseId);
                }
            });
        });
    });
    });


    document.getElementById('submitDone').addEventListener('click', function() {
    event.preventDefault();

    // สร้าง SweetAlert เพื่อขอยืนยันการลบคนไข้
    Swal.fire({
        icon: 'warning',
        title: 'ยืนยันการดำเนินการ',
        text: 'ตรวจเสร็จแล้วใช่หรือไม่',
        showCancelButton: true,
        confirmButtonText: 'ใช่, ตรวจเสร็จแล้ว',
        cancelButtonText: 'ไม่, ยกเลิก'
    }).then((result) => {
        // หากผู้ใช้คลิกปุ่ม "ใช่, ต้องการลบ"
        if (result.isConfirmed) {
            var treatment_id = document.getElementById('treatment_id').value;

            var formData = new FormData();
            formData.append('treatment_id', treatment_id);
            console.log(treatment_id);

            // เรียกใช้งาน XMLHttpRequest
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../ConnectDB/remove_patient.php', true); // เปลี่ยนเส้นทางไปยังไฟล์ PHP ที่รับค่าและลบคนไข้

            xhr.onload = function() {
                if (xhr.status === 200) {
                    // ถ้าการลบสำเร็จ
                    Swal.fire({
                        icon: 'success',
                        title: 'ทำรายการเรียบร้อยแล้ว',
                        showConfirmButton: false,
                        timer: 1000
                    }).then(() => {
                        window.location.href = 'doctor_checkpatient.php';
                    });
                } else {
                    // ถ้าเกิดข้อผิดพลาดในการลบ
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'กรุณาลองใหม่อีกครั้ง'
                    });
                }
            };

            // ส่งคำขอไปยังไฟล์ PHP
            xhr.send(formData);
        }
    });
});

</script>

</html>
