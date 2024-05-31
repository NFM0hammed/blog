<?php

    session_start();
    
    if(!isset($_SESSION["username"])) {

        header("Location: index.php");

        exit();

    } else {

        $title = "Articles";

        include "init.php";

        include $template . "navbar.php";

        $action = isset($_GET["action"]) ? $_GET["action"] : "default";

        if($action === "default") {

            ?>

                <div class="container">
                    <h1 class="title">Articles</h1>
                    <div class="manage">
                        <a href="?action=add">Add article</a>
                        <a href="?action=manage">Manage article</a>
                    </div>
                </div>

            <?php

        } elseif($action === "add") {

            ?>

                <div class="container">
                    <h1 class="title">Add article</h1>
                    <form
                            class   =   "manage-article"
                            action  =   "?action=insert"
                            method  =   "POST"
                            enctype =   multipart/form-data
                    >
                        <input
                                type        =   "text"
                                name        =   "article_title"
                                placeholder =   "عنوان المقال"
                        />
                        <input
                                type        =   "file"
                                name        =   "article_img"
                                id          =   "article_img"
                        />
                        <label for="article_img">
                            <img
                                class       =   "article-img"
                                src         =   "uploads\defualt_article_image.png"
                                alt         =   ""
                            >
                        </label>
                        <textarea name="article_content" placeholder="محتوى المقال"></textarea>
                        <span>تصنيف المقال</span>
                        <select name="article_category">
                            <option value="1">IT</option>
                            <option value="2">Programming</option>
                            <option value="3">Cyper</option>
                            <option value="4">Networks</option>
                        </select>
                        <input 
                                type        =   "submit"
                                value       =   "Add"
                        >
                    </form>
                </div>

            <?php

        } elseif($action === "manage") {

            $rows = selectData(
                "*",
                "articles",
                "INNER JOIN users ON articles.user_id = users.user_id INNER JOIN categories ON articles.category_id = categories.category_id",
                "",
                ""
                // "ORDER BY article_id DESC"
            );

            $count = count($rows);

            if($count > 0) {
                
                ?>

                    <div class="container">
                        <h1 class="title">Manage article</h1>
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Manage</th>
                            </tr>
                            
                                <?php

                                    foreach($rows as $row) {

                                        ?>

                                            <tr>
                                                <td><?=$row["article_id"]?></td>
                                                <td><?=$row["username"]?></td>
                                                <td class="dir"><?=$row["article_title"]?></td>
                                                <td><?=$row["category_name"]?></td>
                                                <td>
                                                    <a
                                                        class   =   "edit"
                                                        href    =   "?action=edit&id=<?=$row["article_id"]?>">Edit</a>
                                                    <a
                                                        class   =   "delete"
                                                        href    =   "?action=delete&id=<?=$row["article_id"]?>">Delete</a>
                                                </td>
                                            </tr>

                                        <?php

                                    }

                                ?>

                        </table>
                    </div>

                <?php

            } else {

                show_msg(
                    "There're no articles data !",
                    "alert"
                );

            }

        } elseif($action === "edit") {

            $articleid = isset($_GET["id"]) && is_numeric($_GET["id"]) ? intval($_GET["id"]) : 0;

            $check = checkData(
                "articles",
                "article_id",
                $articleid
            );

            if($check > 0) {

                $row = selectSpecificData(
                    "*",
                    "articles",
                    "WHERE article_id = ?",
                    $articleid
                );

                ?>

                    <div class="container">
                        <h1 class="title">Edit article</h1>
                        <form
                                class   =   "manage-article"
                                action  =   "?action=update"
                                method  =   "POST"
                                enctype =   multipart/form-data
                        >
                            <input
                                    type    =   "text"
                                    name    =   "article_title"
                                    value   =   "<?=$row["article_title"]?>"
                            />
                            <input
                                    type    =   "file"
                                    name    =   "article_img"
                                    id      =   "article_img"
                            />
                            <label for="article_img">
                                <img
                                    class   =   "article-img"
                                    src     =   "uploads\<?=$row["article_img"]?>"
                                    alt     =   ""
                                >
                            </label>
                            <textarea name="article_content"><?=$row["article_content"]?></textarea>
                            <span>تصنيف المقال</span>
                            <select name="article_category">
                                <option value="1" <?php if($row["category_id"] == 1) echo "selected" ?>>IT</option>
                                <option value="2" <?php if($row["category_id"] == 2) echo "selected" ?>>Programming</option>
                                <option value="3" <?php if($row["category_id"] == 3) echo "selected" ?>>Cyper</option>
                                <option value="4" <?php if($row["category_id"] == 4) echo "selected" ?>>Networks</option>
                            </select>
                            <input
                                    type    =   "hidden"
                                    name    =   "article_id"
                                    value   =   "<?=$articleid?>"
                            >
                            <input
                                    type    =   "submit"
                                    value   =   "Update"
                            >
                        </form>
                    </div>

                <?php

            } else {

                show_msg(
                    "There's no ID as you wrote !",
                    "error"
                );

                redirectPage();

            }

        } elseif($action === "insert") {

            if($_SERVER["REQUEST_METHOD"] === "POST") {

                $userid          = $_SESSION["userid"];
                $articleTitle    = $_POST["article_title"];
                $articleImg      = $_FILES["article_img"];
                $articleContent  = $_POST["article_content"];
                $articleCategory = $_POST["article_category"];

                // Get info of img
                $imgName = $articleImg["name"];
                $imgSize = $articleImg["size"];
                $imgTmp  = $articleImg["tmp_name"];

                // Array to add allowed of extensions
                $extensions = array("jpg", "jpeg", "png");

                // Max size of img [1,000,000 Bytes]
                $maxSize = 1000000;

                // Get the extension of img
                $extensionImg = strtolower(end(explode(".", $imgName)));

                $errors = array();

                if(empty($articleTitle)) {

                    $errors[] = "The article title is empty !";

                }

                if(empty($imgName)) {

                    $errors[] = "The article image is empty !";

                } else {

                    // Check the extension of image
                    if(!in_array($extensionImg, $extensions)) {

                        $errors[] = "The extension of image isn't available !";

                    }

                    // Check the size of image
                    if($imgSize > $maxSize) {

                        $errors[] = "The size of image is larger than 1,000,000 bytes !";

                    }

                }

                if(empty($articleContent)) {

                    $errors[] = "The article content is empty !";

                }

                if(empty($errors)) {

                    // For no repeating the name of img
                    $rand = rand(0, 1000000000);

                    // Name of img after random number
                    $nameOfImg = $rand . "_" . $imgName;

                    // Add img to the uploads folder
                    move_uploaded_file($imgTmp, "uploads\\" . $nameOfImg);

                    $stmt = $conn->prepare(
                        "INSERT INTO
                            articles(user_id, article_title, article_content, article_img, date_publication, category_id)
                        VALUES
                            (:userid, :title, :content, :img, NOW(), :categoryid)"
                    );

                    $stmt->execute(array(
                        "userid"     => $userid,
                        "title"      => $articleTitle,
                        "content"    => $articleContent,
                        "img"        => $nameOfImg,
                        "categoryid" => $articleCategory
                    ));
                    
                    show_msg(
                        "Success insert",
                        "success"
                    );

                    redirectPage();

                } else {

                    ?>

                        <div class="container">
                            <div class="error">

                                <?php

                                    foreach($errors as $error) {

                                        echo $error . "</br></br>";

                                    }

                                ?>

                            </div>
                        </div>

                    <?php
                    
                }

            } else {

                show_msg(
                    "You can't open this page directly !",
                    "error"
                );

                redirectPage();

            }

        } elseif($action === "update") {

            if($_SERVER["REQUEST_METHOD"] === "POST") {

                $articleid       = $_POST["article_id"];
                $articleTitle    = $_POST["article_title"];
                $articleImg      = $_FILES["article_img"];
                $articleContent  = $_POST["article_content"];
                $articleCategory = $_POST["article_category"];

                // Get info of img
                $imgName = $articleImg["name"];
                $imgSize = $articleImg["size"];
                $imgTmp  = $articleImg["tmp_name"];

                // Array to add allowed of extensions
                $extensions = array("jpg", "jpeg", "png");

                // Max size of img [1,000,000 Bytes]
                $maxSize = 1000000;

                // Get the extension of img
                $extensionImg = strtolower(end(explode(".", $imgName)));

                $errors = array();

                $check = checkData("articles", "article_id", $articleid);

                if(empty($articleTitle)) {

                    $errors[] = "The article title is empty !";

                }

                if(empty($imgName)) {

                    $errors[] = "The article image is empty !";

                } else {

                    // Check the extension of image
                    if(!in_array($extensionImg, $extensions)) {

                        $errors[] = "The extension of image isn't available !";

                    }

                    // Check the size of image
                    if($imgSize > $maxSize) {

                        $errors[] = "The size of image is larger than 1,000,000 bytes !";

                    }

                }

                if(empty($articleContent)) {

                    $errors[] = "The article content is empty !";

                }

                if(empty($errors)){

                    if($check > 0) {

                        // For no repeating the name of img
                        $rand = rand(0, 1000000000);

                        // Name of img after random number
                        $nameOfImg = $rand . "_" . $imgName;

                        // Add img to the uploads folder
                        move_uploaded_file($imgTmp, "uploads\\" . $nameOfImg);

                        $stmt = $conn->prepare(
                            "UPDATE
                                articles
                            SET
                                article_title   = ?,
                                article_content = ?,
                                article_img     = ?,
                                category_id     = ?
                            WHERE
                                article_id      = ?"
                        );

                        $stmt->execute(array(
                            $articleTitle,
                            $articleContent,
                            $nameOfImg,
                            $articleCategory,
                            $articleid
                        ));

                        show_msg(
                            "Update is done !",
                            "success"
                        );

                        redirectPage();

                    }

                } else {

                    ?>

                         <div class="container">
                            <div class="error">

                                <?php

                                    foreach($errors as $error) {

                                        echo $error . "</br></br>";

                                    }

                                ?>

                            </div>
                        </div>

                    <?php

                }

            } else {

                show_msg(
                    "You can't open this page directly !",
                    "error"
                );

                redirectPage();

            }

        } elseif($action === "delete") {

            $articleid = isset($_GET["id"]) && is_numeric($_GET["id"]) ? intval($_GET["id"]) : 0;
            
            $check = checkData(
                "articles",
                "article_id",
                $articleid
            );

            if($check > 0) {

                deleteData(
                    "articles",
                    "article_id",
                    $articleid
                );
                
                show_msg(
                    "Delete is done !",
                    "success"
                );

                redirectPage();

            } else {

                show_msg(
                    "There's no ID as you wrote !",
                    "error"
                );

                redirectPage();

            }

        } else {

            show_msg(
                "This page doesn't exists !",
                "error"
            );

            redirectPage();

        }

    }

    include "includes/templates/footer.php";