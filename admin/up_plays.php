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

// Vérification si l'ID de la playlist est passé dans l'URL, sinon redirection vers la page des playlists
if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:playlist.php');
}

// Traitement du formulaire de mise à jour de la playlist
if(isset($_POST['submit'])){

   // Récupération et nettoyage des informations soumises par le formulaire
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_SPECIAL_CHARS);

   // Mise à jour des informations de la playlist dans la base de données
   $update_playlist = $conn->prepare("UPDATE `playlist` SET title = ?, description = ?, status = ? WHERE id = ?");
   $update_playlist->execute([$title, $description, $status, $get_id]);

   // Gestion de l'upload de l'image (miniature) de la playlist
   $old_image = $_POST['old_image'];
   $old_image = filter_var($old_image, FILTER_SANITIZE_SPECIAL_CHARS);
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = unique_id().'.'.$ext;
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded/'.$rename;

   // Si une nouvelle image est téléchargée, on la traite
   if(!empty($image)){
      if($image_size > 2000000){ // Vérification de la taille de l'image
         $message[] = 'La taille de l\'image est trop grande!';
      }else{
         // Mise à jour de la miniature dans la base de données
         $update_image = $conn->prepare("UPDATE `playlist` SET thumb = ? WHERE id = ?");
         $update_image->execute([$rename, $get_id]);
         move_uploaded_file($image_tmp_name, $image_folder);
         // Suppression de l'ancienne image si elle est différente
         if($old_image != '' AND $old_image != $rename){
            unlink('../uploaded/'.$old_image);
         }
      }
   } 

   // Message de confirmation
   $message[] = 'Playlist mise à jour!';  

}

// Traitement de la suppression de la playlist
if(isset($_POST['delete'])){
   // Récupération de l'ID de la playlist à supprimer
   $delete_id = $_POST['playlist_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_SPECIAL_CHARS);
   
   // Suppression de la miniature de la playlist
   $delete_playlist_thumb = $conn->prepare("SELECT * FROM `playlist` WHERE id = ? LIMIT 1");
   $delete_playlist_thumb->execute([$delete_id]);
   $fetch_thumb = $delete_playlist_thumb->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded/'.$fetch_thumb['thumb']);

   // Suppression des favoris (bookmarks) associés à cette playlist
   $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE playlist_id = ?");
   $delete_bookmark->execute([$delete_id]);

   // Suppression de la playlist de la base de données
   $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
   $delete_playlist->execute([$delete_id]);

   // Redirection après suppression
   header('location:plays.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Mettre à jour la playlist</title>

   <!-- Lien vers le CDN de Font Awesome pour les icônes -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé -->
   <link rel="stylesheet" href="../css/style_adm.css">

</head>
<body>

<?php include '../componnents/admin_header.php'; ?>
   
<section class="playlist-form">

   <h1 class="heading">Mettre à jour la playlist</h1>

   <?php
      // Sélectionner la playlist à mettre à jour
      $select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ?");
      $select_playlist->execute([$get_id]);
      if($select_playlist->rowCount() > 0){
         while($fetch_playlist = $select_playlist->fetch(PDO::FETCH_ASSOC)){
            $playlist_id = $fetch_playlist['id'];
            // Compter le nombre de vidéos dans cette playlist
            $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
            $count_videos->execute([$playlist_id]);
            $total_videos = $count_videos->rowCount();
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <!-- Champ caché pour la miniature actuelle -->
      <input type="hidden" name="old_image" value="<?= $fetch_playlist['thumb']; ?>">
      
      <!-- Sélection du statut de la playlist -->
      <p>Statut de la playlist <span>*</span></p>
      <select name="status" class="box" required>
         <option value="<?= $fetch_playlist['status']; ?>" selected><?= $fetch_playlist['status']; ?></option>
         <option value="active">active</option>
         <option value="deactive">désactivé</option>
      </select>
      
      <!-- Champ pour le titre de la playlist -->
      <p>Titre de la playlist <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="entrer le titre de la playlist" value="<?= $fetch_playlist['title']; ?>" class="box">
      
      <!-- Champ pour la description de la playlist -->
      <p>Description de la playlist <span>*</span></p>
      <textarea name="description" class="box" required placeholder="écrire la description" maxlength="1000" cols="30" rows="10"><?= $fetch_playlist['description']; ?></textarea>
      
      <!-- Affichage de la miniature actuelle et du nombre de vidéos -->
      <p>Miniature de la playlist <span>*</span></p>
      <div class="thumb">
         <span><?= $total_videos; ?></span>
         <img src="../uploaded/<?= $fetch_playlist['thumb']; ?>" alt="Miniature">
      </div>
      
      <!-- Champ pour télécharger une nouvelle image de miniature -->
      <input type="file" name="image" accept="image/*" class="box">
      
      <!-- Bouton pour soumettre la mise à jour -->
      <input type="submit" value="Mettre à jour la playlist" name="submit" class="btn">
      
      <div class="flex-btn">
         <!-- Bouton pour supprimer la playlist -->
         <input type="submit" value="Supprimer" class="delete-btn" onclick="return confirm('Voulez-vous vraiment supprimer cette playlist?');" name="delete">
         <!-- Lien pour voir la playlist -->
         <a href="view_play.php?get_id=<?= $playlist_id; ?>" class="option-btn">Voir la playlist</a>
      </div>
   </form>
   <?php
         } 
      }else{
         echo '<p class="empty">Aucune playlist ajoutée pour le moment!</p>';
      }
   ?>

</section>

<?php include '../componnents/footer.php'; ?>

<script src="../js/adm_script.js"></script>

</body>
</html>
