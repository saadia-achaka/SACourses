<?php

// Inclusion de la connexion à la base de données
include '../componnents/connect.php';

// Vérification si le tuteur est connecté, sinon redirection vers la page de connexion
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:log.php'); // Redirection si le tuteur n'est pas authentifié
}

// Suppression d'un commentaire si le formulaire correspondant est soumis
if(isset($_POST['delete_comment'])){

   // Récupération et nettoyage de l'ID du commentaire à supprimer
   $delete_id = $_POST['comment_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_SPECIAL_CHARS);

   // Vérification si le commentaire existe dans la base de données
   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
   $verify_comment->execute([$delete_id]);

   // Si le commentaire existe, le supprimer
   if($verify_comment->rowCount() > 0){
      $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
      $delete_comment->execute([$delete_id]);
      $message[] = 'Commentaire supprimé avec succès !'; // Message de confirmation
   }else{
      $message[] = 'Commentaire déjà supprimé !'; // Message si le commentaire n'existe pas
   }

}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tableau de Bord</title>

   <!-- Lien vers la bibliothèque Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé -->
   <link rel="stylesheet" href="../css/style_adm.css">

</head>
<body>

<?php 
// Inclusion de l'en-tête administrateur
include '../componnents/admin_header.php'; 
?>

<section class="comments">

   <h1 class="heading">Commentaires des utilisateurs</h1>

   <div class="show-comments">
      <?php
         // Récupération des commentaires liés au tuteur
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
         $select_comments->execute([$tutor_id]);
         
         // Si des commentaires existent, les afficher
         if($select_comments->rowCount() > 0){
            while($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)){
               
               // Récupération des détails du contenu lié au commentaire
               $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
               $select_content->execute([$fetch_comment['content_id']]);
               $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box" style="<?php if($fetch_comment['tutor_id'] == $tutor_id){echo 'order:-1;';} ?>">
         <div class="content">
            <span><?= $fetch_comment['date']; ?></span>
            <p> - <?= $fetch_content['title']; ?> - </p>
            <a href="view_cont.php?get_id=<?= $fetch_content['id']; ?>">Voir le contenu</a>
         </div>
         <p class="text"><?= $fetch_comment['comment']; ?></p>
         <form action="" method="post">
            <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
            <button type="submit" name="delete_comment" class="inline-delete-btn" onclick="return confirm('Supprimer ce commentaire ?');">Supprimer le commentaire</button>
         </form>
      </div>
      <?php
       }
      }else{
         // Message affiché si aucun commentaire n'a été ajouté
         echo '<p class="empty">Aucun commentaire ajouté pour le moment !</p>';
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
