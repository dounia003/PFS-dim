<?php
    session_start();
    
    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'ADMIN') {
        header('location:../index.php');
        exit();
    }
    
    require_once('../connexion.php');
    
    $idUser = isset($_GET['idUser']) ? $_GET['idUser'] : 0;
    $etat = isset($_GET['etat']) ? $_GET['etat'] : 0;
    
    // Empêcher la désactivation de son propre compte
    if($idUser == $_SESSION['user']['iduser'] && $etat == 0) {
        $_SESSION['erreurUser'] = "Vous ne pouvez pas désactiver votre propre compte.";
    } else {
        $requete = "UPDATE utilisateur SET etat = ? WHERE iduser = ?";
        $params = array($etat, $idUser);
        
        $resultat = $pdo->prepare($requete);
        $resultat->execute($params);
    }
    
    header('location:utilisateurs.php');
    exit();
?>
