<?php
    include "../connect.php";
    include "../session/sessionlogin.php";
?>
<?php
    $stmt = $pdo->prepare("SELECT * FROM medical_records WHERE patient_id = ?");
    $stmt->bindParam(1, $_GET["patient_id"]);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="admin_general.css">
    <!-- <link rel="stylesheet" href="../css/medical_records.css"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<header>
    <?php include('Navbar_Admin.php');?>
</header>
<div class="container">
    <div class="sidebar">
        <div class="sidebar-1">
            <!-- ตรวจสอบว่ามีข้อมูลใน $row หรือไม่ก่อนแสดงผล -->
            <?php if($row && isset($row["patient_id"])): ?>
                รหัสผู้ป่วย: <?=$row["patient_id"]?><br>
                ชื่อ-สกุล: <?=$row["name_patient"]?>
            <?php endif; ?>
        </div>
        <div class="side-nav">
            <a href="admin_general.php?patient_id=<?=$row ["patient_id"] ?>" class="sidebar-item" id="general">ข้อมูลทั่วไป</a>
            <a href="fetch_history.php?patient_id=<?=$row ["patient_id"] ?>" class="sidebar-item" id="history">ประวัติการรักษา</a>
            <a href="admin_coursepatient.php?patient_id=<?=$row ["patient_id"] ?>" class="sidebar-item" id="course">คอร์สการรักษา</a>
        </div>
    </div>

    <div class="content-result">
    <form id="form_data" method="post" >
        <div class="form-container">
            <p>ข้อมูลส่วนตัว</p>
            <div class="card-data-personal">
                <div class="column" style="width: 200px;">
                    <label>ชื่อ-สกุล : <?=$row["name_patient"]?></label>
                    <label>วัน เดือน ปี เกิด : <?=$row["date_of_birth"]?></label>
                    <label>สถานภาพ : <?=$row["status"]?></label>
                    <label>ศาสนา : <?=$row["religion"]?></label>
                </div>
                <div class="column">
                    <label>เลขบัตรประชาชน : <?=$row["id_card"]?></label>
                    <label>อายุ : <?=$row["age"]?> ปี</label>
                    <label>เชื้อชาติ : <?=$row["ethnicity"]?></label>
                </div>
                <div class="column">
                    <label>เพศ : <?=$row["gender"]?></label>
                    <label>อาชีพ : <?=$row["career"]?></label>
                    <label>สัญชาติ : <?=$row["nationality"]?></label>  
                </div>
            </div>

            <p>ข้อมูลที่อยู่</p>
            <div class="card-data-address">
                <div class="column" style="width: 100px;">
                    <label>ที่อยู่ : <?=$row["address"]?></label>
                    <label>ตำบล : <?=$row["sub_district"]?></label>
                </div>
                <div class="column" style="width: 100px;">
                    <label>จังหวัด : <?=$row["province"]?></label>
                    <label>รหัสไปรษณีย์ : <?=$row["zip_code"]?></label>
                </div>
                <div class="column">
                    <label>อำเภอ : <?=$row["district"]?></label>
                    <label>เบอร์โทรศัพท์ : <?=$row["tel"]?></label><br>
                </div>
            </div>

            <p>ข้อมูลอื่นๆ</p>
            <div class="card-data-other">
                <div class="column" style="width: 100px;">
                    <label>โรคประจำตัว : <?=$row["congenital_disease"]?></label>
                    <label>ประวัติแพ้ยา : <?=$row["allergy"]?></label>
                    <label>ประวัติการผ่าตัด : <?=$row["surgery_history"]?></label>
                    <label>ประวัติการประสบอุบัติเหตุ : <?=$row["accident_history"]?></label>
                    <!-- <div class="note">หมายเหตุ : หากไม่มีประวัติให้ใช้สัญลักษณ์ "-"</div> -->
                </div>
            </div>
            <div class="center">
                <button id="submit" type="submit">แก้ไขประวัติผู้ป่วย</button>
            </div>
    </form>
        </div>


    <!-- ฟอร์มสำหรับแก้ไข -->
    <form id="form_editdata" method="post" >
        <div class="form-container">
            <p>ข้อมูลส่วนตัว</p>
            <div class="card-data-personal">
                <div class="column" style="width: 200px;">
                    <label>ชื่อ-สกุล : <input type="text" name="name_patient" value="<?=$row["name_patient"]?>" readonly></label>
                    <label>วัน เดือน ปี เกิด : <input type="date" name="date_of_birth" value="<?=$row["date_of_birth"]?>"></label>
                    <label>สถานภาพ :
                        <input type="checkbox" value="โสด" name="status" <?php if($row["status"] == "โสด") echo "checked"; ?>> โสด
                        <input type="checkbox" value="สมรส" name="status" <?php if($row["status"] == "สมรส") echo "checked"; ?>> สมรส
                        <input type="checkbox" value="หย่าร้าง" name="status" <?php if($row["status"] == "หย่าร้าง") echo "checked"; ?>> หย่าร้าง
                    </label>
                    <label>ศาสนา : <input type="text" name="religion" style="width: 50px;" value="<?=$row["religion"]?>"></label>
                </div>
                <div class="column">
                    <label>เลขบัตรประชาชน : <input type="text" name="id_card" value="<?=$row["id_card"]?>" style="width: 120px;"></label>
                    <label>อายุ : <input type="text" name="age" pattern="\d{1,2}" 
                    title="กรุณากรอกเป็นตัวเลข" style="width: 30px;" value="<?=$row["age"]?>"> ปี</label>
                    <label>เชื้อชาติ : <input type="text" name="ethnicity" style="width: 50px;" value="<?=$row["ethnicity"]?>"></label>
                </div>
                <div class="column">
                <label>เพศ :
                    <select id="typegender" name="gender">
                        <option value="หญิง" <?php if($row["gender"] == "หญิง") echo "selected"; ?>>หญิง</option>
                        <option value="ชาย" <?php if($row["gender"] == "ชาย") echo "selected"; ?>>ชาย</option>
                    </select>
                </label>
                    <label>อาชีพ : <input type="text" name="career" value="<?=$row["career"]?>"></label>
                    <label>สัญชาติ : <input type="text" name="nationality" style="width: 50px;" value="<?=$row["nationality"]?>"></label>  
                </div>
            </div>

            <p>ข้อมูลที่อยู่</p>
            <div class="card-data-address">
                <div class="column" style="width: 100px;">
                    <label>ที่อยู่ : <input type="text" name="address" value="<?=$row["address"]?>"></label>
                    <label>ตำบล : <input type="text" name="sub_district" value="<?=$row["sub_district"]?>"></label>
                </div>
                <div class="column" style="width: 100px;">
                    <label>จังหวัด : <input type="text" name="province" value="<?=$row["province"]?>"></label>
                    <label>รหัสไปรษณีย์ : <input type="text" name="zip_code" pattern="\d{5}" 
                    title="กรุณากรอกเป็นตัวเลข 5 หลัก" style="width: 100px;" value="<?=$row["zip_code"]?>"></label>
                </div>
                <div class="column">
                    <label>อำเภอ : <input type="text" name="district" value="<?=$row["district"]?>"></label>
                    <label>เบอร์โทรศัพท์ : <input type="text" pattern="[0-9]{10}" 
                    title="กรุณากรอกเป็นตัวเลข 10 หลัก "name="tel" value="<?=$row["tel"]?>"></label><br>
                </div>
            </div>

            <p>ข้อมูลอื่นๆ</p>
            <div class="card-data-other">
                <div class="column" style="width: 100px;">
                    <label>โรคประจำตัว : <input type="text" name="congenital_disease" value="<?=$row["congenital_disease"]?>"></label>
                    <label>ประวัติแพ้ยา : <input type="text" name="allergy" value="<?=$row["allergy"]?>"></label>
                    <label>ประวัติการผ่าตัด : <input type="text" name="surgery_history" value="<?=$row["surgery_history"]?>"></label>
                    <label>ประวัติการประสบอุบัติเหตุ : <input type="text" name="accident_history" value="<?=$row["accident_history"]?>"></label>
                    <div class="note">หมายเหตุ : หากไม่มีประวัติให้ใช้สัญลักษณ์ "-"</div>
                </div>
            </div>
            <div class="center">
                <button id="submit" type="submit">บันทึกการแก้ไข</button>
            </div>
    </form>
        </div>
    </div>
    </div>
    </div>
</body>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>

    function refreshPage() {
        window.location.reload(); // รีเฟรชหน้า
    }

    document.getElementById("submit").addEventListener("click", function(event) {
        event.preventDefault(); // หยุดการทำงานของฟอร์มเพื่อป้องกันการโหลดหน้าใหม่

        // ซ่อนฟอร์มเมื่อคลิกที่ปุ่ม "แก้ไขประวัติผู้ป่วย"
        document.getElementById("form_data").style.display = "none";
        document.getElementById("form_editdata").style.display = "block";
    });

    document.addEventListener("DOMContentLoaded", function() {
        var form = document.getElementById("form_editdata");
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
            xhr.open("POST", "../ConnectDB/edit_patient_medical.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Show success message using SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกการแก้ไขเรียบร้อยแล้ว',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            document.getElementById("form_data").style.display = "block";
                            document.getElementById("form_editdata").style.display = "none";
                            refreshPage()
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        console.error("เกิดข้อผิดพลาดในการบันทึกข้อมูล");
                    }
                }
                };
        xhr.send(formData);
        });
    });
    
</script>
</html>
