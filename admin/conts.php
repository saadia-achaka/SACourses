<?php

// Inclusion de la connexion à la base de données
include '../componnents/connect.php';

// Vérification si l'utilisateur est connecté via un cookie, sinon redirection vers la page de connexion
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:log.php'); // Redirection si le tuteur n'est pas authentifié
}

// Suppression d'une vidéo si le formulaire correspondant est soumis
if(isset($_POST['delete_video'])){
   // Récupération et nettoyage de l'ID de la vidéo à supprimer
   $delete_id = $_POST['video_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_SPECIAL_CHARS);

   // Vérification si la vidéo existe dans la base de données
   $verify_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
   $verify_video->execute([$delete_id]);

   // Si la vidéo existe, procéder à la suppression
   if($verify_video->rowCount() > 0){
      // Suppression de l'image miniature de la vidéo
      $delete_video_thumb = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $delete_video_thumb->execute([$delete_id]);
      $fetch_thumb = $delete_video_thumb->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded/'.$fetch_thumb['thumb']); // Supprime le fichier de la miniature

      // Suppression du fichier vidéo
      $delete_video = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $delete_video->execute([$delete_id]);
      $fetch_video = $delete_video->fetch(PDO::FETCH_ASSOC);
      unlink('../uploaded/'.$fetch_video['video']); // Supprime le fichier vidéo

      // Suppression des "likes" associés à la vidéo
      $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
      $delete_likes->execute([$delete_id]);

      // Suppression des commentaires associés à la vidéo
      $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
      $delete_comments->execute([$delete_id]);

      // Suppression de l'enregistrement vidéo dans la table `content`
      $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?");
      $delete_content->execute([$delete_id]);

      $message[] = 'Vidéo supprimée avec succès !'; // Message de confirmation
   }else{
      $message[] = 'Vidéo déjà supprimée ou inexistante !'; // Message si la vidéo n'existe pas
   }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tableau de bord</title>

   <!-- Lien vers la bibliothèque Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé -->
   <link rel="stylesheet" href="../css/style_adm.css">

</head>
<body>

<?php 
// Inclusion de l'en-tête de la page administrateur
include '../componnents/admin_header.php'; 
?>
   
<section class="contents">

   <h1 class="heading">Vos contenus</h1>

   <div class="box-container">

   <!-- Boîte pour ajouter un nouveau contenu -->
   <div class="box" style="text-align: center;">
      <h3 class="title" style="margin-bottom: .5rem;">Créer un nouveau contenu</h3>
      <a href="add_cont.php" class="btn">Ajouter un contenu</a>
   </div>

   <?php
      // Récupération des contenus associés au tuteur
      $select_videos = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ? ORDER BY date DESC");
      $select_videos->execute([$tutor_id]);

      // Vérification si des contenus existent
      if($select_videos->rowCount() > 0){
         while($fecth_videos = $select_videos->fetch(PDO::FETCH_ASSOC)){ 
            $video_id = $fecth_videos['id'];
   ?>
      <!-- Affichage des informations de chaque vidéo -->
      <div class="box">
         <div class="flex">
            <div>
               <i class="fas fa-dot-circle" style="<?php if($fecth_videos['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i>
               <span style="<?php if($fecth_videos['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fecth_videos['status']; ?></span>
            </div>
            <div><i class="fas fa-calendar"></i><span><?= $fecth_videos['date']; ?></span></div>
         </div>
         <img src="../uploaded/<?= $fecth_videos['thumb']; ?>" class="thumb" alt="Miniature de la vidéo">
         <h3 class="title"><?= $fecth_videos['title']; ?></h3>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">
            <a href="up_cont.php?get_id=<?= $video_id; ?>" class="option-btn">Modifier</a>
            <input type="submit" value="Supprimer" class="delete-btn" onclick="return confirm('Supprimer cette vidéo ?');" name="delete_video">
         </form>
         <a href="view_cont.php?get_id=<?= $video_id; ?>" class="btn">Voir le contenu</a>
      </div>
   <?php
         }
      }else{
         // Message affiché si aucun contenu n'a été ajouté
         echo '<p class="empty">Aucun contenu ajouté pour le moment !</p>';
      }
   ?>

   </div>

</section>

<?php 
// Inclusion du pied de page
include '../componnents/footer.php'; 
?>

<script src="../js/adm_script.js"></script>

</body>
</html>
