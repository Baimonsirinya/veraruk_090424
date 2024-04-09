<?php
    include "../connect.php";
    include "../session/sessionlogin.php";
?>

<?php
    // ตั้งค่าโซนเวลาเป็น Asia/Bangkok (เวลาไทย)
    date_default_timezone_set('Asia/Bangkok');

    // รับวันที่ปัจจุบันในรูปแบบที่ต้องการ (วัน/เดือน/ปี)
    $current_date = date('Y-m-d'); // หรือ 'd/m/Y' ถ้าต้องการในรูปแบบวัน/เดือน/ปี

    // นำค่า $current_date ไปใช้ในการคิวรี่ฐานข้อมูล
    $stmt = $pdo->prepare("SELECT treatment.treatment_id, treatment.patient_id, medical_records.name_patient, treatment.medicine_name, treatment.status
    FROM treatment JOIN medical_records ON treatment.patient_id = medical_records.patient_id WHERE date = ? AND treatment.status = 'ตรวจเสร็จแล้ว' ");
    $stmt->execute([$current_date]);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" href="admin_medicine.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


  <script>

    function showformcheck(treatmentId){
        var popup = document.getElementById("myModal");
        popup.style.display = "flex";
        var treatmentIdInput = document.getElementById("treatment_id"); 
    }

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
                <a href="admin_seedoctor.php" class="sidebar-item" style="color: black;">พบแพทย์</a>
                <a href="admin_medicine.php" class="sidebar-item" style="color: black;">จ่ายยา</a>
            </div>
        </div>
      <div class="content">
        <div class="position-table">
            <?php if ($stmt->rowCount() > 0): ?>
            <table>
                <tr>
                    <th style="display: none;">รหัสtm</th>
                    <th>รหัสผู้ป่วย</th>
                    <th>ชื่อผู้ป่วย</th>
                    <th>ใบสั่งยา</th>
                    <th>Status</th>
                </tr>
                <?php while($row=$stmt->fetch()): ?>
                    <tr>
                        <td style="display: none;"><?=$row ["treatment_id"] ?></td>
                        <td><?=$row ["patient_id"] ?></td>
                        <td><?=$row ["name_patient"] ?></td>
                        <td><button class="button-check" onclick="showformcheck('<?= $row['treatment_id'] ?>')" data-treatment-id="<?= $row['treatment_id'] ?>">ดูใบสั่งยา</button></td>
                        <td><?=$row ["status"] ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>ไม่มีการจ่ายยา</p>
        <?php endif; ?>
        </div>
      </div>
    </div>


    <div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="popupContent">
            <input type="hidden" id="treatment_id" name="treatment_id" class="input-field" readonly>
        </div>
        <div class='center'>
            <button id='submitFinish' type='submit' data-treatment-id=''>จ่ายยาเสร็จสิ้น</button>
        </div>
    </div>
</div>

</body>

        


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById("myModal");
    var closeButton = document.getElementsByClassName("close")[0];
    var submitButton = document.getElementById("submitFinish");

    // เมื่อคลิกที่ปุ่ม "ดูใบสั่งยา"
    var buttons = document.querySelectorAll('.button-check');
    buttons.forEach(function(button) {
        button.addEventListener('click', function() {
            var row = this.closest('tr');
            var treatmentId = row.cells[0].innerText.trim(); // ดึงค่า treatment_id จาก cell แรกของแถว
            // ส่งค่า treatment_id ไปยังไฟล์ PHP เพื่อดึงข้อมูลใบสั่งยา
            fetchMedicineData(treatmentId);
        });
    });

    // ฟังก์ชันสำหรับดึงข้อมูลใบสั่งยาผ่าน AJAX
    function fetchMedicineData(treatmentId) {
        console.log(treatmentId);
        var formData = new FormData();
        formData.append('treatmentId', treatmentId);

        // สร้าง XMLHttpRequest Object
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    modal.style.display = "block";
                    var popupContent = document.getElementById('popupContent');
                    popupContent.innerHTML = xhr.responseText;

                    // เมื่อกดปุ่ม "จ่ายยาเสร็จสิ้น" ให้เซ็ตค่า data-treatment-id ของปุ่ม
                    submitButton.setAttribute('data-treatment-id', treatmentId);
                } else {
                    console.error('เกิดข้อผิดพลาด: ' + xhr.status);
                }
            }
        };

        // เปิดการเชื่อมต่อ
        xhr.open('POST', '../ConnectDB/fetch_medicine.php', true);
        // ส่งข้อมูล FormData ไปยังไฟล์ PHP
        xhr.send(formData);
    }

    // ฟังก์ชันสำหรับปรับปรุงเนื้อหาใน modal
    function updateModalContent(response) {
        var popupContent = document.getElementById('popupContent');
        popupContent.innerHTML = response;
    }

    // เมื่อคลิกที่ปุ่ม "จ่ายยาเสร็จสิ้น"
    submitButton.addEventListener('click', function() {
        var formData = new FormData();
        var treatmentId = this.getAttribute("data-treatment-id");
        formData.append('treatmentId', treatmentId);
        console.log(treatmentId);

        if (treatmentId) {
            Swal.fire({
                title: 'ยืนยันการเสร็จสิ้นการจ่ายยา',
                text: 'คุณต้องการที่จะทำการเสร็จสิ้นการจ่ายยาหรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, เสร็จสิ้นการจ่ายยา',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'เสร็จสิ้นการจ่ายยา',
                                    showConfirmButton: true,
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        modal.style.display = "none";
                                        location.reload();
                                    }
                                });
                            } else {
                                console.error('เกิดข้อผิดพลาด: ' + xhr.status);
                            }
                        }
                    };

                    xhr.open('POST', '../ConnectDB/update_status_complete.php', true);
                    xhr.send(formData);
                }
            });
        } else {
            console.error('ไม่พบรหัสการรักษา');
        }
    });

    // เมื่อคลิกที่ปุ่มปิด (X) เพื่อปิดโมดัล
    closeButton.addEventListener('click', function() {
        modal.style.display = "none";
    });

    // เมื่อผู้ใช้คลิกที่พื้นหลังที่มืดเพื่อปิดโมดัล
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});


// // ประกาศ updateModalContent ในระดับสูงสุดของสคริปต์ JavaScript
// function updateModalContent(response) {
//     var popupContent = document.getElementById('popupContent');
//     popupContent.innerHTML = response;
// }

</script>



</html>

