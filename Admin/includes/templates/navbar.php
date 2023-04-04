<nav>
    <div class="container">
        <div class="navbar">
            <i class="fa-solid fa-bars menu"></i>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="members.php">Members</a></li>
                <li><a href="articles.php">Articles</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
            <a href="profile.php"><?=$_SESSION["username"]?></a>
        </div>
    </div>
</nav>