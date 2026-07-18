<?php

   // Inclusion du fichier de connexion à la base de données
   include '../componnents/connect.php';

   // Vérification de la présence du cookie 'tutor_id' pour savoir si un tuteur est connecté
   if(isset($_COOKIE['tutor_id'])){
      $tutor_id = $_COOKIE['tutor_id']; // Récupération de l'ID du tuteur depuis le cookie
   }else{
      $tutor_id = ''; // Si le cookie n'est pas présent, on redirige vers la page de connexion
      header('location:log.php');
   }

   // Sélection des informations du tuteur à partir de son ID
   $select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
   $select_profile->execute([$tutor_id]);

   // Si un profil de tuteur est trouvé, on récupère ses informations
   if ($select_profile->rowCount() > 0) {
       $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
   } else {
       $fetch_profile = ['name' => 'Unknown']; // Valeur par défaut si le profil n'est pas trouvé
   }

   // Comptage du nombre de playlists créées par le tuteur
   $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
   $select_playlists->execute([$tutor_id]);
   $total_playlists = $select_playlists->rowCount(); // Total des playlists

   // Comptage du nombre de contenus (vidéos) créés par le tuteur
   $select_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
   $select_contents->execute([$tutor_id]);
   $total_contents = $select_contents->rowCount(); // Total des vidéos

   // Comptage du nombre de likes donnés par le tuteur
   $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
   $select_likes->execute([$tutor_id]);
   $total_likes = $select_likes->rowCount(); // Total des likes

   // Comptage du nombre de commentaires laissés par le tuteur
   $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
   $select_comments->execute([$tutor_id]);
   $total_comments = $select_comments->rowCount(); // Total des commentaires

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile</title>

   <!-- Lien vers Font Awesome pour les icônes -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé -->
   <link rel="stylesheet" href="../css/style_adm.css">

</head>
<body>

<?php include '../componnents/admin_header.php'; ?>

<section class="tutor-profile" style="min-height: calc(100vh - 19rem);"> 
   <!-- Section du profil du tuteur -->

   <h1 class="heading">Informations du profil</h1>

   <div class="details">
      <!-- Affichage des détails du tuteur -->
      <div class="tutor">
         <!-- Affichage de l'image, du nom et de la profession du tuteur -->
         <img src="../uploaded/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <span><?= $fetch_profile['profession']; ?></span>
         <!-- Lien vers la page de mise à jour du profil -->
         <a href="up.php" class="inline-btn">Mettre à jour le profil</a>
      </div>

      <div class="flex">
         <!-- Affichage des statistiques sur les playlists, vidéos, likes et commentaires -->

         <div class="box">
            <span><i class="fas fa-list" style="color: #e91e63; margin-right: 10px;"></i><?= $total_playlists; ?></span> <!-- Nombre de playlists -->
            <p>Nombre total de playlists</p>
            <!-- Lien vers la page des playlists -->
            <a href="plays.php" class="btn"> Voir les playlists</a>
         </div>

         <div class="box">
            <span><i class="fas fa-video" style="color: #e91e63; margin-right: 10px;"></i><?= $total_contents; ?></span> <!-- Nombre de vidéos -->
            <p>Nombre total de videos</p>
            <!-- Lien vers la page des vidéos -->
            <a href="conts.php" class="btn">Voir les contenus</a>
         </div>

         <div class="box">
            <span><i class="fas fa-thumbs-up" style="color: #e91e63; margin-right: 10px;"></i><?= $total_likes; ?></span> <!-- Nombre de likes -->
            <p>Nombre total de likes</p>
            <!-- Lien vers la page des contenus pour voir les likes -->
            <a href="conts.php" class="btn">Voir les likes</a>
         </div>

         <div class="box">
            <span><i class="fas fa-comments" style="color: #e91e63; margin-right: 10px;"></i><?= $total_comments; ?></span> <!-- Nombre de commentaires -->
            <p>Nombre total de commentaires</p>
            <!-- Lien vers la page des commentaires -->
            <a href="comms.php" class="btn">Voir les  commentaires</a>
         </div>
      </div>
   </div>

</section>

<?php include '../componnents/footer.php'; ?>

<script src="../js/adm_script.js"></script>

</body>
</html>
