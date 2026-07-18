<?php

// Inclusion du fichier de connexion à la base de données
include '../componnents/connect.php';

// Vérification si le formulaire a été soumis
if (isset($_POST['submit'])) {

   // Vérification si les conditions d'utilisation sont acceptées
   if (!isset($_POST['terms'])) {
      $message[] = 'You must accept the Terms and Conditions to register!';
   } else {
      // Génération d'un ID unique pour le tuteur
      $id = unique_id();
      // Récupération et nettoyage des données du formulaire
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
      $profession = $_POST['profession'];
      $profession = filter_var($profession, FILTER_SANITIZE_SPECIAL_CHARS);
      $email = $_POST['email'];
      $email = filter_var($email, FILTER_SANITIZE_SPECIAL_CHARS);
      $pass = sha1($_POST['pass']); // Hachage du mot de passe
      $pass = filter_var($pass, FILTER_SANITIZE_SPECIAL_CHARS);
      $cpass = sha1($_POST['cpass']); // Hachage du mot de passe de confirmation
      $cpass = filter_var($cpass, FILTER_SANITIZE_SPECIAL_CHARS);

      // Traitement de l'image téléchargée
      $image = $_FILES['image']['name'];
      $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
      $ext = pathinfo($image, PATHINFO_EXTENSION); // Récupération de l'extension de l'image
      $rename = unique_id() . '.' . $ext; // Renommage unique de l'image
      $image_size = $_FILES['image']['size']; // Taille de l'image
      $image_tmp_name = $_FILES['image']['tmp_name']; // Chemin temporaire de l'image
      $image_folder = '../uploaded/' . $rename; // Dossier où l'image sera stockée

      // Vérification si l'email est déjà utilisé dans la base de données
      $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
      $select_tutor->execute([$email]);

      if ($select_tutor->rowCount() > 0) { // Si l'email est déjà pris, on affiche un message
         $message[] = 'email already taken!';
      } else {
         // Vérification si les mots de passe correspondent
         if ($pass != $cpass) {
            $message[] = 'confirm password not matched!';
         } else {
            // Vérification si la taille de l'image est trop grande
            if ($image_size > 2000000) {
               $message[] = 'image size is too large';
            } else {
               // Insertion du tuteur dans la base de données
               $insert_tutor = $conn->prepare("INSERT INTO `tutors`(id, name, profession, email, password, image) VALUES(?,?,?,?,?,?)");
               $insert_tutor->execute([$id, $name, $profession, $email, $cpass, $rename]);

               // Déplacement de l'image dans le dossier de destination
               move_uploaded_file($image_tmp_name, $image_folder);

               // Vérification des informations du tuteur pour se connecter
               $verify_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? AND password = ? LIMIT 1");
               $verify_tutor->execute([$email, $cpass]);
               $row = $verify_tutor->fetch(PDO::FETCH_ASSOC);

               // Si la vérification est réussie
               if ($verify_tutor) {
                  if ($verify_tutor->rowCount() > 0) {
                     // Enregistrement de l'ID du tuteur dans un cookie et redirection vers le tableau de bord
                     setcookie('tutor_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
                     header('location:dashboard.php');
                  } else {
                     $message[] = 'something wrong'; // Si une erreur se produit
                  }
               }
            }
         }
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- Lien vers Font Awesome pour les icônes -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé -->
   <link rel="stylesheet" href="../css/style_adm.css">

</head>
<body style="padding-left: 0;">

<!-- Affichage des messages d'erreur ou de succès -->
<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message form">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- Section d'inscription -->
<section class="form-container">

   <!-- Formulaire d'inscription -->
   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>Nouvelle inscription</h3>
      <div class="flex">
         <div class="col">
            <p>Votre nom<span>*</span></p>
            <input type="text" name="name" placeholder="Veuillez entrer votre nom" maxlength="50" required class="box">
            <p>Votre profession <span>*</span></p>
            <select name="profession" class="box" required>
               <option value="" disabled selected>-- Sélectionnez votre profession</option>
               <option value="developer">developer</option>
               <option value="designer">designer</option>
               <option value="translator">translator</option>
               <option value="teacher">teacher</option>
               <option value="engineer">engineer</option>
            </select>
            <p>Votre email <span>*</span></p>
            <input type="email" name="email" placeholder="Veuillez entrer votre email" maxlength="50" required class="box">
         </div>
         <div class="col">
            <p>Votre pmot de passe<span>*</span></p>
            <input type="password" name="pass" placeholder="Veuillez entrer votre mot de passe" maxlength="20" required class="box">
            <p>Confirmer le mot de passe<span>*</span></p>
            <input type="password" name="cpass" placeholder="Confirmer votre mot de passe" maxlength="20" required class="box">
            <p>Sélectionner une image <span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">
         </div>
      </div>
      <p>
         <input type="checkbox" id="terms" name="terms" required>
         <label for="terms">J'accepte les<a href="terms.php" target="_blank"> Termes et conditions</a></label>
      </p>
      <p class="link">Vous avez déjà un compte ? <a href="log.php">Connectez-vous maintenant</a></p>
      <input type="submit" name="submit" value="Inscrivez-vous maintenant" class="btn">
   </form>

</section>

</body>
</html>
