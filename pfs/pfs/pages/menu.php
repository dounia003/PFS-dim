<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="accueil.php">Gestion des Stagiaires</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li><a href="accueil.php"><i class="glyphicon glyphicon-home"></i> Accueil</a></li>
                <li><a href="stagiaires.php"><i class="glyphicon glyphicon-user"></i> Stagiaires</a></li>
                <li><a href="filieres.php"><i class="glyphicon glyphicon-education"></i> Filières</a></li>
                <?php if($_SESSION['user']['role'] == 'ADMIN') { ?>
                    <li><a href="utilisateurs.php"><i class="glyphicon glyphicon-lock"></i> Utilisateurs</a></li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="glyphicon glyphicon-user"></i> <?php echo $_SESSION['user']['login']; ?>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php"><i class="glyphicon glyphicon-cog"></i> Profil</a></li>
                        <li><a href="seDeconnecter.php"><i class="glyphicon glyphicon-log-out"></i> Se déconnecter</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
