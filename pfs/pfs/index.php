<?php
    session_start();
    if(isset($_SESSION['user'])) {
        header('location:pages/accueil.php');
        exit();
    }
    
    $message_erreur = "";
    
    if(isset($_POST['login']) && isset($_POST['pwd'])) {
        include("connexion.php");
        $login = $_POST['login'];
        $pwd = $_POST['pwd'];
        
        // Approche simplifiée pour l'authentification
        $requete = "SELECT * FROM utilisateur WHERE login=?";
        $stmt = $pdo->prepare($requete);
        $stmt->execute([$login]);
        $user = $stmt->fetch();
        
        if($user) {
            // Vérifier si le mot de passe correspond
            $pwd_hash = md5($pwd);
            if($user['pwd'] === $pwd_hash) {
                // Vérifier si le compte est actif
                if($user['etat'] == 1) {
                    $_SESSION['user'] = $user;
                    header('location:pages/accueil.php');
                    exit();
                } else {
                    $message_erreur = "Votre compte est désactivé. Contactez l'administrateur.";
                }
            } else {
                $message_erreur = "Mot de passe incorrect";
                // Pour le débogage (à commenter en production)
                error_log("Mot de passe fourni (haché): " . $pwd_hash);
                error_log("Mot de passe en base: " . $user['pwd']);
            }
        } else {
            $message_erreur = "Login inconnu";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gestion des stagiaires</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/monstyle.css">
    <link rel="stylesheet" href="css/style-moderne.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-primary login-box">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center">Connexion</h3>
                    </div>
                    <div class="panel-body">
                        <form method="post" action="">
                            <?php if(!empty($message_erreur)) { ?>
                                <div class="alert alert-danger">
                                    <strong>Erreur!</strong> <?php echo $message_erreur; ?>
                                </div>
                            <?php } ?>
                            
                            <div class="form-group">
                                <label for="login">Login :</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <input type="text" name="login" id="login" class="form-control" placeholder="Entrez votre login" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="pwd">Mot de passe :</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="password" name="pwd" id="pwd" class="form-control" placeholder="Entrez votre mot de passe" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="glyphicon glyphicon-log-in"></i> Se connecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
