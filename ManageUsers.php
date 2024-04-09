<?php 
    include "connect.php";
    include "session/sessionlogin.php";


    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'user'");
    $stmt->execute();

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="css/ManageUsers.css">
    <link rel="stylesheet" href="ForManageUsers/Table.css">
    <link rel="stylesheet" href="css/Popup.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<header>
    <?php include "Navbar_Managers.php"; ?>
</header>
<body>

<div class="container-add">
    <button class="button-add">เพิ่มบัญชีผู้ใช้งาน +</button>
</div>

<div id="popupContainer" class="popup-container">
    <div class="popup-content">
        <!-- เพิ่มฟอร์มสำหรับกรอกข้อมูล -->
        <form id="registrationForm" method="post" onsubmit="submitForm(event)">
            <p>Name :</p> 
            <input class="text-box" type="text" id="name" name="name" placeholder="Name"></br></br>
            <p>Lastname :</p>
            <input class="text-box" type="text" id="lastname" name="lastname" placeholder="Lastname"></br></br>
            <p>Tel :</p>
            <input class="text-box" type="text" id="tel" name="tel" placeholder="Tel"></br></br>
            <p>หมายเลขบัตรประชาชน :</p>
            <input class="text-box" type="text" id="citizen_id" name="citizen_id" placeholder="หมายเลขบัตรประชาชน">

            <div id="citizen-error"></div><br>

            <p>Password :</p>
            <input class="text-box" type="password" id="password" name="password" placeholder="Password"></br></br>
            <p>Confirm Password :</p>
            <input class="text-box" type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password"></br></br>
            <p>Role :</p>
            <select id="role" name="role">
                <option value="user">user</option>
                <option value="admin">admin</option>
                <option value="doctor">doctor</option>
                <option value="manager">manager</option>
            </select>
            <div class="image-doc" id="doc_upload">
                <div class="imagePreview">
                    <img id="uploadedImage">
                </div>
                <input type="file" id="imageUpload" name="imageUpload"><br>
            </div>
            <div class="sign-up">
                <button class="button-submit">Sign Up</button>
            </div>
        </form>
        <!-- ปุ่มปิดหน้า pop-up -->
        <img id="closePopup" src="images/close_icon.png" alt="ปิด" onclick="closePopup()">
       
    </div>
</div>

<div class="container">
    <div class="menu">
        <button id="customersBtn">ลูกค้า</button>
        <button id="employeesBtn">พนักงาน</button>
        <button id="doctorsBtn">หมอ</button>
        <button id="managersBtn">ผู้จัดการ</button>
    </div>
</div>


<?php if ($stmt->rowCount() > 0): ?>
    <div class="position-search">
        <input type="text" id="customerSearchInput" placeholder="ค้นหาลูกค้า...">
        <input type="text" id="employeeSearchInput" style="display:none;" placeholder="ค้นหาพนักงาน...">
        <input type="text" id="doctorSearchInput" style="display:none;" placeholder="ค้นหาหมอ...">
        <input type="text" id="managerSearchInput" style="display:none;" placeholder="ค้นหาผู้จัดการ...">
    </div>
    <div class="all-role" id="all-role">
    
    <div class="text-role">บัญชีผู้ใช้ของลูกค้า</div>
        <table border ='1'>
            <tr>
                <th>หมายเลขบัตรประชาชน</th>
                <th>Password</th>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
                <th>เบอร์โทร</th>
                <th></th>
            </tr>
            <?php while ($row = $stmt->fetch()): ?>
                <tr>
                    <td><?= $row["citizen_id"] ?></td>
                    <td><?= $row["password"] ?></td>
                    <td><?= $row["name"] ?></td>
                    <td><?= $row["lastname"] ?></td>
                    <td><?= $row["tel"] ?></td>
                    <td><button class='button-delete' citizen_id='<?= $row["citizen_id"] ?>' onclick='deleteUser("<?= $row["citizen_id"] ?>")'>ลบ</button></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>ไม่พบข้อมูลลูกค้า</p>
    <?php endif; ?>
    </div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    // ฟังก์ชันสำหรับปิด popup
    function closePopup() {
    var popupContainer = document.getElementById('popupContainer');
    popupContainer.style.display = 'none';
    }

    // เลือกปุ่ม "เพิ่มบัญชีผู้ใช้งาน +"
    var addButton = document.querySelector('.button-add');

    // เลือก pop-up container
    var popupContainer = document.getElementById('popupContainer');

    // เลือกปุ่ม "ปิด"
    var closeButton = document.getElementById('closePopup');

    // เมื่อคลิกที่ปุ่ม "เพิ่มบัญชีผู้ใช้งาน +"
    addButton.addEventListener('click', function() {
        popupContainer.style.display = "flex";
    });

    // เมื่อคลิกที่ปุ่ม "ปิด"
    closeButton.addEventListener('click', function() {
        popupContainer.style.display = "none";
    });

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("citizen_id").addEventListener("input", function() {
        var citizen_id = this.value;

        // เรียกใช้งาน AJAX เพื่อตรวจสอบชื่อผู้ใช้
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "CheckCitizen.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // รับค่าจากไฟล์ PHP
                    var count = xhr.responseText;

                    // ถ้ามีชื่อผู้ใช้ในฐานข้อมูลแล้ว
                    if (count > 0) {
                        document.getElementById("citizen_id").classList.add("duplicate-citizen");
                        document.getElementById("citizen-error").innerHTML = "ชื่อผู้ใช้นี้ถูกใช้งานแล้ว";
                    } else {
                        document.getElementById("citizen_id").classList.remove("duplicate-citizen");
                        document.getElementById("citizen-error").innerHTML = ""; 
                    }
                } else {
                    console.log("เกิดข้อผิดพลาดในการร้องขอ");
                }
            }
        };
        // ส่งข้อมูล citizen_id ไปยังไฟล์ PHP
        xhr.send("citizen_id=" + citizen_id);
    });
    document.getElementById("registrationForm").addEventListener("submit", function(event) {
    event.preventDefault();

    var password = document.getElementById("password").value;
    var confirm_password = document.getElementById("confirm_password").value;

    // ตรวจสอบว่ารหัสผ่านและยืนยันรหัสผ่านตรงกันหรือไม่
    if (password !== confirm_password) {
        alert("รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน");
        // เพิ่มคลาส error ที่กล่อง input confirm password
        document.getElementById("confirm_password").classList.add("password-mismatch");
        return;
    } else {
        // ถ้ารหัสผ่านตรงกัน ลบคลาส error ที่กล่อง input confirm password ออก
        document.getElementById("confirm_password").classList.remove("password-mismatch");
    }

    // ถ้ารหัสผ่านตรงกัน ให้ทำการส่งข้อมูลไปยังไฟล์ PHP ตรวจสอบและบันทึกข้อมูล
    var formData = new FormData(this); // สร้าง FormData object จากแบบฟอร์ม

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "AddRegister-Manager.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                closePopup();
                Swal.fire({
                    icon: 'success',
                    title: 'เสร็จสิ้น',
                    text: xhr.responseText,
                    timer: 1000
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload(); // รีโหลดหน้าเว็บหลังจากคลิก OK ใน SweetAlert
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'มีข้อผิดพลาดในการส่งข้อมูล'
                });
            }
        }
    };
    xhr.send(formData); // ส่งข้อมูล FormData ไปยังไฟล์ PHP
    });
    });
    



    document.getElementById("customersBtn").addEventListener("click", function() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "ForManageUsers/users.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("all-role").innerHTML = xhr.responseText;
            document.getElementById("customerSearchInput").style.display = "block";
            document.getElementById("doctorSearchInput").style.display = "none";
            document.getElementById("managerSearchInput").style.display = "none";
            document.getElementById("employeeSearchInput").style.display = "none"
        }
    };
    xhr.send();
    });

    document.getElementById("employeesBtn").addEventListener("click", function() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "ForManageUsers/Admin.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("all-role").innerHTML = xhr.responseText;
            document.getElementById("customerSearchInput").style.display = "none";
            document.getElementById("doctorSearchInput").style.display = "none";
            document.getElementById("managerSearchInput").style.display = "none";
            document.getElementById("employeeSearchInput").style.display = "block";
        }
    };
    xhr.send();
    });

    document.getElementById("doctorsBtn").addEventListener("click", function() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "ForManageUsers/Doctor.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("all-role").innerHTML = xhr.responseText;
            document.getElementById("customerSearchInput").style.display = "none";
            document.getElementById("managerSearchInput").style.display = "none";
            document.getElementById("employeeSearchInput").style.display = "none";
            document.getElementById("doctorSearchInput").style.display = "block";
        }
    };
    xhr.send();
    });

    document.getElementById("managersBtn").addEventListener("click", function() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "ForManageUsers/Manager.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("all-role").innerHTML = xhr.responseText;
            document.getElementById("customerSearchInput").style.display = "none";
            document.getElementById("doctorSearchInput").style.display = "none";
            document.getElementById("employeeSearchInput").style.display = "none";
            document.getElementById("managerSearchInput").style.display = "block";
        }
    };
    xhr.send();
    });


    document.addEventListener("DOMContentLoaded", function() {
        // เมื่อมีการพิมพ์ในช่องค้นหา
        document.getElementById("customerSearchInput").addEventListener("input", function() {
            // เรียกฟังก์ชัน fetchSearchData() เมื่อมีการพิมพ์
            fetchSearchUser();
        });
    });

    function fetchSearchUser() {
        // รับค่าคำค้นหา
        var keyword = document.getElementById("customerSearchInput").value;

        var xhr = new XMLHttpRequest();
        
        // กำหนดเมธอดและ URL
        xhr.open("GET", "ForManageUsers/search_users.php?search=" + keyword, true);

        // เมื่อข้อมูลส่งกลับมา
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var searchData = xhr.responseText;
                    // เอา searchData ไปแสดงแทนที่ all-role
                    document.getElementById("all-role").innerHTML = searchData;
                } else {
                    console.error("เกิดข้อผิดพลาดในการเรียกข้อมูล");
                }
            }
        };

        // ส่งคำค้นหาไปยังไฟล์ PHP
        xhr.send();
    }


    document.addEventListener("DOMContentLoaded", function() {
        // เมื่อมีการพิมพ์ในช่องค้นหา
        document.getElementById("employeeSearchInput").addEventListener("input", function() {
            // เรียกฟังก์ชัน fetchSearchData() เมื่อมีการพิมพ์
            fetchSearchAdmin();
        });
    });

    function fetchSearchAdmin() {
        // รับค่าคำค้นหา
        var keyword = document.getElementById("employeeSearchInput").value;

        var xhr = new XMLHttpRequest();
        
        // กำหนดเมธอดและ URL
        xhr.open("GET", "ForManageUsers/search_admin.php?search=" + keyword, true);

        // เมื่อข้อมูลส่งกลับมา
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var searchData = xhr.responseText;
                    // เอา searchData ไปแสดงแทนที่ all-role
                    document.getElementById("all-role").innerHTML = searchData;
                } else {
                    console.error("เกิดข้อผิดพลาดในการเรียกข้อมูล");
                }
            }
        };

        // ส่งคำค้นหาไปยังไฟล์ PHP
        xhr.send();
    }



    document.addEventListener("DOMContentLoaded", function() {
        // เมื่อมีการพิมพ์ในช่องค้นหา
        document.getElementById("doctorSearchInput").addEventListener("input", function() {
            // เรียกฟังก์ชัน fetchSearchData() เมื่อมีการพิมพ์
            fetchSearchDoctor();
        });
    });

    function fetchSearchDoctor() {
        // รับค่าคำค้นหา
        var keyword = document.getElementById("doctorSearchInput").value;

        var xhr = new XMLHttpRequest();
        
        // กำหนดเมธอดและ URL
        xhr.open("GET", "ForManageUsers/search_doctor.php?search=" + keyword, true);

        // เมื่อข้อมูลส่งกลับมา
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var searchData = xhr.responseText;
                    // เอา searchData ไปแสดงแทนที่ all-role
                    document.getElementById("all-role").innerHTML = searchData;
                } else {
                    console.error("เกิดข้อผิดพลาดในการเรียกข้อมูล");
                }
            }
        };

        // ส่งคำค้นหาไปยังไฟล์ PHP
        xhr.send();
    }


    document.addEventListener("DOMContentLoaded", function() {
        // เมื่อมีการพิมพ์ในช่องค้นหา
        document.getElementById("managerSearchInput").addEventListener("input", function() {
            // เรียกฟังก์ชัน fetchSearchData() เมื่อมีการพิมพ์
            fetchSearchManager();
        });
    });

    function fetchSearchManager() {
        // รับค่าคำค้นหา
        var keyword = document.getElementById("managerSearchInput").value;

        var xhr = new XMLHttpRequest();
        
        // กำหนดเมธอดและ URL
        xhr.open("GET", "ForManageUsers/search_manager.php?search=" + keyword, true);

        // เมื่อข้อมูลส่งกลับมา
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var searchData = xhr.responseText;
                    // เอา searchData ไปแสดงแทนที่ all-role
                    document.getElementById("all-role").innerHTML = searchData;
                } else {
                    console.error("เกิดข้อผิดพลาดในการเรียกข้อมูล");
                }
            }
        };

        // ส่งคำค้นหาไปยังไฟล์ PHP
        xhr.send();
    }

            function deleteUser(citizen_id) {
                swal({
                    title: "คุณแน่ใจหรือไม่ที่จะลบ?",
                    text: citizen_id + " ออกจากระบบ?",
                    icon: "warning",
                    buttons: ["ไม่", "ใช่"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'ForManageUsers/DeleteUsers.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                var element = document.getElementById(citizen_id);
                                if (element) {
                                    element.remove();
                                }
                                swal("ลบเรียบร้อย!", {
                                    icon: "success",
                                }).then(() => {
                                    location.reload(); // รีเฟรชหน้าเว็บหลังจากการลบเสร็จสมบูรณ์
                                });
                            } else {
                                alert('เกิดข้อผิดพลาดในการลบผู้ใช้งาน');
                            }
                        };
                        xhr.send('citizen_id=' + citizen_id);
                    } else {
                        swal("ยกเลิกการลบ", {
                            icon: "info",
                        });
                    }
                });
                }



    //    --------------------------------------------------แพทย์ upload รูป ------------------------------------
        function toggleImageUpload() {
        var roleSelect = document.getElementById("role");
        var doc_upload = document.getElementById("doc_upload");

        // เมื่อ role เป็น "doctor" ให้แสดงช่องอัพโหลดรูปภาพ
        if (roleSelect.value === "doctor") {
            doc_upload.style.display = "block";
        } else {
            doc_upload.style.display = "none";
        }
        }
        // เรียกใช้ฟังก์ชัน toggleImageUpload เมื่อมีการเปลี่ยนค่าใน dropdown
        document.getElementById("role").addEventListener("change", toggleImageUpload);
        document.querySelector('input[type="file"]').addEventListener('change', function() {
        // ตรวจสอบว่ามีการเลือกไฟล์ภาพหรือไม่
        if (this.files && this.files[0]) {
            var reader = new FileReader();

            // เมื่ออ่านไฟล์เสร็จสิ้น
            reader.onload = function(e) {
                // รับ URL ของรูปภาพ
                var imageURL = e.target.result;

                // แสดงรูปภาพในอิลิเมนต์ <img>
                document.getElementById('uploadedImage').setAttribute('src', imageURL);
            }

            // อ่านไฟล์ภาพ
            reader.readAsDataURL(this.files[0]);
        }
        });

</script>

</body>
</html>

