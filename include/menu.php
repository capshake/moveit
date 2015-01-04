<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">moveit</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>

                <?php
                if ($userData->isloggedIn()) {
                    echo '<li><a href="' . BASEDIR . 'pages/user/user.php?id=' . $userData->getUserId() . '">' . $userData->getFirstName() . ' ' . $userData->getLastName() . '</a></li>';
                    echo '<li><a href="' . BASEDIR . 'include/logout.php">logout</a></li>';
                    
                    if($userData->isAdmin()) {
                        echo '<li><a href="#">Administration</a></li>';
                    }
                }
                ?>

            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>