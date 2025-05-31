<?php
// Script de test pour vérifier la connexion à la base de données et les identifiants
include("connexion.php");

echo "<h2>Test de connexion à la base de données</h2>";
echo "<p>Si vous voyez ce message, la connexion à la base de données fonctionne.</p>";

// Vérifier si l'utilisateur admin existe
$requete = "SELECT * FROM utilisateur WHERE login='admin'";
$resultat = $pdo->query($requete);
$user = $resultat->fetch();

if ($user) {
    echo "<p>L'utilisateur 'admin' existe dans la base de données.</p>";
    echo "<p>Email: " . $user['email'] . "</p>";
    echo "<p>Rôle: " . $user['role'] . "</p>";
    echo "<p>État: " . ($user['etat'] == 1 ? "Actif" : "Inactif") . "</p>";
    
    // Vérifier le mot de passe
    $pwd = "123";
    $hashed_pwd = md5($pwd);
    echo "<p>Mot de passe '123' haché avec MD5: " . $hashed_pwd . "</p>";
    echo "<p>Mot de passe stocké dans la base: " . $user['pwd'] . "</p>";
    
    if ($user['pwd'] === $hashed_pwd) {
        echo "<p style='color:green;'>Le mot de passe correspond!</p>";
    } else {
        echo "<p style='color:red;'>Le mot de passe ne correspond pas!</p>";
    }
} else {
    echo "<p style='color:red;'>L'utilisateur 'admin' n'existe pas dans la base de données.</p>";
}
?>
