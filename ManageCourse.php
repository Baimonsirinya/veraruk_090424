<?php 
    include "connect.php";
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
    <title>AddCourse</title>

    <!--responsive.css-->
    <link rel="stylesheet" href="responsive.css">
    <link rel="stylesheet" href="css/ManageCourse.css">
    <!-- <link rel="stylesheet" href="HomePageCustomers.css"> -->
   
</head>
<header>
    <?php include "Navbar_Managers.php"; ?>
</header>
<body>
    <a href="AddCourse.php" class="button-add">เพิ่มคอร์การรักษา +</a>

    <?php
        $stmt = $pdo->prepare("SELECT * FROM course");
        $stmt->execute();
        while($row = $stmt->fetch()) :
    ?>
	<div class="container">
        <img src="images/<?= $row['image']; ?>" alt="<?= $row['course_name']; ?>">
        <div class="position">
            <h1><?= $row['course_name']; ?></h1>
            <p><?= $row["recommend"] ?></p> 
            <a href="FormEdit.php?id=<?= $row['course_id'] ?>" class="button-edit">แก้ไข</a>
            <a href="CourseDelete.php" data-id="<?= $row['course_id'] ?>" class="button-delete">ลบ</a>
        </div>
    </div>
      
	  <?php endwhile; ?>

      
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
// เลือกปุ่มลบแต่ละคอร์สและกำหนดการทำงานเมื่อคลิก
var deleteButtons = document.querySelectorAll('.button-delete');
deleteButtons.forEach(function(button) {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        var courseId = this.getAttribute('data-id'); // รับค่า id ของคอร์ส

        // แสดง sweetalert เพื่อยืนยันการลบข้อมูล
        swal({
            title: "คุณแน่ใจหรือไม่ที่จะลบ?",
            text: "การลบข้อมูลจะไม่สามารถยกเลิกได้",
            icon: "warning",
            buttons: ["ยกเลิก", "ใช่, ลบข้อมูล"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                // ส่งค่า id ของคอร์สไปยังไฟล์ CourseDelete.php โดยใช้ AJAX
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'CourseDelete.php?id=' + courseId, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // ตรวจสอบคำตอบจากไฟล์ CourseDelete.php
                            var response = xhr.responseText;
                            if (response.trim() === 'success') {
                                // ลบข้อมูลสำเร็จ
                                swal("ลบข้อมูลสำเร็จ", {
                                    icon: "success",
                                }).then(() => {
                                    // รีเฟรชหน้าหลังจากลบเรียบร้อย
                                    location.reload();
                                });
                            } else {
                                // มีข้อผิดพลาดเกิดขึ้นในการลบข้อมูล
                                swal("เกิดข้อผิดพลาด", "มีข้อผิดพลาดในการลบข้อมูล", "error");
                            }
                        } else {
                            // มีข้อผิดพลาดในการเชื่อมต่อ
                            swal("เกิดข้อผิดพลาด", "เกิดข้อผิดพลาดในการลบข้อมูล", "error");
                        }
                    }
                };
                xhr.send();
            }
        });
    });
});
</script>

</body>
</html>

