<?php
    session_start();
    
    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'ADMIN') {
        header('location:../index.php');
        exit();
    }
    
    require_once('../connexion.php');
    
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    
    $requeteStagiaire = "SELECT * FROM stagiaire WHERE id = ?";
    $params = array($id);
    $resultatStagiaire = $pdo->prepare($requeteStagiaire);
    $resultatStagiaire->execute($params);
    $stagiaire = $resultatStagiaire->fetch();
    
    $requeteFiliere = "SELECT * FROM filiere";
    $resultatFiliere = $pdo->query($requeteFiliere);
    
    $message = "";
    $erreur = "";
    
    if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['civilite']) && isset($_POST['idFiliere'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $civilite = $_POST['civilite'];
        $idFiliere = $_POST['idFiliere'];
        $nomPhoto = $stagiaire['photo'];
        
        if(!empty($_FILES['photo']['name'])) {
            $nomPhoto = "img" . rand() . "." . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $uploadFile = "../images/" . $nomPhoto;
            
            if(move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                $message = "Photo téléchargée avec succès";
            } else {
                $erreur = "Erreur lors du téléchargement de la photo";
            }
        }
        
        $requete = "UPDATE stagiaire SET nom = ?, prenom = ?, civilite = ?, photo = ?, idFiliere = ? WHERE id = ?";
        $params = array($nom, $prenom, $civilite, $nomPhoto, $idFiliere, $id);
        
        $resultat = $pdo->prepare($requete);
        $resultat->execute($params);
        
        header('location:stagiaires.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Éditer stagiaire</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/monstyle.css">
    <link rel="stylesheet" href="../css/style-moderne.css">
</head>
<body>
    <?php include("menu.php"); ?>
    
    <div class="container">
        <div class="panel panel-primary margetop">
            <div class="panel-heading">Éditer stagiaire</div>
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
                                <label for="nom" class="required">Nom</label>
                                <input type="text" name="nom" id="nom" class="form-control" value="<?php echo $stagiaire['nom']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="prenom" class="required">Prénom</label>
                                <input type="text" name="prenom" id="prenom" class="form-control" value="<?php echo $stagiaire['prenom']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="required">Civilité</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="civilite" value="F" <?php if($stagiaire['civilite'] == 'F') echo 'checked'; ?>> Féminin
                                    </label>
                                    <label style="margin-left: 20px;">
                                        <input type="radio" name="civilite" value="M" <?php if($stagiaire['civilite'] == 'M') echo 'checked'; ?>> Masculin
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="idFiliere" class="required">Filière</label>
                                <select name="idFiliere" id="idFiliere" class="form-control" required>
                                    <?php while($filiere = $resultatFiliere->fetch()) { ?>
                                        <option value="<?php echo $filiere['idFiliere']; ?>" <?php if($filiere['idFiliere'] == $stagiaire['idFiliere']) echo 'selected'; ?>>
                                            <?php echo $filiere['nomFiliere']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="photo">Photo</label>
                                <input type="file" name="photo" id="photo" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label>Photo actuelle</label>
                                <div>
                                    <img src="../images/<?php echo $stagiaire['photo'] ? $stagiaire['photo'] : 'profile.png'; ?>" 
                                         width="100" height="100" class="img-circle">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="glyphicon glyphicon-save"></i> Enregistrer
                    </button>
                    <a href="stagiaires.php" class="btn btn-default">
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
