<?php

    session_start();

    if(!isset($_SESSION["username"])) {

        header("Location: index.php");

        exit();

    } else {

        $title = "Comments";

        include "init.php";

        include $template . "navbar.php";

        $action = isset($_GET["action"]) ? $_GET["action"] : "default";

        if($action === "default") {

            $rows = selectData(
                "*",
                "comments",
                "INNER JOIN users ON comments.user_id = users.user_id INNER JOIN articles ON comments.article_id = articles.article_id",
                "",
                "",
                "ORDER BY comment_id DESC"
            );

            $count = count($rows);

            if($count > 0) {

                ?>

                    <div class="container">
                        <h1 class="title">Manage comments</h1>
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Comment</th>
                                <th>Comment date</th>
                                <th>Article</th>
                                <th>Manage</th>
                            </tr>

                                <?php

                                    foreach($rows as $row) {

                                        ?>

                                            <tr>
                                                <td><?=$row["comment_id"]?></td>
                                                <td><?=$row["username"]?></td>
                                                <td><?=$row["comment_content"]?></td>
                                                <td><?=$row["comment_date"]?></td>
                                                <td><?=$row["article_title"]?></td>
                                                <td>
                                                    <a
                                                        class   =   "delete"
                                                        href    =   "?action=delete&id=<?=$row["comment_id"]?>">Delete</a>
                                                </td>
                                            </tr>

                                        <?php

                                    }

                                ?>

                        </table>
                    </div>

                <?php

            }

        } elseif($action === "delete") {

            $commentId = isset($_GET["id"]) && is_numeric($_GET["id"]) ? intval($_GET["id"]) : 0;

            $check = checkData(
                "comments",
                "comment_id",
                $commentId
            );

            if($check > 0) {

                deleteData(
                    "comments",
                    "comment_id",
                    $commentId
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