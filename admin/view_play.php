<?php

include '../componnents/connect.php';  // Inclusion du fichier de connexion à la base de données

// Vérification si l'ID du tuteur est présent dans les cookies
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];  // Récupération de l'ID du tuteur
}else{
   $tutor_id = '';
   header('location:log.php');  // Redirection vers la page de connexion si l'ID du tuteur n'est pas trouvé
}

// Vérification si l'ID de la playlist est passé dans l'URL
if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];  // Récupération de l'ID de la playlist
}else{
   $get_id = '';
   header('location:playlist.php');  // Redirection vers la page des playlists si l'ID n'est pas trouvé
}

// Suppression d'une playlist
if(isset($_POST['delete_playlist'])){
   $delete_id = $_POST['playlist_id'];  // Récupération de l'ID de la playlist à supprimer
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_SPECIAL_CHARS);  // Sanitation de l'ID pour éviter les injections

   // Récupération de l'élément de la playlist pour obtenir la miniature à supprimer
   $delete_playlist_thumb = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? LIMIT 1");
   $delete_playlist_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_playlist_thumb->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded/'.$fetch_thumb['thumb']);  // Suppression de la miniature de la playlist

   // Suppression des marque-pages associés à cette playlist
   $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?");
   $delete_bookmark->execute([$delete_id]);

   // Suppression de la playlist dans la base de données
   $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
   $delete_playlist->execute([$delete_id]);
   header('location:playlists.php');  // Redirection vers la page des playlists après suppression
}

// Suppression d'une vidéo
if(isset($_POST['delete_video'])){
   $delete_id = $_POST['video_id'];  // Récupération de l'ID de la vidéo à supprimer
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_SPECIAL_CHARS);  // Sanitation de l'ID de la vidéo

   // Vérification si la vidéo existe dans la base de données
   $verify_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
   $verify_video->execute([$delete_id]);
   if($verify_video->rowCount() > 0){
      // Récupération de la miniature et du fichier vidéo à supprimer
      $delete_video_thumb = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $delete_video_thumb->execute([$delete_id]);
      $fetch_thumb = $delete_video_thumb->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded/'.$fetch_thumb['thumb']);  // Suppression de la miniature

      $delete_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $delete_video->execute([$delete_id]);
      $fetch_video = $delete_video->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded/'.$fetch_video['video']);  // Suppression du fichier vidéo

      // Suppression des likes et commentaires associés à la vidéo
      $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
      $delete_likes->execute([$delete_id]);

      $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
      $delete_comments->execute([$delete_id]);

      // Suppression de la vidéo dans la base de données
      $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?");
      $delete_content->execute([$delete_id]);
      $message[] = 'video deleted!';  // Message de succès
   }else{
      $message[] = 'video already deleted!';  // Message si la vidéo est déjà supprimée
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Playlist Details</title>

   <!-- Lien CDN Font Awesome pour les icônes -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé -->
   <link rel="stylesheet" href="../css/style_adm.css">

</head>
<body>

<?php include '../componnents/admin_header.php'; ?>  <!-- Inclusion de l'en-tête de l'administrateur -->

<!-- Section des détails de la playlist -->
<section class="playlist-details">

   <h1 class="heading">Playlist details</h1>

   <?php
      // Sélection de la playlist spécifique du tuteur
      $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND tutor_id = ?");
      $select_playlist->execute([$get_id, $tutor_id]);
      if($select_playlist->rowCount() > 0){
         while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
            $playlist_id = $fetch_playlist['id'];
            // Compte des vidéos dans cette playlist
            $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
            $count_videos->execute([$playlist_id]);
            $total_videos = $count_videos->rowCount();
   ?>
   <div class="row">
      <div class="thumb">
         <span><?= $total_videos; ?></span>  <!-- Affichage du nombre de vidéos -->
         <img src="../uploaded/<?= $fetch_playlist['thumb']; ?>" alt="">
      </div>
      <div class="details">
         <h3 class="title"><?= $fetch_playlist['title']; ?></h3>  <!-- Affichage du titre de la playlist -->
         <div class="date"><i class="fas fa-calendar"></i><span><?= $fetch_playlist['date']; ?></span></div>  <!-- Date de création de la playlist -->
         <div class="description"><?= $fetch_playlist['description']; ?></div>  <!-- Description de la playlist -->
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">  <!-- ID caché de la playlist -->
            <a href="up_plays.php?get_id=<?= $playlist_id; ?>" class="option-btn">Mettre à jour  playlist</a>  <!-- Lien pour mettre à jour la playlist -->
            <input type="submit" value="Supprimer playlist" class="delete-btn" onclick="return confirm('delete this playlist?');" name="delete">  <!-- Bouton pour supprimer la playlist -->
         </form>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no playlist found!</p>';  // Message si aucune playlist n'est trouvée
      }
   ?>

</section>

<!-- Section des vidéos de la playlist -->
<section class="contents">

   <h1 class="heading">playlist videos</h1>

   <div class="box-container">

   <?php
      // Sélection des vidéos liées à cette playlist
      $select_videos = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ? AND playlist_id = ?");
      $select_videos->execute([$tutor_id, $playlist_id]);
      if($select_videos->rowCount() > 0){
         while($fecth_videos = $select_videos->fetch(PDO::FETCH_ASSOC)){ 
            $video_id = $fecth_videos['id'];
   ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-dot-circle" style="<?php if($fecth_videos['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fecth_videos['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fecth_videos['status']; ?></span></div>  <!-- Affichage du statut de la vidéo -->
            <div><i class="fas fa-calendar"></i><span><?= $fecth_videos['date']; ?></span></div>  <!-- Date de publication de la vidéo -->
         </div>
         <img src="../uploaded/<?= $fecth_videos['thumb']; ?>" class="thumb" alt="">  <!-- Affichage de la miniature -->
         <h3 class="title"><?= $fecth_videos['title']; ?></h3>  <!-- Titre de la vidéo -->
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">  <!-- ID caché de la vidéo -->
            <a href="up_cont.php?get_id=<?= $video_id; ?>" class="option-btn">Mettre à jour </a>  <!-- Lien pour mettre à jour la vidéo -->
            <input type="submit" value="supprimer" class="delete-btn" onclick="return confirm('delete this video?');" name="delete_video">  <!-- Bouton pour supprimer la vidéo -->
         </form>
         <a href="view_cont.php?get_id=<?= $video_id; ?>" class="btn">Voir video</a>  <!-- Lien pour visualiser la vidéo -->
      </div>
   <?php
         }
      }else{
         echo '<p class="empty">no videos added yet! <a href="add_cont.php" class="btn" style="margin-top: 1.5rem;">add videos</a></p>';  // Message si aucune vidéo n'est trouvée
      }
   ?>

   </div>

</section>

<?php include '../componnents/footer.php'; ?>  <!-- Inclusion du pied de page -->

<script src="../js/adm_script.js"></script>  <!-- Lien vers le fichier JavaScript -->

</body>
</html>
