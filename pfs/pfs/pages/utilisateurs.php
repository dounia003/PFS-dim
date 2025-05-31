<?php
    session_start();
    
    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'ADMIN') {
        header('location:../index.php');
        exit();
    }
    
    require_once('../connexion.php');
    
    // Récupération des paramètres de pagination et de recherche
    $size = isset($_GET['size']) ? $_GET['size'] : 5;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $size;
    
    $login = isset($_GET['login']) ? $_GET['login'] : "";
    $role = isset($_GET['role']) ? $_GET['role'] : "all";
    $etat = isset($_GET['etat']) ? $_GET['etat'] : "all";
    
    // Construction de la requête SQL avec filtres
    $condition = "";
    
    if($login != "") {
        $condition .= " AND login LIKE '%$login%'";
    }
    
    if($role != "all") {
        $condition .= " AND role = '$role'";
    }
    
    if($etat != "all") {
        $condition .= " AND etat = $etat";
    }
    
    $requeteCount = "SELECT COUNT(*) countU FROM utilisateur WHERE 1=1 $condition";
    $resultatCount = $pdo->query($requeteCount);
    $tabCount = $resultatCount->fetch();
    $nbrUtilisateurs = $tabCount['countU'];
    
    $reste = $nbrUtilisateurs % $size;
    
    if($reste === 0) {
        $nbrPages = $nbrUtilisateurs / $size;
    } else {
        $nbrPages = floor($nbrUtilisateurs / $size) + 1;
    }
    
    $requete = "SELECT * FROM utilisateur WHERE 1=1 $condition LIMIT $size OFFSET $offset";
    $resultat = $pdo->query($requete);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/monstyle.css">
    <link rel="stylesheet" href="../css/style-moderne.css">
</head>
<body>
    <?php include("menu.php"); ?>
    
    <div class="container">
        <div class="panel panel-success margetop">
            <div class="panel-heading">Rechercher des utilisateurs</div>
            <div class="panel-body">
                <form method="get" action="utilisateurs.php" class="form-inline">
                    <div class="form-group">
                        <input type="text" name="login" placeholder="Login" class="form-control" value="<?php echo $login; ?>">
                    </div>
                    <div class="form-group">
                        <select name="role" class="form-control">
                            <option value="all" <?php if($role == "all") echo "selected"; ?>>Tous les rôles</option>
                            <option value="ADMIN" <?php if($role == "ADMIN") echo "selected"; ?>>Administrateur</option>
                            <option value="VISITEUR" <?php if($role == "VISITEUR") echo "selected"; ?>>Visiteur</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="etat" class="form-control">
                            <option value="all" <?php if($etat == "all") echo "selected"; ?>>Tous les états</option>
                            <option value="1" <?php if($etat == "1") echo "selected"; ?>>Activé</option>
                            <option value="0" <?php if($etat == "0") echo "selected"; ?>>Désactivé</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="glyphicon glyphicon-search"></i> Rechercher
                    </button>
                    &nbsp;&nbsp;
                    <a href="nouvelUtilisateur.php" class="btn btn-primary">
                        <i class="glyphicon glyphicon-plus"></i> Nouvel utilisateur
                    </a>
                </form>
            </div>
        </div>
        
        <div class="panel panel-primary">
            <div class="panel-heading">Liste des utilisateurs (<?php echo $nbrUtilisateurs; ?> utilisateurs)</div>
            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Login</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>État</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($utilisateur = $resultat->fetch()) { ?>
                            <tr class="<?php echo $utilisateur['etat'] == 1 ? 'success' : 'danger'; ?>">
                                <td>
                                    <img src="../images/<?php echo $utilisateur['avatar'] ? $utilisateur['avatar'] : 'profile.png'; ?>" 
                                         width="50" height="50" class="img-circle">
                                </td>
                                <td><?php echo $utilisateur['login']; ?></td>
                                <td><?php echo $utilisateur['email']; ?></td>
                                <td><?php echo $utilisateur['role']; ?></td>
                                <td>
                                    <span class="badge <?php echo $utilisateur['etat'] == 1 ? 'badge-success' : 'badge-danger'; ?>">
                                        <?php echo $utilisateur['etat'] == 1 ? 'Activé' : 'Désactivé'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="editerUtilisateur.php?idUser=<?php echo $utilisateur['iduser']; ?>" class="btn btn-info btn-sm">
                                        <i class="glyphicon glyphicon-edit"></i> Éditer
                                    </a>
                                    <?php if($utilisateur['etat'] == 1) { ?>
                                        <a href="activerUtilisateur.php?idUser=<?php echo $utilisateur['iduser']; ?>&etat=0" class="btn btn-warning btn-sm">
                                            <i class="glyphicon glyphicon-remove"></i> Désactiver
                                        </a>
                                    <?php } else { ?>
                                        <a href="activerUtilisateur.php?idUser=<?php echo $utilisateur['iduser']; ?>&etat=1" class="btn btn-success btn-sm">
                                            <i class="glyphicon glyphicon-ok"></i> Activer
                                        </a>
                                    <?php } ?>
                                    <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')" 
                                       href="supprimerUtilisateur.php?idUser=<?php echo $utilisateur['iduser']; ?>" 
                                       class="btn btn-danger btn-sm">
                                        <i class="glyphicon glyphicon-trash"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                
                <div class="text-center">
                    <ul class="pagination">
                        <?php for($i = 1; $i <= $nbrPages; $i++) { ?>
                            <li class="<?php if($i == $page) echo 'active'; ?>">
                                <a href="utilisateurs.php?page=<?php echo $i; ?>&login=<?php echo $login; ?>&role=<?php echo $role; ?>&etat=<?php echo $etat; ?>">
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
