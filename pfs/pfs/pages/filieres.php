<?php
    session_start();
    
    if(!isset($_SESSION['user'])) {
        header('location:../index.php');
        exit();
    }
    
    require_once('../connexion.php');
    
    // Récupération des paramètres de pagination et de recherche
    $size = isset($_GET['size']) ? $_GET['size'] : 5;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $size;
    
    $nomFiliere = isset($_GET['nomFiliere']) ? $_GET['nomFiliere'] : "";
    $niveau = isset($_GET['niveau']) ? $_GET['niveau'] : "all";
    
    // Construction de la requête SQL avec filtres
    $condition = "";
    
    if($nomFiliere != "") {
        $condition .= " AND nomFiliere LIKE '%$nomFiliere%'";
    }
    
    if($niveau != "all") {
        $condition .= " AND niveau = '$niveau'";
    }
    
    $requeteCount = "SELECT COUNT(*) countF FROM filiere WHERE 1=1 $condition";
    $resultatCount = $pdo->query($requeteCount);
    $tabCount = $resultatCount->fetch();
    $nbrFilieres = $tabCount['countF'];
    
    $reste = $nbrFilieres % $size;
    
    if($reste === 0) {
        $nbrPages = $nbrFilieres / $size;
    } else {
        $nbrPages = floor($nbrFilieres / $size) + 1;
    }
    
    $requete = "SELECT * FROM filiere WHERE 1=1 $condition LIMIT $size OFFSET $offset";
    $resultat = $pdo->query($requete);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gestion des filières</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/monstyle.css">
    <link rel="stylesheet" href="../css/style-moderne.css">
</head>
<body>
    <?php include("menu.php"); ?>
    
    <div class="container">
        <div class="panel panel-success margetop">
            <div class="panel-heading">Rechercher des filières</div>
            <div class="panel-body">
                <form method="get" action="filieres.php" class="form-inline">
                    <div class="form-group">
                        <input type="text" name="nomFiliere" placeholder="Nom de la filière" class="form-control" value="<?php echo $nomFiliere; ?>">
                    </div>
                    <div class="form-group">
                        <select name="niveau" class="form-control">
                            <option value="all" <?php if($niveau == "all") echo "selected"; ?>>Tous les niveaux</option>
                            <option value="Technicien" <?php if($niveau == "Technicien") echo "selected"; ?>>Technicien</option>
                            <option value="Technicien Spécialisé" <?php if($niveau == "Technicien Spécialisé") echo "selected"; ?>>Technicien Spécialisé</option>
                            <option value="Qualification" <?php if($niveau == "Qualification") echo "selected"; ?>>Qualification</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="glyphicon glyphicon-search"></i> Rechercher
                    </button>
                    &nbsp;&nbsp;
                    <?php if($_SESSION['user']['role'] == 'ADMIN') { ?>
                        <a href="nouvelleFiliere.php" class="btn btn-primary">
                            <i class="glyphicon glyphicon-plus"></i> Nouvelle filière
                        </a>
                    <?php } ?>
                </form>
            </div>
        </div>
        
        <div class="panel panel-primary">
            <div class="panel-heading">Liste des filières (<?php echo $nbrFilieres; ?> filières)</div>
            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom de la filière</th>
                            <th>Niveau</th>
                            <?php if($_SESSION['user']['role'] == 'ADMIN') { ?>
                                <th>Actions</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($filiere = $resultat->fetch()) { ?>
                            <tr>
                                <td><?php echo $filiere['idFiliere']; ?></td>
                                <td><?php echo $filiere['nomFiliere']; ?></td>
                                <td><?php echo $filiere['niveau']; ?></td>
                                <?php if($_SESSION['user']['role'] == 'ADMIN') { ?>
                                    <td>
                                        <a href="editerFiliere.php?idF=<?php echo $filiere['idFiliere']; ?>" class="btn btn-info btn-sm">
                                            <i class="glyphicon glyphicon-edit"></i> Éditer
                                        </a>
                                        <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette filière?')" 
                                           href="supprimerFiliere.php?idF=<?php echo $filiere['idFiliere']; ?>" 
                                           class="btn btn-danger btn-sm">
                                            <i class="glyphicon glyphicon-trash"></i> Supprimer
                                        </a>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                
                <div class="text-center">
                    <ul class="pagination">
                        <?php for($i = 1; $i <= $nbrPages; $i++) { ?>
                            <li class="<?php if($i == $page) echo 'active'; ?>">
                                <a href="filieres.php?page=<?php echo $i; ?>&nomFiliere=<?php echo $nomFiliere; ?>&niveau=<?php echo $niveau; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
