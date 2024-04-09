<?php 
    include "connect.php" ;
    include "session/sessionlogin.php";
?>

<html class="no-js" lang="en">
<head>
    <!-- meta data -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!--font-family-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&amp;subset=devanagari,latin-ext" rel="stylesheet">
    
    <!-- title of site -->
    <title>Edit Course</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--responsive.css-->
    <link rel="stylesheet" href="responsive.css">
    <link rel="stylesheet" href="css/FormEdit.css">
    <!-- <link rel="stylesheet" href="HomePageCustomers.css"> -->
   
</head>
<header>
    <?php include "Navbar_Managers.php" ?>
</header>
<body>
    <?php
        // ตรวจสอบว่ามีการส่งค่า id ของคอร์สมาหรือไม่
        if(isset($_GET['id'])) {
            // ดึงค่า id ของคอร์สจาก URL
            $course_id = $_GET['id'];

            // เตรียมคำสั่ง SQL เพื่อดึงข้อมูลของคอร์ส
            $stmt = $pdo->prepare("SELECT * FROM course WHERE course_id = ?");
            $stmt->execute([$course_id]);
            $course = $stmt->fetch();

            $stmt2 = $pdo->prepare("SELECT * FROM times_of_course WHERE course_id = ?");
            $stmt2->execute([$course_id]);
            $times_and_prices = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            if($course) {
    ?>

          <form class="container-ID" method="post"  enctype="multipart/form-data">
	  		<p id="font-ID">รหัสคอร์ส :
                <input type="text" class="text-box" id="course_id_input" name="course_id" style="width: 100px;" 
                value="<?= $course['course_id'] ?>"readonly><br>
            </p>
            <img id="course_image" src="images/<?= $course['image']; ?>" alt="<?= $course['course_name']; ?>">
            <p id="font-ID">โปรดเลือกรูปภาพ:
            <label for="file-upload" class="custom-file-upload">
                Choose file
            </label>
            <input id="file-upload" type="file" name="upload">
            </p>

            <p id="font-ID">ชื่อคอร์สการรักษา :
                <input  class="text-box" type="text" id="course_name" name="course_name" placeholder="" style="width: 200px;"
                value="<?= $course['course_name'] ?>"><br>
            </p>

            <?php foreach ($times_and_prices as $row): ?>
                <div>
                    <input type="hidden" name="course_id" value="<?= $course['course_id']; ?>">
                    <input type="hidden" name="times_id" value="<?= $row['times_id']; ?>">

                    <div class="button-container">
                        <p id="font-ID">จำนวน <?= $row['number_of_times']; ?> ครั้ง 
                        ราคา 
                        <input type="number" class="text-box" name="price" id="price_<?= $row['times_id']; ?>" style="width: 100px;" value="<?= $row['price']; ?>">
                        <button type="button" class="button-save" onclick="updatePrice(<?= $row['times_id']; ?>)">บันทึก</button>
                        <button type="button" class="button-delete" onclick="DeleteAll(<?= $row['times_id']; ?>)">ลบ</button>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div id="formContainer" style="display: none;">
                <div id="display" class="display"></div>
                <p id="font-ID">จำนวน 
                    <input type="number" class="text-box" name="number_of_times" id="number_of_times" style="width: 50px;"> ครั้ง
                    ราคา <input type="number" class="text-box" name="price_add" id="price_add" style="width: 100px;">
                </p>
                <button id="submitButton" class="submit-button" name ="submitt">เพิ่มข้อมูล</button>
            </div>

            <button type="button" id="showFormButton" class = "ShowBotton">เพิ่มจำนวนครั้ง +</button>
            
            

            <p id="font-ID">รายละเอียดแนะนำ :
                <textarea class="text-box" type="text" id="recommend" name="recommend"  style="width: 300px;"
                ><?= $course['recommend'] ?></textarea><br>
            </p>
            <p id="font-ID">รายละเอียดเพิ่มเติม :
                <textarea class="text-box" type="text" id="course_detail" name="course_detail"  style="width: 400px;"
                ><?= $course['course_detail'] ?></textarea><br>
            </p>
            <button type="submit" id="submitButtonOuter" class="submit-buttonOuter">บันทึก</button>
        </form>


    <?php
            } else {
                echo "Invalid course ID.";
            }
        } else {
            echo "No course ID provided.";
        }
    ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
	    document.getElementById('showFormButton').addEventListener('click', function() {
            var formContainer = document.getElementById('formContainer');
            if (formContainer.style.display === 'none') {
                formContainer.style.display = 'block';
            } else {
                formContainer.style.display = 'none';
            }
        });
        
        document.addEventListener("DOMContentLoaded", function() {
    // เมื่อมีการเลือกไฟล์ภาพ
        document.querySelector('input[type="file"]').addEventListener('change', function() {
        // ตรวจสอบว่ามีการเลือกไฟล์ภาพหรือไม่
        if (this.files && this.files[0]) {
            var reader = new FileReader();

            // เมื่ออ่านไฟล์เสร็จสิ้น
            reader.onload = function(e) {
                // รับ URL ของรูปภาพ
                var imageURL = e.target.result;

                // แสดงรูปภาพในอิลิเมนต์ <img>
                document.getElementById('course_image').setAttribute('src', imageURL);
            }

            // อ่านไฟล์ภาพ
            reader.readAsDataURL(this.files[0]);
        }
        });
    });


    document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('submitButton').addEventListener('click', function() {
    event.preventDefault();
    // เก็บค่าจากฟอร์ม
    var course_id = document.getElementById('course_id_input').value;
    var course_name = document.getElementById('course_name').value;
    var number_of_times = document.getElementById('number_of_times').value;
    var price_add = document.getElementById('price_add').value;

    // สร้างข้อมูลที่จะส่งไปยังไฟล์ AddnumofCourse
    var data = new FormData();
    data.append('course_id', course_id);
    data.append('course_name', course_name);
    data.append('number_of_times', number_of_times);
    data.append('price_add', price_add);

    // เรียกใช้งาน XMLHttpRequest
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'AddnumofCourse-edit.php', true);

    // ส่งข้อมูลไปยังไฟล์ AddnumofCourse
    xhr.onload = function() {
        if (xhr.status === 200) {
            // การดำเนินการหลังจากทำการส่งข้อมูลสำเร็จ
            Swal.fire({
                icon: 'success',
                title: 'เพิ่มข้อมูลเสร็จสิ้น',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                location.reload();
            });
        } else {
            // การดำเนินการหลังจากที่เกิดข้อผิดพลาดในการส่งข้อมูล
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'เกิดข้อผิดพลาดในการบันทึก โปรดลองใหม่อีกครั้ง'
            });
        }
    };

    // ส่งข้อมูล
    xhr.send(data);
});
});

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('submitButtonOuter').addEventListener('click', function() {
        event.preventDefault();
        // ตรวจสอบว่ามีค่าในช่อง input ที่ต้องการหรือไม่
        var course_id = document.getElementById('course_id_input').value;
        var course_name = document.getElementById('course_name').value;
        var recommend = document.getElementById('recommend').value;
        var course_detail = document.getElementById('course_detail').value;
        var uploadFile = document.querySelector('input[type="file"]').files[0];

        // เช็คว่ามีช่อง input ว่างหรือไม่
        if (course_id.trim() === '' || course_name.trim() === '' || recommend.trim() === '' || course_detail.trim() === '' || uploadFile === null) {
            // ถ้ามีช่อง input ว่างให้แสดง SweetAlert เตือน
            Swal.fire({
                icon: 'error',
                title: 'กรุณากรอกข้อมูลให้ครบทุกช่อง',
                text: 'โปรดกรอกข้อมูลให้ครบทุกช่องก่อนบันทึก'
            });
        } else {
            // ถ้าไม่มีช่อง input ว่างให้ดำเนินการส่งข้อมูล
            var formData = new FormData();
            formData.append('course_id', course_id);
            formData.append('course_name', course_name);
            formData.append('recommend', recommend);
            formData.append('course_detail', course_detail);
            formData.append('upload', uploadFile);

            // เรียกใช้งาน XMLHttpRequest
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'Edit-CourseDetail.php', true);

            // ตรวจสอบว่าการส่งข้อมูลเสร็จสมบูรณ์
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // การดำเนินการหลังจากทำการส่งข้อมูลสำเร็จ
                    console.log(xhr.responseText); // ให้แสดงผลลัพธ์ที่ได้จากไฟล์ PHP ที่รับข้อมูล
                    Swal.fire({
                        icon: 'success',
                        title: 'การแก้ไขข้อมูลเสร็จสิ้น',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    // การดำเนินการหลังจากที่เกิดข้อผิดพลาดในการส่งข้อมูล
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'เกิดข้อผิดพลาดในการส่งข้อมูล'
                    });
                }
            };

            // ส่งข้อมูล
            xhr.send(formData);
        }
    });
});



    function updatePrice(timesId) {
        var price = document.getElementById('price_' + timesId).value;
        var courseId = <?= $course['course_id']; ?>;

        // เช็คว่า price ไม่ว่างเปล่า และเป็นตัวเลข
        if (price.trim() !== '' && !isNaN(price)) {
            var formData = new FormData();
            formData.append('course_id', courseId);
            formData.append('times_id', timesId);
            formData.append('price', price);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_price.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'อัปเดตราคาเสร็จสิ้น',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    // จัดการกรณีที่มีข้อผิดพลาดเกิดขึ้น
                    console.error('Error occurred while updating price');
                }
            };
            xhr.send(formData);
        } else {
            // แจ้งเตือนให้กรอกข้อมูลให้ถูกต้องโดยใช้ SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'ข้อผิดพลาด',
                text: 'กรุณากรอกข้อมูลให้ถูกต้อง (Price ห้ามว่างเปล่าและต้องเป็นตัวเลข)',
            });
        }
    }

    function DeleteAll(timesId) {
    Swal.fire({
        title: 'คุณต้องการลบข้อมูลนี้ใช่หรือไม่?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ลบข้อมูล!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'DeleteInCourseDetail.php', true); // แก้ไขเป็นชื่อไฟล์ PHP ที่ใช้ในการลบข้อมูล

            // เตรียมข้อมูลที่จะส่งไปยังไฟล์ Delete_All.php
            var formData = new FormData();
            formData.append('times_id', timesId);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    // กระทำหลังจากลบข้อมูลสำเร็จ
                    console.log(xhr.responseText); // ให้แสดงผลลัพธ์ที่ได้จากไฟล์ PHP
                    Swal.fire({
                        title: 'ลบข้อมูลเสร็จสิ้น',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500 // หน่วยเป็นมิลลิวินาที (1 วินาที = 1000 มิลลิวินาที)
                    }).then(() => {
                        // รีเฟรชหน้าเพื่อแสดงการเปลี่ยนแปลง
                        location.reload();
                    });
                } else {
                    // กระทำหลังจากเกิดข้อผิดพลาดในการลบข้อมูล
                    console.error('เกิดข้อผิดพลาดในการลบข้อมูล');
                    Swal.fire(
                        'เกิดข้อผิดพลาด',
                        'เกิดข้อผิดพลาดในการลบข้อมูล',
                        'error'
                    );
                }
            };

            // ส่งข้อมูลไปยังไฟล์ PHP
            xhr.send(formData);
        }
    });
}


    </script>
</body>
</html>
