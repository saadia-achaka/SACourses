<?php

// Inclusion du fichier de connexion à la base de données
include '../componnents/connect.php';

// Vérification si l'utilisateur est connecté en vérifiant le cookie 'tutor_id'
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id']; // Récupération de l'ID du tuteur
}else{
   // Si le tuteur n'est pas connecté, redirection vers la page de connexion
   $tutor_id = '';
   header('location:log.php');
}

// Vérification si le bouton "delete" a été cliqué
if(isset($_POST['delete'])){
   $delete_id = $_POST['playlist_id']; // ID de la playlist à supprimer
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_SPECIAL_CHARS); // Nettoyage de l'ID pour éviter les injections

   // Vérification si la playlist existe et appartient au tuteur
   $verify_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND tutor_id = ? LIMIT 1");
   $verify_playlist->execute([$delete_id, $tutor_id]);

   // Si la playlist existe, on la supprime
   if($verify_playlist->rowCount() > 0){

      // Récupération de l'image de la playlist pour la supprimer du dossier
      $delete_playlist_thumb = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? LIMIT 1");
      $delete_playlist_thumb->execute([$delete_id]);
      $fetch_thumb = $delete_playlist_thumb->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded/'.$fetch_thumb['thumb']); // Suppression de l'image de la playlist

      // Suppression des favoris liés à la playlist
      $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?");
      $delete_bookmark->execute([$delete_id]);

      // Suppression de la playlist
      $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
      $delete_playlist->execute([$delete_id]);

      $message[] = 'playlist deleted!'; // Message de confirmation
   }else{
      $message[] = 'playlist already deleted!'; // Message si la playlist est déjà supprimée
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Playlists</title>

   <!-- Lien vers la bibliothèque Font Awesome pour les icônes -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé -->
   <link rel="stylesheet" href="../css/style_adm.css">

</head>
<body>

<?php include '../componnents/admin_header.php'; ?>

<section class="playlists">

   <h1 class="heading">Playlists ajoutées</h1>

   <div class="box-container">
   
      <!-- Bouton pour ajouter une nouvelle playlist -->
      <div class="box" style="text-align: center;">
         <h3 class="title" style="margin-bottom: .5rem;">Créer une nouvelle playlist</h3>
         <a href="add_plays.php" class="btn">Ajouter une playlist</a>
      </div>

      <?php
         // Sélectionner toutes les playlists du tuteur
         $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ? ORDER BY date DESC");
         $select_playlist->execute([$tutor_id]);

         // Si le tuteur a des playlists, les afficher
         if($select_playlist->rowCount() > 0){
         while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
            $playlist_id = $fetch_playlist['id'];

            // Compter le nombre de vidéos dans chaque playlist
            $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
            $count_videos->execute([$playlist_id]);
            $total_videos = $count_videos->rowCount();
      ?>
      <div class="box">
         <div class="flex">
            <!-- Affichage du statut de la playlist (active ou inactive) -->
            <div><i class="fas fa-circle-dot" style="<?php if($fetch_playlist['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fetch_playlist['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fetch_playlist['status']; ?></span></div>
            <!-- Affichage de la date de création de la playlist -->
            <div><i class="fas fa-calendar"></i><span><?= $fetch_playlist['date']; ?></span></div>
         </div>
         <div class="thumb">
            <!-- Affichage du nombre de vidéos et de la miniature de la playlist -->
            <span><?= $total_videos; ?></span>
            <img src="../uploaded/<?= $fetch_playlist['thumb']; ?>" alt="">
         </div>
         <h3 class="title"><?= $fetch_playlist['title']; ?></h3>
         <p class="description"><?= $fetch_playlist['description']; ?></p>

         <!-- Formulaire pour mettre à jour ou supprimer une playlist -->
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
            <a href="up_plays.php?get_id=<?= $playlist_id; ?>" class="option-btn">Mettre à jour</a>
            <input type="submit" value="Supprimer" class="delete-btn" onclick="return confirm('delete this playlist?');" name="delete">
         </form>

         <!-- Lien pour visualiser la playlist -->
         <a href="view_play.php?get_id=<?= $playlist_id; ?>" class="btn">Voir la playlist</a>
      </div>
      <?php
         } 
      }else{
         // Si aucune playlist n'est ajoutée, afficher un message
         echo '<p class="empty">no playlist added yet!</p>';
      }
      ?>

   </div>

</section>

<!-- Inclusion du pied de page -->
<?php include '../componnents/footer.php'; ?>

<script src="../js/adm_script.js"></script>

<script>
   // Limiter la longueur de la description des playlists à 100 caractères
   document.querySelectorAll('.playlists .box-container .box .description').forEach(content => {
      if(content.innerHTML.length > 100) content.innerHTML = content.innerHTML.slice(0, 100);
   });
</script>

</body>
</html>
