<?php

// Inclusion de la connexion à la base de données
include '../componnents/connect.php';

// Vérification si le tuteur est connecté, sinon redirection vers la page de connexion
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:log.php'); // Redirection en cas de non-authentification
}

// Gestion de la soumission du formulaire pour créer une nouvelle playlist
if(isset($_POST['submit'])){

   // Générer un identifiant unique pour la playlist
   $id = unique_id();

   // Récupération et nettoyage des données du formulaire
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS); // Nettoyage du titre
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS); // Nettoyage de la description
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_SPECIAL_CHARS); // Nettoyage du statut

   // Gestion de l'upload de la miniature (thumbnail)
   $image = $_FILES['image']['name']; 
   $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS); // Nettoyage du nom de fichier
   $ext = pathinfo($image, PATHINFO_EXTENSION); // Extraction de l'extension du fichier
   $rename = unique_id().'.'.$ext; // Renommage du fichier pour éviter les conflits
   $image_size = $_FILES['image']['size']; // Taille du fichier
   $image_tmp_name = $_FILES['image']['tmp_name']; // Nom temporaire du fichier
   $image_folder = '../uploaded/'.$rename; // Dossier de destination pour le fichier

   // Insertion des données de la playlist dans la base de données
   $add_playlist = $conn->prepare("INSERT INTO `playlist`(id, tutor_id, title, description, thumb, status) VALUES(?,?,?,?,?,?)");
   $add_playlist->execute([$id, $tutor_id, $title, $description, $rename, $status]);

   // Déplacement du fichier uploadé vers le dossier cible
   move_uploaded_file($image_tmp_name, $image_folder);

   // Message de confirmation
   $message[] = 'Nouvelle playlist créée !';  

}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Ajouter une Playlist</title>

   <!-- Inclusion de Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Inclusion du fichier CSS personnalisé -->
   <link rel="stylesheet" href="../css/style_adm.css">

</head>
<body>

<?php 
// Inclusion de l'en-tête administrateur
include '../componnents/admin_header.php'; 
?>
   
<section class="playlist-form">

   <h1 class="heading">Créer une Playlist</h1>

   <!-- Formulaire pour créer une nouvelle playlist -->
   <form action="" method="post" enctype="multipart/form-data">
      <p>Statut de la playlist <span>*</span></p>
      <select name="status" class="box" required>
         <option value="" selected disabled>-- Sélectionner le statut --</option>
         <option value="active">Active</option>
         <option value="deactive">Inactive</option>
      </select>
      <p>Titre de la playlist <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Entrez le titre de la playlist" class="box">
      <p>Description de la playlist <span>*</span></p>
      <textarea name="description" class="box" required placeholder="Rédigez une description" maxlength="1000" cols="30" rows="10"></textarea>
      <p>Miniature de la playlist <span>*</span></p>
      <input type="file" name="image" accept="image/*" required class="box">
      <input type="submit" value="Créer la playlist" name="submit" class="btn">
   </form>

</section>

<?php 
// Inclusion du pied de page
include 'footer.php'; 
?>

<!-- Inclusion du script JS personnalisé -->
<script src="../js/adm_script.js"></script>

</body>
</html>
