<?php
    session_start();
    
    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'ADMIN') {
        header('location:../index.php');
        exit();
    }
    
    require_once('../connexion.php');
    
    $idUser = isset($_GET['idUser']) ? $_GET['idUser'] : 0;
    
    // Empêcher la suppression de son propre compte
    if($idUser == $_SESSION['user']['iduser']) {
        $_SESSION['erreurUser'] = "Vous ne pouvez pas supprimer votre propre compte.";
    } else {
        $requete = "DELETE FROM utilisateur WHERE iduser = ?";
        $params = array($idUser);
        
        $resultat = $pdo->prepare($requete);
        $resultat->execute($params);
    }
    
    header('location:utilisateurs.php');
    exit();
?>
