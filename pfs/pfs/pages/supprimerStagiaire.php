<?php
    session_start();
    
    if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'ADMIN') {
        header('location:../index.php');
        exit();
    }
    
    require_once('../connexion.php');
    
    $id = isset($_GET['id']) ? $_GET['id'] : 0;
    
    $requete = "DELETE FROM stagiaire WHERE id = ?";
    $params = array($id);
    
    $resultat = $pdo->prepare($requete);
    $resultat->execute($params);
    
    header('location:stagiaires.php');
    exit();
?>
