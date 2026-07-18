<?php

// Inclusion des fichiers nécessaires pour la connexion à la base de données et l'en-tête de l'admin
include_once '../componnents/connect.php';
include_once '../componnents/admin_header.php';

// Vérification si l'ID du tuteur est présent dans les cookies
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = ''; // Si l'ID n'existe pas, on redirige l'utilisateur vers la page de connexion
   header('location:log.php');
}

// Récupération du profil du tuteur dans la base de données
$select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
$select_profile->execute([$tutor_id]);

// Si le tuteur existe, on récupère ses informations
if ($select_profile->rowCount() > 0) {
    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
} else {
    // Sinon, on définit un nom par défaut si le tuteur n'est pas trouvé
    $fetch_profile = ['name' => 'Unknown'];
}

// Comptage des contenus ajoutés par le tuteur
$select_con = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
$select_con->execute([$tutor_id]);
$total_con = $select_con->rowCount(); // Nombre total de contenus

// Comptage des playlists créées par le tuteur
$select_plays = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
$select_plays->execute([$tutor_id]);
$total_play = $select_plays->rowCount(); // Nombre total de playlists

// Comptage des likes donnés au tuteur
$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
$select_likes->execute([$tutor_id]);
$total_likes = $select_likes->rowCount(); // Nombre total de likes

// Comptage des commentaires associés au tuteur
$select_coms = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
$select_coms->execute([$tutor_id]);
$total_coms = $select_coms->rowCount(); // Nombre total de commentaires

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <!-- Lien vers la bibliothèque Boxicons pour les icônes -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel='stylesheet'>
  <!-- Lien vers le fichier CSS pour le style de l'interface admin -->
  <link rel="stylesheet"  href='../css/style_adm.css'>
</head>
<body>

<!-- Section du tableau de bord -->
<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">

      <!-- Affichage du message de bienvenue avec le nom du tuteur -->
      <div class="box">
         <h3> <i class="fas fa-user-circle" style="color:#e91e63; margin-right: 10px;"></i>welcome</h3>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="profile.php" class="btn">Voir le profil</a> <!-- Lien vers le profil du tuteur -->
      </div>

      <!-- Affichage du nombre total de contenus -->
      <div class="box">
         <h3>  <i class="fas fa-video" style="color: #e91e63; margin-right: 10px;"></i><?= $total_con; ?></h3>
         <p> total contents</p>
         <a href="add_cont.php" class="btn">Ajouter du nouveau contenu</a> <!-- Lien pour ajouter un nouveau contenu -->
      </div>

      <!-- Affichage du nombre total de playlists -->
      <div class="box">
         <h3> <i class="fas fa-list" style="color: #e91e63; margin-right: 10px;"></i><?= $total_play; ?></h3>
         <p>total playlists</p>
         <a href="add_plays.php" class="btn">Ajouter du nouveau playlist</a> <!-- Lien pour ajouter une nouvelle playlist -->
      </div>

      <!-- Affichage du nombre total de likes -->
      <div class="box">
         <h3><i class="fas fa-thumbs-up" style="color: #e91e63; margin-right: 10px;"></i><?= $total_likes; ?></h3>
         <p>total likes</p>
         <a href="conts.php" class="btn">Consulter le contenu</a> <!-- Lien pour afficher les contenus -->
      </div>

      <!-- Affichage du nombre total de commentaires -->
      <div class="box">
         <h3><i class="fas fa-comments" style="color: #e91e63; margin-right: 10px;"></i><?= $total_coms; ?></h3>
         <p>total comments</p>
         <a href="comms.php" class="btn">Consulter les commentaires</a> <!-- Lien pour afficher les commentaires -->
      </div>

      

   </div>

</section>

<?php include 'footer.php'; ?> <!-- Inclusion du pied de page -->
<script src="../js/adm_script.js"></script> <!-- Inclusion du script JavaScript -->
</body>
</html>
