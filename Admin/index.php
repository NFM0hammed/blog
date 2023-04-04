<?php

    session_start();

    if(isset($_SESSION["username"])) {
        
        header("Location: dashboard.php");

        exit();

    }

    $title = "Login";

    include "init.php";

    if($_SERVER["REQUEST_METHOD"] === "POST") {

        $username     = $_POST["username"];
        $password     = $_POST["password"];
        $hashPassword = sha1($password);

        $stmt = $conn->prepare(
            "SELECT
                user_id, username, password
            FROM
                users
            WHERE
                username = ?
            AND
                password = ?
            AND
                group_id = 1" // Admin
        );

        $stmt->execute(array(
            $username,
            $hashPassword
        ));

        $getId = $stmt->fetch();

        $count = $stmt->rowCount();

        if($count === 1) {
            
            $_SESSION["userid"]   = $getId["user_id"];

            $_SESSION["username"] = $username;

            header("Location: dashboard.php");

            exit();

        } else {

            ?>

                <p class="failed-login">Login failed !</p>

            <?php

        }

    }
?>

<!-- Login form -->
<form class="register" action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
    <h3>Admin Login</h3>
    <div class="login">
        <input type="text" placeholder="Username" name="username" />
        <input type="password" placeholder="Password" name="password" />
        <input type="submit" value="Login" />
    </div>
</form>

<?php include "includes/templates/footer.php"; ?>