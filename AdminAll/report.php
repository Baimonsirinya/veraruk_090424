<?php
    include "../connect.php";
    include "../session/sessionlogin.php";

    $stmt = $pdo->prepare("SELECT `procedure`, quantity, COUNT(*) as total_occurrences FROM purchase GROUP BY `procedure`, quantity ORDER BY total_occurrences DESC");
    $stmt->execute();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="report.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>คอร์สยอดนิยม</title>
</head>
<body>
    <header>
        <?php include('Navbar_Admin.php');?>
    </header>
    <h2>คอร์สที่ได้รับความนิยมมากที่สุด</h2>
    <div class="container">
        <div class="content">
                <table>
                    <tr>
                        <th>ชื่อคอร์ส</th>
                        <th>จำนวนครั้ง</th>
                        <th>จำนวนการจอง</th>
                    </tr>
                    <?php while($row=$stmt->fetch()): ?>
                    <tr>
                    <td><?php echo $row["procedure"]; ?></td>
                    <td><?php echo $row["quantity"]; ?></td>
                    <td><?php echo $row["total_occurrences"]; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
