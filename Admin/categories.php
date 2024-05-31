<?php

    session_start();

    if(!isset($_SESSION["username"])) {

        header("Location: index.php");

        exit();

    } else {

        $title = "Categories";

        include "init.php";

        include $template . "navbar.php";

        $action = isset($_GET["action"]) ? $_GET["action"] : "default";

        if($action === "default") {

            ?>

                <div class="container">
                    <h1 class="title">Categories</h1>
                    <div class="manage">
                        <a href="?action=add">Add category</a>
                        <a href="?action=manage">Manage category</a>
                    </div>
                </div>

            <?php

        } elseif($action === "add") {

            ?>

                <div class="container">
                    <h1 class="title">Add category</h1>
                    <form
                            class   =   "manage-category"
                            action  =   "?action=insert"
                            method  =   "POST"
                    >
                        <input
                                type        =   "text"
                                name        =   "category_name"
                                placeholder =   "إضافة صنف"
                        >
                        <input
                                type        =   "text"
                                name        =   "description"
                                placeholder =   "إضافة وصف"
                        >
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
                "categories"
            );

            $count = count($rows);

            if($count > 0) {
            
                ?>
                    <div class="container">
                        <h1 class="title">Manage category</h1>
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Manage</th>
                            </tr>

                                <?php

                                    foreach($rows as $row) {

                                        ?>

                                            <tr>
                                                <td><?=$row["category_id"]?></td>
                                                <td><?=$row["category_name"]?></td>
                                                <td><?=$row["description"]?></td>
                                                <td>
                                                    <a
                                                        class   =   "edit"
                                                        href    =   "?action=edit&id=<?=$row["category_id"]?>">Edit</a>
                                                    <a
                                                        class   =   "delete"
                                                        href    =   "?action=delete&id=<?=$row["category_id"]?>">Delete</a>
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
                    "There're no categories data !",
                    "alert"
                );

            }

        } elseif($action === "edit") {

            $categoryid = isset($_GET["id"]) && is_numeric($_GET["id"]) ? intval($_GET["id"]) : 0;

            $check = checkData(
                "categories",
                "category_id",
                $categoryid
            );

            if($check > 0) {

                $row = selectSpecificData(
                    "*",
                    "categories",
                    "WHERE category_id = ?",
                    $categoryid
                );

                    ?>

                        <div class="container">
                            <h1 class="title">Edit category</h1>
                            <form
                                    class   =   "manage-category"
                                    action  =   "?action=update"
                                    method  =   "POST"
                            >
                                <input
                                        type    =   "text"
                                        name    =   "category_name"
                                        value   =   "<?=$row["category_name"]?>"
                                >
                                <input
                                        type    =   "text"
                                        name    =   "description"
                                        value   =   "<?=$row["description"]?>"
                                >
                                <input
                                        type    =   "hidden"
                                        name    =   "category_id"
                                        value   =   "<?=$categoryid?>"
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

                $categoryName = $_POST["category_name"];
                $description  = $_POST["description"];

                $errors = array();

                if(empty($categoryName)) {

                    $errors[] = "The category name is empty !";

                }

                if(empty($description)) {

                    $errors[] = "The category description is empty !";

                }

                if(empty($errors)) {

                    $stmt = $conn->prepare(
                        "INSERT INTO
                            categories(category_name, description)
                        VALUES
                            (:catgname, :catgdesc)"
                    );

                    $stmt->execute(array(
                        "catgname" => $categoryName,
                        "catgdesc" => $description
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

                $categoryid   = $_POST["category_id"];
                $categoryName = $_POST["category_name"];
                $description  = $_POST["description"];

                $errors = array();

                $check = checkData("categories", "category_id", $categoryid);

                if(empty($categoryName)) {

                    $errors[] = "The category name is empty !";

                }

                if(empty($description)) {

                    $errors[] = "The category description is empty !";

                }

                if(empty($errors)){

                    if($check > 0) {

                        $stmt = $conn->prepare(
                            "UPDATE
                                categories
                            SET
                                category_name   = ?,
                                description     = ?
                            WHERE
                                category_id     = ?"
                        );

                        $stmt->execute(array(
                            $categoryName,
                            $description,
                            $categoryid
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

            $categoryid = isset($_GET["id"]) && is_numeric($_GET["id"]) ? intval($_GET["id"]) : 0;

            $check = checkData(
                "categories",
                "category_id",
                $categoryid
            );

            if($check > 0) {

                deleteData(
                    "categories",
                    "category_id",
                    $categoryid
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