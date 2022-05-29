<?php

echo "
    <header>
        <nav>
            <div id='l_nav'>
                <h1>GIF It Up!</h1>
                <a href='./'>Home<span class='material-menu material-symbols-outlined'>home</span></a>
                <a href='profile.php'>" . $_SESSION['username'] . "<span class='material-menu material-symbols-outlined'>person</span></a>
                <a href='search.php'>Search<span class='material-menu material-symbols-outlined'>search</span></a>
                <a href='logout.php'>Logout<span class='material-menu material-symbols-outlined'>logout</span></a>
                <span id='mmmnksss'>1000001680
            </div>

            <div id='r_nav'>
                <a id='create_btn' href='create.php'>Post something<span id='material-add' class='material-symbols-outlined'>add</span></a>
            </div>
        </nav>
    </header>
";
