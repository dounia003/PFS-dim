<?php
    session_start();
    
    if(!isset($_SESSION['user'])) {
        header('location:../index.php');
        exit();
    }
    
    require_once('../connexion.php');
    
    $idUser = $_SESSION['user']['iduser'];
    
    $requeteUser = "SELECT * FROM utilisateur WHERE iduser = ?";
    $params = array($idUser);
    $resultatUser = $pdo->prepare($requeteUser);
    $resultatUser->execute($params);
    $utilisateur = $resultatUser->fetch();
    
    $message = "";
    $erreur = "";
    
    if(isset($_POST['email'])) {
        $email = $_POST['email'];
        $avatar = $utilisateur['avatar'];
        
        if(!empty($_FILES['avatar']['name'])) {
            $avatar = "avatar" . rand() . "." . pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $uploadFile = "../images/" . $avatar;
            
            if(move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile)) {
                $message = "Avatar téléchargé avec succès";
            } else {
                $erreur = "Erreur lors du téléchargement de l'avatar";
            }
        }
        
        if(!empty($_POST['newPwd']) && !empty($_POST['oldPwd'])) {
            $oldPwd = $_POST['oldPwd'];
            $newPwd = $_POST['newPwd'];
            
            // Vérifier l'ancien mot de passe
            $requeteCheck = "SELECT COUNT(*) as count FROM utilisateur WHERE iduser = ? AND pwd = MD5(?)";
            $stmtCheck = $pdo->prepare($requeteCheck);
            $stmtCheck->execute([$idUser, $oldPwd]);
            $result = $stmtCheck->fetch();
            
            if($result['count'] > 0) {
                $requete = "UPDATE utilisateur SET email = ?, pwd = MD5(?), avatar = ? WHERE iduser = ?";
                $params = array($email, $newPwd, $avatar, $idUser);
            } else {
                $erreur = "L'ancien mot de passe est incorrect.";
                $requete = "UPDATE utilisateur SET email = ?, avatar = ? WHERE iduser = ?";
                $params = array($email, $avatar, $idUser);
            }
        } else {
            $requete = "UPDATE utilisateur SET email = ?, avatar = ? WHERE iduser = ?";
            $params = array($email, $avatar, $idUser);
        }
        
        $resultat = $pdo->prepare($requete);
        $resultat->execute($params);
        
        // Mettre à jour les informations de session
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['avatar'] = $avatar;
        
        if(empty($erreur)) {
            $message = "Profil mis à jour avec succès";
        }
        
        // Recharger les informations de l'utilisateur
        $resultatUser->execute($params);
        $utilisateur = $resultatUser->fetch();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mon profil</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/monstyle.css">
    <link rel="stylesheet" href="../css/style-moderne.css">
</head>
<body>
    <?php include("menu.php"); ?>
    
    <div class="container">
        <div class="panel panel-primary margetop">
            <div class="panel-heading">Mon profil</div>
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
                                <label for="login">Login</label>
                                <input type="text" name="login" id="login" class="form-control" value="<?php echo $utilisateur['login']; ?>" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="email" class="required">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo $utilisateur['email']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="role">Rôle</label>
                                <input type="text" name="role" id="role" class="form-control" value="<?php echo $utilisateur['role']; ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="oldPwd">Ancien mot de passe</label>
                                <input type="password" name="oldPwd" id="oldPwd" class="form-control" placeholder="Entrez votre ancien mot de passe">
                            </div>
                            
                            <div class="form-group">
                                <label for="newPwd">Nouveau mot de passe</label>
                                <input type="password" name="newPwd" id="newPwd" class="form-control" placeholder="Entrez votre nouveau mot de passe">
                            </div>
                            
                            <div class="form-group">
                                <label for="avatar">Avatar</label>
                                <input type="file" name="avatar" id="avatar" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label>Avatar actuel</label>
                                <div>
                                    <img src="../images/<?php echo $utilisateur['avatar'] ? $utilisateur['avatar'] : 'profile.png'; ?>" 
                                         width="100" height="100" class="img-circle">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="glyphicon glyphicon-save"></i> Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
