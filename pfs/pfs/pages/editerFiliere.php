<?php
    session_start();
    
    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'ADMIN') {
        header('location:../index.php');
        exit();
    }
    
    require_once('../connexion.php');
    
    $idF = isset($_GET['idF']) ? $_GET['idF'] : 0;
    
    $requeteFiliere = "SELECT * FROM filiere WHERE idFiliere = ?";
    $params = array($idF);
    $resultatFiliere = $pdo->prepare($requeteFiliere);
    $resultatFiliere->execute($params);
    $filiere = $resultatFiliere->fetch();
    
    if(isset($_POST['nomFiliere']) && isset($_POST['niveau'])) {
        $nomFiliere = $_POST['nomFiliere'];
        $niveau = $_POST['niveau'];
        
        $requete = "UPDATE filiere SET nomFiliere = ?, niveau = ? WHERE idFiliere = ?";
        $params = array($nomFiliere, $niveau, $idF);
        
        $resultat = $pdo->prepare($requete);
        $resultat->execute($params);
        
        header('location:filieres.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Éditer filière</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/monstyle.css">
    <link rel="stylesheet" href="../css/style-moderne.css">
</head>
<body>
    <?php include("menu.php"); ?>
    
    <div class="container">
        <div class="panel panel-primary margetop">
            <div class="panel-heading">Éditer filière</div>
            <div class="panel-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="nomFiliere" class="required">Nom de la filière</label>
                        <input type="text" name="nomFiliere" id="nomFiliere" class="form-control" value="<?php echo $filiere['nomFiliere']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="niveau" class="required">Niveau</label>
                        <select name="niveau" id="niveau" class="form-control" required>
                            <option value="Qualification" <?php if($filiere['niveau'] == 'Qualification') echo 'selected'; ?>>Qualification</option>
                            <option value="Technicien" <?php if($filiere['niveau'] == 'Technicien') echo 'selected'; ?>>Technicien</option>
                            <option value="Technicien Spécialisé" <?php if($filiere['niveau'] == 'Technicien Spécialisé') echo 'selected'; ?>>Technicien Spécialisé</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="glyphicon glyphicon-save"></i> Enregistrer
                    </button>
                    <a href="filieres.php" class="btn btn-default">
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
