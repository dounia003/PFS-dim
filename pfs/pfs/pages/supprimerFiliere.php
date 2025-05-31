<?php
    session_start();
    
    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'ADMIN') {
        header('location:../index.php');
        exit();
    }
    
    require_once('../connexion.php');
    
    $idF = isset($_GET['idF']) ? $_GET['idF'] : 0;
    
    // Vérifier si la filière est utilisée par des stagiaires
    $requeteCheck = "SELECT COUNT(*) as count FROM stagiaire WHERE idFiliere = ?";
    $stmtCheck = $pdo->prepare($requeteCheck);
    $stmtCheck->execute([$idF]);
    $result = $stmtCheck->fetch();
    
    if($result['count'] > 0) {
        $_SESSION['erreurFiliere'] = "Impossible de supprimer cette filière car elle est utilisée par des stagiaires.";
    } else {
        $requete = "DELETE FROM filiere WHERE idFiliere = ?";
        $params = array($idF);
        
        $resultat = $pdo->prepare($requete);
        $resultat->execute($params);
    }
    
    header('location:filieres.php');
    exit();
?>
