<?php

    session_start();

    if(isset($_SESSION["user"])) {

        header("Location: index.php");

        exit();

    }

    $title = "نسيت كلمة المرور";

    include "init.php";

    $action = isset($_GET["action"]) ? $_GET["action"] : 0;

    if($action === "reset-password") {

        ?>

            <div class="container">
                <form class="form" method="POST" action="?action=auth">
                    <h1 class="reset-password">Reset Password</h1>
                    <input
                            class       =   "email"
                            name        =   "email"
                            type        =   "text"
                            placeholder =   "Email"
                    />
                    <input
                            class       =   "reset"
                            type        =   "submit"
                            id          =   "register"
                            value       =   "Reset"
                    />
                </form>
            </div>

        <?php

    } elseif($action === "auth") {

        if(isset($_POST["email"]) && $_SERVER["REQUEST_METHOD"] === "POST") {

            $email = $_POST["email"];

            $stmt = $conn->prepare(
                "SELECT
                    email
                FROM
                    users
                WHERE
                    email = ?"
            );

            $stmt->execute(
                array($email)
            );

            $check = $stmt->rowCount();

            if($check === 1) {

                // $authNumber = rand(0, 1000000);

                // $to = "N.Mohammed1999@outlook.sa";

                // $subject = "Reset password";

                // $message = $authNumber;

                // mail("N.Mohammed1999@outlook.sa", "Reset password", "Your verification code is 1010", "From: naif@gmail.com");

                // $randNumber = rand(100000, 999999);

                ?>

                    <div class="container">
                        <div class="form">
                            <h1 class="authentication">Authentication</h1>
                            <input
                                class       =   "auth"
                                type        =   "text"
                                placeholder =   "Authentication"
                            />
                            <input
                                class       =   "check"
                                type        =   "submit"
                                id          =   "register"
                                value       =   "Check"
                            />
                        </div>
                    </div>

                <?php

            } else {

                ?>

                    <div class="container">
                        <div class="error">
                            This email doesn't exists !
                            <i class="fa-solid fa-circle-exclamation error-icon"></i>
                        </div>
                    </div>

                <?php
                
            }

        } else {

            ?>

                <div class="container">
                    <div class="error">
                        This page doesn't exists !
                        <i class="fa-solid fa-circle-exclamation error-icon"></i>
                    </div>
                </div>

            <?php

        }


    } else {

        ?>

            <div class="container">
                <div class="error">
                    This page doesn't exists !
                    <i class="fa-solid fa-circle-exclamation error-icon"></i>
                </div>
            </div>

        <?php

    }