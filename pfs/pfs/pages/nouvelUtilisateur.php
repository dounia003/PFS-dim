<?php
    session_start();
    
    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'ADMIN') {
        header('location:../index.php');
        exit();
    }
    
    require_once('../connexion.php');
    
    $message = "";
    $erreur = "";
    
    if(isset($_POST['login']) && isset($_POST['email']) && isset($_POST['pwd']) && isset($_POST['role'])) {
        $login = $_POST['login'];
        $email = $_POST['email'];
        $pwd = $_POST['pwd'];
        $role = $_POST['role'];
        $avatar = "";
        
        // Vérifier si le login existe déjà
        $requeteCheck = "SELECT COUNT(*) as count FROM utilisateur WHERE login = ?";
        $stmtCheck = $pdo->prepare($requeteCheck);
        $stmtCheck->execute([$login]);
        $result = $stmtCheck->fetch();
        
        if($result['count'] > 0) {
            $erreur = "Ce login existe déjà. Veuillez en choisir un autre.";
        } else {
            if(!empty($_FILES['avatar']['name'])) {
                $avatar = "avatar" . rand() . "." . pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $uploadFile = "../images/" . $avatar;
                
                if(move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile)) {
                    $message = "Avatar téléchargé avec succès";
                } else {
                    $erreur = "Erreur lors du téléchargement de l'avatar";
                    $avatar = "";
                }
            } else {
                // Attribuer une image par défaut
                $avatar = "img" . rand(1, 8) . ".jpg";
            }
            
            $requete = "INSERT INTO utilisateur(login, email, pwd, role, avatar, etat) VALUES(?, ?, MD5(?), ?, ?, 1)";
            $params = array($login, $email, $pwd, $role, $avatar);
            
            $resultat = $pdo->prepare($requete);
            $resultat->execute($params);
            
            header('location:utilisateurs.php');
            exit();
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nouvel utilisateur</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/monstyle.css">
    <link rel="stylesheet" href="../css/style-moderne.css">
</head>
<body>
    <?php include("menu.php"); ?>
    
    <div class="container">
        <div class="panel panel-primary margetop">
            <div class="panel-heading">Nouvel utilisateur</div>
            <div class="panel-body">
                <?php if(!empty($message)) { ?>
                    <div class="alert alert-success">
                        <?php echo $message; ?>
                    </div>
                <?php } ?>
                
                <?php if(!empty($erreur)) { ?>
                    <div class="alert alert-danger">
                        <?php echo $erreur; ?>
                    </div>
                <?php } ?>
                
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="login" class="required">Login</label>
                                <input type="text" name="login" id="login" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email" class="required">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="pwd" class="required">Mot de passe</label>
                                <input type="password" name="pwd" id="pwd" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role" class="required">Rôle</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="ADMIN">Administrateur</option>
                                    <option value="VISITEUR">Visiteur</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="avatar">Avatar</label>
                                <input type="file" name="avatar" id="avatar" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <p class="help-block">
                                    Si vous ne téléchargez pas d'avatar, une image par défaut sera attribuée.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="glyphicon glyphicon-save"></i> Enregistrer
                    </button>
                    <a href="utilisateurs.php" class="btn btn-default">
                        <i class="glyphicon glyphicon-arrow-left"></i> Retour
                    </a>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
