<?php
    include "../connect.php";
    include "../session/sessionlogin.php";
?>
<?php
    $stmt = $pdo->prepare("SELECT * FROM appointment_admin_page WHERE status = 'รอเรียกคิว'"); 
    $stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link rel="stylesheet" href="admin_seedoctor.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script>

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
      <?php
            if ($stmt->rowCount() > 0) {
            ?>
        <div class="position-table">
            <table>
              <tr>
                <th>วันเดือนปีที่นัดหมาย</th>
                <th>เวลานัดหมาย</th>
                <th>รหัสผู้ป่วย</th>
                <th>ชื่อผู้ป่วย</th>
                <th>แพทย์</th>
                <th></th>
                </tr>
              <?php while($row=$stmt->fetch()): ?>
                <tr>
                <td><?=$row ["date"] ?></td>
                <td><?=$row ["time"] ?></td>
                <td><?=$row ["patient_id"] ?></td>
                <td><?=$row ["patient_name"] ?></td>
                <td><?=$row ["doctor"] ?></td>
                <td><button class="button-check" data-appointment-id="<?= $row['appointment_id'] ?>">เรียกพบแพทย์</button></td>  
                </tr>
              <?php endwhile; ?>
            </table>  
          </div>
          <?php
            } else {
              echo "<p style='text-align: center; margin-top: 150px'>ไม่มีคิวรอพบหมอ</p>";
            }
            ?>  
      </div>
    </div>

</body>

        


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

document.addEventListener('DOMContentLoaded', function() {
    var buttons = document.querySelectorAll('.button-check');
    buttons.forEach(function(button) {
        button.addEventListener('click', function() {
            var appointmentId = this.dataset.appointmentId;
            console.log(appointmentId);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../ConnectDB/add_appointment_doctor.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = xhr.responseText;
                        if (response === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'เรียกพบแพทย์เรียบร้อย',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: response
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาดในการเชื่อมต่อ',
                            text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้'
                        });
                    }
                }
            };
            var data = 'appointment_id=' + encodeURIComponent(appointmentId);
            xhr.send(data);
        });
    });
});


</script>

</html>

