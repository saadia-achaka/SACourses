<?php

include '../componnents/connect.php'; // Inclusion de la connexion à la base de données

// Vérification si l'ID du tuteur est stocké dans un cookie
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id']; // Récupération de l'ID du tuteur depuis le cookie
}else{
   $tutor_id = ''; // Si aucun ID n'est trouvé, la variable est vide
   header('location:log.php'); // Redirection vers la page de connexion si le tuteur n'est pas connecté
}

// Vérification si un ID de contenu est passé dans l'URL
if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id']; // Récupération de l'ID du contenu
}else{
   $get_id = ''; // Si l'ID n'est pas trouvé, la variable est vide
   header('location:conts.php'); // Redirection si l'ID du contenu est manquant
}

// Suppression d'une vidéo lorsqu'un formulaire de suppression est soumis
if(isset($_POST['delete_video'])){

   // Récupération et sanitation de l'ID de la vidéo
   $delete_id = $_POST['video_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_SPECIAL_CHARS);

   // Suppression de la miniature (thumbnail) associée à la vidéo
   $delete_video_thumb = $conn->prepare("SELECT thumb FROM `content` WHERE id = ? LIMIT 1");
   $delete_video_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_video_thumb->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded/'.$fetch_thumb['thumb']); // Suppression du fichier de la miniature

   // Suppression du fichier vidéo
   $delete_video = $conn->prepare("SELECT video FROM `content` WHERE id = ? LIMIT 1");
   $delete_video->execute([$delete_id]);
   $fetch_video = $delete_video->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded/'.$fetch_video['video']); // Suppression du fichier vidéo

   // Suppression des likes associés à la vidéo
   $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
   $delete_likes->execute([$delete_id]);

   // Suppression des commentaires associés à la vidéo
   $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
   $delete_comments->execute([$delete_id]);

   // Suppression du contenu de la vidéo de la base de données
   $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?");
   $delete_content->execute([$delete_id]);
   header('location:conts.php'); // Redirection vers la page des contenus après suppression
}

// Suppression d'un commentaire lorsqu'un formulaire de suppression est soumis
if(isset($_POST['delete_comment'])){

   // Récupération et sanitation de l'ID du commentaire
   $delete_id = $_POST['comment_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_SPECIAL_CHARS);

   // Vérification si le commentaire existe avant de le supprimer
   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
   $verify_comment->execute([$delete_id]);

   // Si le commentaire existe, il est supprimé
   if($verify_comment->rowCount() > 0){
      $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
      $delete_comment->execute([$delete_id]);
      $message[] = 'comment deleted successfully!'; // Message de confirmation de suppression
   }else{
      $message[] = 'comment already deleted!'; // Message si le commentaire n'existe plus
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Voir le contenu</title>

   <!-- Lien vers la feuille de style Font Awesome pour les icônes -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Lien vers la feuille de style personnalisée pour l'administration -->
   <link rel="stylesheet" href="../css/style_adm.css">

</head>
<body>

<?php include '../componnents/admin_header.php'; ?> <!-- Inclusion de l'en-tête d'administration -->

<section class="view-content">

   <?php
      // Sélection du contenu basé sur l'ID et l'ID du tuteur
      $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? AND tutor_id = ?");
      $select_content->execute([$get_id, $tutor_id]);
      if($select_content->rowCount() > 0){
         while($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)){
            $video_id = $fetch_content['id'];

            // Comptage des likes pour cette vidéo
            $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ? AND content_id = ?");
            $count_likes->execute([$tutor_id, $video_id]);
            $total_likes = $count_likes->rowCount();

            // Comptage des commentaires pour cette vidéo
            $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ? AND content_id = ?");
            $count_comments->execute([$tutor_id, $video_id]);
            $total_comments = $count_comments->rowCount();
   ?>
   <div class="container">
      <video src="../uploaded/<?= $fetch_content['video']; ?>" autoplay controls poster="../uploaded/<?= $fetch_content['thumb']; ?>" class="video"></video>
      <div class="date"><i class="fas fa-calendar"></i><span><?= $fetch_content['date']; ?></span></div>
      <h3 class="title"><?= $fetch_content['title']; ?></h3>
      <div class="flex">
         <div><i class="fas fa-heart"></i><span><?= $total_likes; ?></span></div>
         <div><i class="fas fa-comment"></i><span><?= $total_comments; ?></span></div>
      </div>
      <div class="description"><?= $fetch_content['description']; ?></div>
      <form action="" method="post">
         <div class="flex-btn">
            <input type="hidden" name="video_id" value="<?= $video_id; ?>">
            <a href="up_cont.php?get_id=<?= $video_id; ?>" class="option-btn">Mettre à jour </a>
            <input type="submit" value="Supprimer" class="delete-btn" onclick="return confirm('delete this video?');" name="delete_video">
         </div>
      </form>
   </div>
   <?php
    }
   }else{
      echo '<p class="empty">no contents added yet! <a href="add_content.php" class="btn" style="margin-top: 1.5rem;">add videos</a></p>'; // Si aucun contenu n'est trouvé
   }
      
   ?>

</section>

<section class="comments">

   <h1 class="heading">Commentaires des utilisateurs</h1>

   <div class="show-comments">
      <?php
         // Sélection des commentaires associés au contenu
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ?");
         $select_comments->execute([$get_id]);
         if($select_comments->rowCount() > 0){
            while($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)){   
               // Récupération des informations de l'utilisateur ayant commenté
               $select_commentor = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
               $select_commentor->execute([$fetch_comment['user_id']]);
               $fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="user">
            <img src="../uploaded/<?= $fetch_commentor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_commentor['name']; ?></h3>
               <span><?= $fetch_comment['date']; ?></span>
            </div>
         </div>
         <p class="text"><?= $fetch_comment['comment']; ?></p>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
            <button type="submit" name="delete_comment" class="inline-delete-btn" onclick="return confirm('delete this comment?');">Supprimer les commentaires/button>
         </form>
      </div>
      <?php
       }
      }else{
         echo '<p class="empty">no comments added yet!</p>'; // Si aucun commentaire n'est trouvé
      }
      ?>
      </div>
   
</section>

<?php include '../componnents/footer.php'; ?> <!-- Inclusion du pied de page -->

<script src="../js/adm_script.js"></script> <!-- Lien vers le script JavaScript d'administration -->

</body>
</html>
