<?php

    session_start();

    if(!isset($_SESSION["user"])) {

        header("Location: index.php");

        exit();

    } else {

        $title = "الملف الشخصي";

        include "init.php";

        include $template . "navbar.php";

        $action = isset($_GET["action"]) ? $_GET["action"] : "default";

        $userId = $_SESSION["id"];

        $check = checkData(
            "users",
            "user_id",
            $userId
        );

        if($check > 0) {

            $row = selectSpecificData(
                "*",
                "users",
                "",
                "WHERE user_id = ? AND group_id = 0",
                $userId
            );

        }

        if($action === "default") {

            ?>

                <div class="container">
                    <div class="profile">
                        <h1 class="title">الملف الشخصي</h1>
                        <a href="?action=edit&id=<?=$row["user_id"]?>">Edit</a>

                        <?php

                            if($row["user_img"] != "") {

                                ?>

                                    <img class="profile-img" src="Admin/uploads/<?=$row["user_img"]?>" alt="">
                                    <span class="avatar">Avatar</span>

                                <?php

                            } else {

                                ?>

                                    <img class="profile-img" src="Admin/uploads/defualt_image.png" alt="">
                                    <span class="avatar">Avatar</span>

                                <?php

                            }

                        ?>

                        <div class="profile-info">
                            <input type="text" value="<?=$row["username"]?>" readonly />
                            <input type="text" value="<?=$row["email"]?>" readonly />
                        </div>
                        <p class="date_registration">
                            Date registration
                            <span><?=$row["date_registration"]?></span>
                        </p>
                    </div>
                </div>

            <?php 

        } elseif($action === "edit") {

            $id = isset($_GET["id"]) && is_numeric($_GET["id"]) ? intval($_GET["id"]) : 0;

            if($id == $userId) {

                ?>

                    <div class="container">
                        <div class="profile">
                            <h1 class="title">تعديل الملف الشخصي</h1>

                            <?php

                                if($row["user_img"] != "") {

                                    ?>

                                        <input class="profile-img" type="file" id="profile-img" />
                                        <label for="profile-img">
                                            <img src="Admin/uploads/<?=$row["user_img"]?>" alt="">
                                            <span class="avatar">Avatar</span>
                                        </label>

                                    <?php

                                } else {

                                    ?>

                                        <input type="file" id="profile-img" />
                                        <label class="profile-img" for="profile-img">
                                            <img src="Admin/uploads/defualt_image.png" alt="">
                                            <span class="avatar">Avatar</span>
                                        </label>

                                    <?php

                                }

                            ?>

                            <div class="profile-info">
                                <input class="username" type="text" value="<?=$row["username"]?>" placeholder="Username" />
                                <input class="email" type="text" value="<?=$row["email"]?>" placeholder="Email" />
                            </div>
                            <p class="date_registration">
                                Date registration
                                <span><?=$row["date_registration"]?></span>
                            </p>
                            <input class="user-id" type="hidden" value="<?=$row["user_id"]?>" />
                            <input type="submit" value="Update" />
                        </div>
                    </div>

                <?php

            } else {

                ?>

                    <div class="container">
                        <div class="error">
                            This id doesn't exists !
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
    
    }

?>

<script>
    /*
        Edit profile Ajax
    */
    let profile    = document.querySelector(".profile"),
        avatar     = document.querySelector(".profile #profile-img"),
        user_id    = document.querySelector(".profile .user-id"),
        username   = document.querySelector(".profile .profile-info .username"),
        email      = document.querySelector(".profile .profile-info .email"),
        edit       = document.querySelector(".profile input[type='submit']"),
        img        = document.querySelector(".profile label.profile-img img"),
        successMsg = document.createElement("div"),
        errorMsg   = document.createElement("div"),
        alertMsg   = document.createElement("div");

    // Create an Ajax call
    let request = new XMLHttpRequest();

    // URL of avatar
    let urlImg  = "";

    // Add photo and display it
    avatar && avatar.addEventListener("change", () => {

        const fileReader = new FileReader();

        fileReader.addEventListener("load", () => {

            urlImg = fileReader.result;

            img.src = urlImg;

        });

        fileReader.readAsDataURL(avatar.files[0]);

    });


    // Send Ajax after click
    edit && edit.addEventListener("click", () => { editData(); });
    
    // Send Ajax to edit profile data
    function editData() {

        // Array of editing data
        const data = [user_id.value, username.value, email.value];

        const formData = new FormData();

        formData.append("file", avatar.files[0]);

        formData.append("allData", JSON.stringify(data));
        
        request.onreadystatechange = function () {

            if(this.readyState === 4 && this.status === 200) {

                if(username.value != "" && email.value != "") {

                    if(this.responseText == 1) {
    
                        errorMsg.style.display = "none";

                        alertMsg.style.display = "none";
    
                        successMsg.style.display = "block";                    
    
                        successMsg.className = "success";
    
                        successMsg.textContent = "successfully updated";
    
                        profile.prepend(successMsg);
    
                    } else {
    
                        successMsg.style.display = "none";

                        alertMsg.style.display = "none";
    
                        errorMsg.style.display = "block";
    
                        errorMsg.className = "error";
    
                        errorMsg.textContent = "The img, size or type isn't correct !";
    
                        profile.prepend(errorMsg);
    
                    }

                } else {

                    successMsg.style.display = "none";

                    errorMsg.style.display = "none";

                    alertMsg.style.display = "block";

                    alertMsg.className = "error";

                    alertMsg.textContent = "The fields are empty !";

                    profile.prepend(alertMsg);

                }

            }

        }

        // Make a request
        request.open("POST", "ajax/edit-profile.php", true);

        // Send the request
        request.send(formData);

    }

</script>

<?php include $template . "footer.php"; ?>