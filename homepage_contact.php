<?php

include "connect.php";
include "session/sessionlogin.php";

?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <!-- meta data -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!--font-family-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&amp;subset=devanagari,latin-ext" rel="stylesheet">
    <link rel="stylesheet" href="css/homepage_contact.css">
</head>


<header>
    <?php include "Navbar_Customers.php" ?>
</header>
<body>
    <div class="head-about">
    <div class="about-section">
                <div class="about">
                    <h1> เกี่ยวกับเรา </h1>
                    <div class="detail">
                    เวฬารักษ์ สหคลินิก ทำหัตถการโดยแพทย์แผนไทย-แพทย์แผนจีนที่มีใบประกอบวิชาชีพ มีรูปแบบการรักษาที่แตกต่างเฉพาะบุคคลตามอาการ  สามารถปรึกษาและตรวจร่างกายได้ที่คลินิกโดยไม่เสียค่าใช้จ่าย</div>
                    <p>คลินิกเปิดทำการเวลา 09.00 น. ถึง 20.00 น.</p>
                    <p>ใบอนุญาตโฆษณาสถานพยาบาลเลขที่ ฆสพ.ฉช.๒๖/๒๕๖๖</p>
                </div> 
                <img src="icons/about-us.jpg" alt="about-us">            
  </div>
</div>


    <div class="contact">
    <h1>ช่องทางการติดต่อ</h1>
    <hr>

        <!-- facebook page -->
        <div class="row">
        <div class="column">
            <div class="card">
            <div class="container">
                <div class="img-card"><img src="icons/facebook.svg" alt="facebook"></p></div>
                <p>เวฬารักษ์ สหคลินิก</p>
                <p><a class="button" href="https://www.facebook.com/veraruk.clinic">Contact</a></p>
            </div>
            </div>
        </div>
         <!-- tel -->
         <div class="column">
            <div class="card">
            <div class="container">
                <div class="img-tel"><img  id="tel" src="icons/telephone.png" alt="tel"></p></div>
                <p>tel</p>
                <p>061-954-5461</p>
            </div>
            </div>
            </div>
        <!-- line official account -->
        <div class="column">
            <div class="card">
            <div class="container">
                <div class="img-line"><img  id="line" src="icons/line-icon.png" alt="line"></p></div>
                <p>@veraruk</p>
                <p><a class="button" href=" https://lin.ee/eMkYtE3">Contact</a></p>
            </div>
            </div>
        </div>
        </div>
</div>
    <div class="google_map">
        <div class="row_map">
                <div class="colum_map">
                <img src="icons/verarak_map.jpg" alt="about-us">
                </div>
                <div class="colum_google">
                <h1> ที่อยู่ </h1>
                <p>9/9 ซอย 7 ถนน ระเบียบกิจอนุสรณ์ ตำบลบางคล้า อำเภอบางคล้า จังหวัดฉะเชิงเทรา ประเทศไทย </p>
                <a href="https://maps.app.goo.gl/Fsa5jWnjqTrdA7ti7" class="button-map">เปิดใน Google map </a>
                </div>
        </div>
</div>
    
</body>
<footer>


</footer>
