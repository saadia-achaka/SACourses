<?php

// Paramètres de connexion à la base de données
$db_name = 'mysql:host=localhost;dbname=e_learning';  // Nom de la base de données et hôte
$user_name = 'root';  // Nom d'utilisateur pour se connecter à la base de données
$user_password = '';  // Mot de passe pour se connecter à la base de données

// Création d'une nouvelle instance PDO pour établir la connexion à la base de données
$conn = new PDO($db_name, $user_name, $user_password);

// Fonction pour générer un identifiant unique aléatoire
function unique_id() {
   $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';  // Ensemble de caractères à utiliser pour générer l'ID
   $rand = array();  // Tableau pour stocker les caractères générés
   $length = strlen($str) - 1;  // Longueur de la chaîne de caractères - 1 (pour indexer correctement)
   
   // Génération de 20 caractères aléatoires
   for ($i = 0; $i < 20; $i++) {
       $n = mt_rand(0, $length);  // Choisir un index aléatoire
       $rand[] = $str[$n];  // Ajouter le caractère choisi à l'array
   }
   
   // Retourne l'ID unique généré sous forme de chaîne
   return implode($rand);
}

?>

