<nav>
    <div class="container">
        <!-- Sign up and sign in page -->
        <div class="register">
            <?php
                
                if(isset($_SESSION["user"])) {

                    ?>

                        <a href="logout.php">خروج</a>
                        <a href="profile.php"><?=$_SESSION["user"]?></a>

                    <?php

                } else {

                    ?>

                        <a href="signin.php">تسجيل دخول</a>
                        <a href="signup.php">إنشاء حساب</a>

                    <?php

                }

            ?>
        </div>
        <!-- Start navbar links and logo -->
        <div class="navbar">
            <i class="fa-solid fa-bars menu"></i>
            <div class="links">
                <a href="networks-section.php">قسم الشبكات</a>
                <a href="cyper-section.php">قسم الأمن السيبراني</a>
                <a href="prog-section.php">قسم البرمجة</a>
                <a href="IT-section.php">قسم تقنية المعلومات</a>
                <a href="index.php">الرئيسية</a>
            </div>
            <div class="logo">
                <h1>مدونة التقني</h1>
                <i class="fa-solid fa-blog"></i>
            </div>
        </div>
    </div>
</nav>

<!-- Scroll to top -->
<div class="scroll-to-top"><i class="fa-solid fa-chevron-up"></i></div>