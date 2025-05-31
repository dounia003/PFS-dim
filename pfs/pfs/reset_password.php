<?php
// Script pour réinitialiser le mot de passe de l'administrateur
// À exécuter une seule fois puis à supprimer pour des raisons de sécurité

include("connexion.php");

// Mot de passe à réinitialiser
$login = "admin";
$new_password = "123";
$hashed_password = md5($new_password);

// Mettre à jour le mot de passe
$requete = "UPDATE utilisateur SET pwd = ? WHERE login = ?";
$stmt = $pdo->prepare($requete);
$result = $stmt->execute([$hashed_password, $login]);

if($result) {
    echo "<h2>Mot de passe réinitialisé avec succès</h2>";
    echo "<p>Login: " . $login . "</p>";
    echo "<p>Nouveau mot de passe: " . $new_password . "</p>";
    echo "<p>Mot de passe haché (MD5): " . $hashed_password . "</p>";
    
    // Vérifier si l'utilisateur existe maintenant
    $check = $pdo->prepare("SELECT * FROM utilisateur WHERE login = ?");
    $check->execute([$login]);
    $user = $check->fetch();
    
    if($user) {
        echo "<p>Utilisateur trouvé dans la base de données:</p>";
        echo "<ul>";
        echo "<li>ID: " . $user['iduser'] . "</li>";
        echo "<li>Login: " . $user['login'] . "</li>";
        echo "<li>Email: " . $user['email'] . "</li>";
        echo "<li>Rôle: " . $user['role'] . "</li>";
        echo "<li>État: " . ($user['etat'] == 1 ? "Actif" : "Inactif") . "</li>";
        echo "<li>Mot de passe stocké: " . $user['pwd'] . "</li>";
        echo "</ul>";
    } else {
        echo "<p>Utilisateur non trouvé après la mise à jour!</p>";
    }
} else {
    echo "<h2>Erreur lors de la réinitialisation du mot de passe</h2>";
}

echo "<p><strong>IMPORTANT: Supprimez ce fichier après utilisation pour des raisons de sécurité!</strong></p>";
?>
