<?php
    session_start();
    
    if(!isset($_SESSION['user'])) {
        header('location:../index.php');
        exit();
    }
    
    require_once('../connexion.php');
    
    // Récupération des statistiques
    $requeteStagiaires = "SELECT COUNT(*) as nbStagiaires FROM stagiaire";
    $resultatStagiaires = $pdo->query($requeteStagiaires);
    $nbStagiaires = $resultatStagiaires->fetch()['nbStagiaires'];
    
    $requeteFilieres = "SELECT COUNT(*) as nbFilieres FROM filiere";
    $resultatFilieres = $pdo->query($requeteFilieres);
    $nbFilieres = $resultatFilieres->fetch()['nbFilieres'];
    
    $requeteUtilisateurs = "SELECT COUNT(*) as nbUtilisateurs FROM utilisateur";
    $resultatUtilisateurs = $pdo->query($requeteUtilisateurs);
    $nbUtilisateurs = $resultatUtilisateurs->fetch()['nbUtilisateurs'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gestion des stagiaires - Accueil</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/monstyle.css">
    <link rel="stylesheet" href="../css/style-moderne.css">
    <style>
        .feature-box {
            text-align: center;
            padding: 30px 20px;
            border-radius: var(--border-radius);
            background-color: white;
            box-shadow: var(--box-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            margin-bottom: 20px;
        }
        
        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            font-size: 48px;
            margin-bottom: 20px;
            color: var(--secondary-color);
        }
        
        .feature-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark-color);
        }
        
        .feature-description {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .welcome-section {
            background-color: white;
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
        }
        
        .welcome-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--dark-color);
        }
        
        .welcome-subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: var(--secondary-color);
            margin-top: 10px;
            display: block;
        }
        
        .logo-container {
            text-align: center;
            padding: 20px;
        }
        
        .logo {
            max-width: 150px;
            height: auto;
        }
        
        .action-btn {
            margin-right: 10px;
            margin-bottom: 10px;
        }
        
        @media (max-width: 768px) {
            .welcome-section {
                padding: 20px;
            }
            
            .welcome-title {
                font-size: 24px;
            }
            
            .welcome-subtitle {
                font-size: 16px;
            }
            
            .feature-box {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <?php include("menu.php"); ?>
    
    <div class="container">
        <div class="row welcome-section margetop">
            <div class="col-md-8">
                <h1 class="welcome-title">Gestion de Stagiaires</h1>
                <p class="welcome-subtitle">
                    Plateforme intuitive pour gérer vos stagiaires efficacement. 
                    Suivez leur progression, gérez les filières et communiquez facilement.
                </p>
                <div>
                    <a href="stagiaires.php" class="btn btn-primary action-btn">
                        <i class="glyphicon glyphicon-dashboard"></i> Gérer les stagiaires
                    </a>
                    <a href="filieres.php" class="btn btn-success action-btn">
                        <i class="glyphicon glyphicon-education"></i> Gérer les filières
                    </a>
                </div>
            </div>
            <div class="col-md-4 logo-container">
                <div style="font-size: 72px; font-weight: bold; font-family: Arial, sans-serif;">
                    <span style="color: #2c3e50;">D</span><span style="color: #27ae60;">I</span><span style="color: #2c3e50;">M</span>
                </div>
                <p style="color: #666;">Gestion des Stagiaires</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="glyphicon glyphicon-user"></i>
                    </div>
                    <h3 class="feature-title">Gestion des Stagiaires</h3>
                    <p class="feature-description">
                        Ajoutez, modifiez et suivez tous vos stagiaires en un seul endroit.
                    </p>
                    <span class="stat-number"><?php echo $nbStagiaires; ?> Stagiaires</span>
                    <div>
                        <a href="stagiaires.php" class="btn btn-primary">
                            <i class="glyphicon glyphicon-list"></i> Voir les stagiaires
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="glyphicon glyphicon-education"></i>
                    </div>
                    <h3 class="feature-title">Gestion des Filières</h3>
                    <p class="feature-description">
                        Organisez vos filières et niveaux de formation pour mieux structurer vos stagiaires.
                    </p>
                    <span class="stat-number"><?php echo $nbFilieres; ?> Filières</span>
                    <div>
                        <a href="filieres.php" class="btn btn-success">
                            <i class="glyphicon glyphicon-list"></i> Voir les filières
                        </a>
                    </div>
                </div>
            </div>
            
            <?php if($_SESSION['user']['role'] == 'ADMIN') { ?>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="glyphicon glyphicon-lock"></i>
                        </div>
                        <h3 class="feature-title">Gestion des Utilisateurs</h3>
                        <p class="feature-description">
                            Gérez les accès et les permissions des utilisateurs de la plateforme.
                        </p>
                        <span class="stat-number"><?php echo $nbUtilisateurs; ?> Utilisateurs</span>
                        <div>
                            <a href="utilisateurs.php" class="btn btn-info">
                                <i class="glyphicon glyphicon-list"></i> Voir les utilisateurs
                            </a>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="glyphicon glyphicon-user"></i>
                        </div>
                        <h3 class="feature-title">Mon Profil</h3>
                        <p class="feature-description">
                            Consultez et modifiez vos informations personnelles et vos préférences.
                        </p>
                        <div>
                            <a href="profile.php" class="btn btn-info">
                                <i class="glyphicon glyphicon-cog"></i> Gérer mon profil
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
