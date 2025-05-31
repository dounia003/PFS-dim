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
    
    $nomPrenom = isset($_GET['nomPrenom']) ? $_GET['nomPrenom'] : "";
    $idfiliere = isset($_GET['idfiliere']) ? $_GET['idfiliere'] : 0;
    
    // Construction de la requête SQL avec filtres
    $requeteFiliere = "SELECT * FROM filiere";
    $resultatFiliere = $pdo->query($requeteFiliere);
    
    $condition = "";
    
    if($nomPrenom != "") {
        $condition .= " AND (nom LIKE '%$nomPrenom%' OR prenom LIKE '%$nomPrenom%')";
    }
    
    if($idfiliere != 0) {
        $condition .= " AND idFiliere = $idfiliere";
    }
    
    $requeteCount = "SELECT COUNT(*) countS FROM stagiaire WHERE 1=1 $condition";
    $resultatCount = $pdo->query($requeteCount);
    $tabCount = $resultatCount->fetch();
    $nbrStagiaires = $tabCount['countS'];
    
    $reste = $nbrStagiaires % $size;
    
    if($reste === 0) {
        $nbrPages = $nbrStagiaires / $size;
    } else {
        $nbrPages = floor($nbrStagiaires / $size) + 1;
    }
    
    $requete = "SELECT s.*, f.nomFiliere 
                FROM stagiaire s
                INNER JOIN filiere f ON s.idFiliere = f.idFiliere
                WHERE 1=1 $condition
                ORDER BY s.id
                LIMIT $size
                OFFSET $offset";
    
    $resultat = $pdo->query($requete);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gestion des stagiaires</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/monstyle.css">
    <link rel="stylesheet" href="../css/style-moderne.css">
</head>
<body>
    <?php include("menu.php"); ?>
    
    <div class="container">
        <div class="panel panel-success margetop">
            <div class="panel-heading">Rechercher des stagiaires</div>
            <div class="panel-body">
                <form method="get" action="stagiaires.php" class="form-inline">
                    <div class="form-group">
                        <input type="text" name="nomPrenom" placeholder="Nom ou prénom" class="form-control" value="<?php echo $nomPrenom; ?>">
                    </div>
                    <div class="form-group">
                        <select name="idfiliere" class="form-control">
                            <option value="0">Toutes les filières</option>
                            <?php while($filiere = $resultatFiliere->fetch()) { ?>
                                <option value="<?php echo $filiere['idFiliere']; ?>" <?php if($filiere['idFiliere'] == $idfiliere) echo "selected"; ?>>
                                    <?php echo $filiere['nomFiliere']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="glyphicon glyphicon-search"></i> Rechercher
                    </button>
                    &nbsp;&nbsp;
                    <?php if($_SESSION['user']['role'] == 'ADMIN') { ?>
                        <a href="nouveauStagiaire.php" class="btn btn-primary">
                            <i class="glyphicon glyphicon-plus"></i> Nouveau stagiaire
                        </a>
                    <?php } ?>
                </form>
            </div>
        </div>
        
        <div class="panel panel-primary">
            <div class="panel-heading">Liste des stagiaires (<?php echo $nbrStagiaires; ?> stagiaires)</div>
            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Filière</th>
                            <?php if($_SESSION['user']['role'] == 'ADMIN') { ?>
                                <th>Actions</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($stagiaire = $resultat->fetch()) { ?>
                            <tr>
                                <td>
                                    <img src="../images/<?php echo $stagiaire['photo'] ? $stagiaire['photo'] : 'profile.png'; ?>" 
                                         width="50" height="50" class="img-circle">
                                </td>
                                <td><?php echo $stagiaire['id']; ?></td>
                                <td><?php echo $stagiaire['nom']; ?></td>
                                <td><?php echo $stagiaire['prenom']; ?></td>
                                <td><?php echo $stagiaire['nomFiliere']; ?></td>
                                <?php if($_SESSION['user']['role'] == 'ADMIN') { ?>
                                    <td>
                                        <a href="editerStagiaire.php?id=<?php echo $stagiaire['id']; ?>" class="btn btn-info btn-sm">
                                            <i class="glyphicon glyphicon-edit"></i> Éditer
                                        </a>
                                        <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce stagiaire?')" 
                                           href="supprimerStagiaire.php?id=<?php echo $stagiaire['id']; ?>" 
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
                                <a href="stagiaires.php?page=<?php echo $i; ?>&nomPrenom=<?php echo $nomPrenom; ?>&idfiliere=<?php echo $idfiliere; ?>">
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
