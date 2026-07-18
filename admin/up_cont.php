<?php

// Inclusion du fichier de connexion à la base de données
include '../componnents/connect.php';

// Vérification si l'ID du tuteur est défini dans les cookies, sinon redirection vers la page de connexion
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:log.php');
}

// Vérification si l'ID de la vidéo est passé dans l'URL, sinon redirection vers le tableau de bord
if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:dashboard.php');
}

// Traitement de la soumission du formulaire pour mettre à jour le contenu de la vidéo
if(isset($_POST['update'])){
   
   // Nettoyage des valeurs envoyées par le formulaire pour éviter l'injection de code malveillant
   $video_id = filter_var($_POST['video_id'], FILTER_SANITIZE_STRING);
   $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
   $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
   $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
   $playlist = filter_var($_POST['playlist'], FILTER_SANITIZE_STRING);

   // Mise à jour des détails du contenu (titre, description, statut)
   $update_content = $conn->prepare("UPDATE `content` SET title = ?, description = ?, status = ? WHERE id = ?");
   $update_content->execute([$title, $description, $status, $video_id]);

   // Si une playlist est fournie, mettre à jour la playlist associée au contenu
   if(!empty($playlist)){
      $update_playlist = $conn->prepare("UPDATE `content` SET playlist_id = ? WHERE id = ?");
      $update_playlist->execute([$playlist, $video_id]);
   }

   // Gestion du téléchargement de la miniature (vérification si une nouvelle miniature est fournie)
   $old_thumb = filter_var($_POST['old_thumb'], FILTER_SANITIZE_STRING);
   $thumb = filter_var($_FILES['thumb']['name'], FILTER_SANITIZE_STRING);
   $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
   $rename_thumb = unique_id().'.'.$thumb_ext;
   $thumb_size = $_FILES['thumb']['size'];
   $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
   $thumb_folder = '../uploaded/'.$rename_thumb;

   // Si une nouvelle miniature est téléchargée, vérifier sa taille et la mettre à jour
   if(!empty($thumb)){
      if($thumb_size > 2000000){
         $message[] = 'La taille de l\'image est trop grande!';
      }else{
         $update_thumb = $conn->prepare("UPDATE `content` SET thumb = ? WHERE id = ?");
         $update_thumb->execute([$rename_thumb, $video_id]);
         move_uploaded_file($thumb_tmp_name, $thumb_folder);

         // Supprimer l'ancienne miniature si elle existe et est différente
         if($old_thumb != '' AND $old_thumb != $rename_thumb){
            unlink('../uploaded/'.$old_thumb);
         }
      }
   }

   // Gestion du téléchargement de la vidéo (vérification si une nouvelle vidéo est fournie)
   $old_video = filter_var($_POST['old_video'], FILTER_SANITIZE_STRING);
   $video = filter_var($_FILES['video']['name'], FILTER_SANITIZE_STRING);
   $video_ext = pathinfo($video, PATHINFO_EXTENSION);
   $rename_video = unique_id().'.'.$video_ext;
   $video_tmp_name = $_FILES['video']['tmp_name'];
   $video_folder = '../uploaded/'.$rename_video;

   // Si une nouvelle vidéo est téléchargée, mettre à jour le contenu de la vidéo
   if(!empty($video)){
      $update_video = $conn->prepare("UPDATE `content` SET video = ? WHERE id = ?");
      $update_video->execute([$rename_video, $video_id]);
      move_uploaded_file($video_tmp_name, $video_folder);

      // Supprimer l'ancienne vidéo si elle existe et est différente
      if($old_video != '' AND $old_video != $rename_video){
         unlink('../uploaded/'.$old_video);
      }
   }

   // Message indiquant que le contenu a été mis à jour
   $message[] = 'Le contenu a été mis à jour!';

}

// Traitement de la suppression du contenu vidéo
if(isset($_POST['delete_video'])){
   
   // Nettoyage de l'ID de la vidéo à supprimer
   $delete_id = filter_var($_POST['video_id'], FILTER_SANITIZE_STRING);

   // Suppression de la miniature associée à la vidéo
   $delete_video_thumb = $conn->prepare("SELECT thumb FROM `content` WHERE id = ? LIMIT 1");
   $delete_video_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_video_thumb->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded/'.$fetch_thumb['thumb']);

   // Suppression de la vidéo associée au contenu
   $delete_video = $conn->prepare("SELECT video FROM `content` WHERE id = ? LIMIT 1");
   $delete_video->execute([$delete_id]);
   $fetch_video = $delete_video->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded/'.$fetch_video['video']);

   // Suppression des likes et des commentaires associés à la vidéo
   $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE content_id = ?");
   $delete_likes->execute([$delete_id]);
   $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE content_id = ?");
   $delete_comments->execute([$delete_id]);

   // Suppression du contenu vidéo de la base de données
   $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?");
   $delete_content->execute([$delete_id]);
   header('location:conts.php'); // Redirection vers la page de contenu après suppression
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Mettre à jour la vidéo</title>

   <!-- Lien vers Font Awesome pour les icônes -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé pour le style -->
   <link rel="stylesheet" href="../css/style_adm.css">

</head>
<body>

<?php include '../componnents/admin_header.php'; ?>
   
<section class="video-form">

   <h1 class="heading">Mettre à jour le contenu</h1>

   <?php
      // Sélectionner le contenu vidéo à mettre à jour
      $select_videos = $conn->prepare("SELECT * FROM `content` WHERE id = ? AND tutor_id = ?");
      $select_videos->execute([$get_id, $tutor_id]);

      // Vérifier si la vidéo existe dans la base de données
      if($select_videos->rowCount() > 0){
         while($fecth_videos = $select_videos->fetch(PDO::FETCH_ASSOC)){ 
            $video_id = $fecth_videos['id'];
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="video_id" value="<?= $fecth_videos['id']; ?>">
      <input type="hidden" name="old_thumb" value="<?= $fecth_videos['thumb']; ?>">
      <input type="hidden" name="old_video" value="<?= $fecth_videos['video']; ?>">

      <!-- Sélection du statut de la vidéo -->
      <p>Mettre à jour le statut <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fecth_videos['status']; ?>" selected><?= $fecth_videos['status']; ?></option>
         <option value="active">active</option>
         <option value="deactive">désactivé</option>
      </select>

      <!-- Champ pour mettre à jour le titre -->
      <p>Mettre à jour le titre <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="entrer le titre de la vidéo" class="box" value="<?= $fecth_videos['title']; ?>">

      <!-- Champ pour mettre à jour la description -->
      <p>Mettre à jour la description <span>*</span></p>
      <textarea name="description" class="box" required placeholder="écrire la description" maxlength="1000" cols="30" rows="10"><?= $fecth_videos['description']; ?></textarea>

      <!-- Sélection de la playlist -->
      <p>Mettre à jour la playlist</p>
      <select name="playlist" class="box">
         <option value="<?= $fecth_videos['playlist_id']; ?>" selected>--sélectionner une playlist</option>
         <?php
         // Sélectionner les playlists du tuteur
         $select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
         $select_playlists->execute([$tutor_id]);
         if($select_playlists->rowCount() > 0){
            while($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)){
         ?>
         <option value="<?= $fetch_playlist['id']; ?>"><?= $fetch_playlist['title']; ?></option>
         <?php
            }
         ?>
         <?php
         }else{
            echo '<option value="" disabled>Aucune playlist créée pour le moment!</option>';
         }
         ?>
      </select>
      <img src="../uploaded/<?= $fecth_videos['thumb']; ?>" alt="">
      <p>Mettre à jour la miniature</p>
      <input type="file" name="thumb" accept="image/*" class="box">
      <video src="../uploaded/<?= $fecth_videos['video']; ?>" controls></video>
      <p>Mettre à jour la vidéo</p>
      <input type="file" name="video" accept="video/*" class="box">
      <input type="submit" value="Mettre à jour le contenu" name="update" class="btn">
      <div class="flex-btn">
         <a href="view_cont.php?get_id=<?= $video_id; ?>" class="option-btn">voir le contenu</a>
         <input type="submit" value="supprimer le contenu" name="delete_video" class="delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce contenu?');">
      </div>
   </form>
   <?php
         }
      }
   ?>
</section>

</body>
</html>
