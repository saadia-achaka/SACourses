<?php

include '../componnents/connect.php'; // Inclut le fichier de connexion à la base de données

// Vérifie si le tutor_id existe dans les cookies pour authentifier l'utilisateur
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id']; // Récupère l'ID du tuteur
}else{
   $tutor_id = ''; // Si l'ID n'est pas trouvé, on initialise à vide
   header('location:log.php'); // Redirige vers la page de connexion si l'ID est absent
}

// Suppression d'une vidéo
if(isset($_POST['delete_video'])){
   $delete_id = $_POST['video_id']; // Récupère l'ID de la vidéo à supprimer
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_SPECIAL_CHARS); // Assainit l'ID
   $verify_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1"); // Vérifie si la vidéo existe
   $verify_video->execute([$delete_id]);
   
   if($verify_video->rowCount() > 0){
      // Si la vidéo existe, on récupère les informations
      $delete_video_thumb = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $delete_video_thumb->execute([$delete_id]);
      $fetch_thumb = $delete_video_thumb->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded/'.$fetch_thumb['thumb']); // Supprime la vignette de la vidéo
      $delete_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $delete_video->execute([$delete_id]);
      $fetch_video = $delete_video->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded/'.$fetch_video['video']); // Supprime la vidéo elle-même
      $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?"); // Supprime les likes associés
      $delete_likes->execute([$delete_id]);
      $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?"); // Supprime les commentaires associés
      $delete_comments->execute([$delete_id]);
      $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?"); // Supprime la vidéo du contenu
      $delete_content->execute([$delete_id]);
      $message[] = 'video deleted!'; // Message de succès
   }else{
      $message[] = 'video already deleted!'; // Message si la vidéo est déjà supprimée
   }
}

// Suppression d'une playlist
if(isset($_POST['delete_playlist'])){
   $delete_id = $_POST['playlist_id']; // Récupère l'ID de la playlist à supprimer
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING); // Assainit l'ID

   // Vérifie si la playlist appartient au tuteur et existe
   $verify_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? AND tutor_id = ? LIMIT 1");
   $verify_playlist->execute([$delete_id, $tutor_id]);

   if($verify_playlist->rowCount() > 0){
      // Si la playlist existe, on récupère les informations
      $delete_playlist_thumb = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? LIMIT 1");
      $delete_playlist_thumb->execute([$delete_id]);
      $fetch_thumb = $delete_playlist_thumb->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded/'.$fetch_thumb['thumb']); // Supprime la vignette de la playlist
      $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?"); // Supprime les signets associés
      $delete_bookmark->execute([$delete_id]);
      $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?"); // Supprime la playlist du contenu
      $delete_playlist->execute([$delete_id]);
      $message[] = 'playlist deleted!'; // Message de succès
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
   <title>Dashboard</title>

   <!-- Lien CDN Font Awesome pour les icônes -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé -->
   <link rel="stylesheet" href="../css/style_adm.css">

</head>
<body>

<?php include '../componnents/admin_header.php'; ?> <!-- Inclut l'entête du tableau de bord -->

<section class="contents">

   <h1 class="heading">contenus</h1>

   <div class="box-container">

   <?php
      // Recherche de contenu vidéo
      if(isset($_POST['search']) or isset($_POST['search_btn'])){
         $search = $_POST['search']; // Récupère le terme de recherche
         $select_videos = $conn->prepare("SELECT * FROM `content` WHERE title LIKE '%{$search}%' AND tutor_id = ? ORDER BY date DESC");
         $select_videos->execute([$tutor_id]); // Exécute la recherche
         if($select_videos->rowCount() > 0){
            // Si des vidéos sont trouvées, on les affiche
            while($fecth_videos = $select_videos->fetch(PDO::FETCH_ASSOC)){ 
               $video_id = $fecth_videos['id']; // Récupère l'ID de la vidéo
   ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-dot-circle" style="<?php if($fecth_videos['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fecth_videos['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fecth_videos['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fecth_videos['date']; ?></span></div>
         </div>
         <img src="../uploaded/<?= $fecth_videos['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fecth_videos['title']; ?></h3>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">
            <a href="up_cont.php?get_id=<?= $video_id; ?>" class="option-btn">Mettre à jour</a>
            <input type="submit" value="Supprimer" class="delete-btn" onclick="return confirm('delete this video?');" name="delete_video">
         </form>
         <a href="view_cont.php?get_id=<?= $video_id; ?>" class="btn">Voir le contenu</a>
      </div>
   <?php
         }
      }else{
         echo '<p class="empty">no contents founds!</p>'; // Message si aucune vidéo n'est trouvée
      }
   }else{
      echo '<p class="empty">please search something!</p>'; // Message si aucun terme de recherche n'est entré
   }
   ?>

   </div>

</section>

<section class="playlists">

   <h1 class="heading">Playlists</h1>

   <div class="box-container">
   
      <?php
      // Recherche de playlist
      if(isset($_POST['search']) or isset($_POST['search_btn'])){
         $search = $_POST['search']; // Récupère le terme de recherche
         $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE title LIKE '%{$search}%' AND tutor_id = ? ORDER BY date DESC");
         $select_playlist->execute([$tutor_id]); // Exécute la recherche
         if($select_playlist->rowCount() > 0){
         while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
            $playlist_id = $fetch_playlist['id']; // Récupère l'ID de la playlist
            $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?"); // Compte le nombre de vidéos dans la playlist
            $count_videos->execute([$playlist_id]);
            $total_videos = $count_videos->rowCount();
      ?>
      <div class="box">
         <div class="flex">
            <div><i class="fas fa-circle-dot" style="<?php if($fetch_playlist['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fetch_playlist['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fetch_playlist['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fetch_playlist['date']; ?></span></div>
         </div>
         <div class="thumb">
            <span><?= $total_videos; ?></span>
            <img src="../uploaded/<?= $fetch_playlist['thumb']; ?>" alt="">
         </div>
         <h3 class="title"><?= $fetch_playlist['title']; ?></h3>
         <p class="description"><?= $fetch_playlist['description']; ?></p>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
            <a href="up_plays.php?get_id=<?= $playlist_id; ?>" class="option-btn">Mettre à jour</a>
            <input type="submit" value="Supprime_playlist" class="delete-btn" onclick="return confirm('delete this playlist?');" name="delete">
         </form>
         <a href="view_play.php?get_id=<?= $playlist_id; ?>" class="btn">Voir la playlist</a>
      </div>
      <?php
         } 
      }else{
         echo '<p class="empty">no playlists found!</p>'; // Message si aucune playlist n'est trouvée
      }}else{
         echo '<p class="empty">please search something!</p>'; // Message si aucun terme de recherche n'est entré
      }
      ?>

   </div>

</section>

<?php include '../componnents/footer.php'; ?> <!-- Inclut le pied de page -->

<script src="../js/adm_script.js"></script>

<script>
   document.querySelectorAll('.playlists .box-container .box .description').forEach(content => {
      if(content.innerHTML.length > 100) content.innerHTML = content.innerHTML.slice(0, 100); // Tronque la description des playlists si elle est trop longue
   });
</script>

</body>
</html>
