<?php

include '../componnents/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   // Vérification de l'acceptation des conditions d'utilisation
   if(!isset($_POST['terms'])){
      $message[] = 'You must accept the terms and conditions!';
   } else {
      $id = unique_id();
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
      $email = $_POST['email'];
      $email = filter_var($email, FILTER_SANITIZE_SPECIAL_CHARS);
      $pass = sha1($_POST['pass']);
      $pass = filter_var($pass, FILTER_SANITIZE_SPECIAL_CHARS);
      $cpass = sha1($_POST['cpass']);
      $cpass = filter_var($cpass, FILTER_SANITIZE_SPECIAL_CHARS);

      $image = $_FILES['image']['name'];
      $image = filter_var($image, FILTER_SANITIZE_SPECIAL_CHARS);
      $ext = pathinfo($image, PATHINFO_EXTENSION);
      $rename = unique_id().'.'.$ext;
      $image_size = $_FILES['image']['size'];
      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folder = 'uploaded/'.$rename;

      $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_user->execute([$email]);
      
      if($select_user->rowCount() > 0){
         $message[] = 'Email already taken!';
      }else{
         if($pass != $cpass){
            $message[] = 'Confirm password not matched!';
         }else{
            $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, password, image) VALUES(?,?,?,?,?)");
            $insert_user->execute([$id, $name, $email, $cpass, $rename]);
            move_uploaded_file($image_tmp_name, $image_folder);
            
            $verify_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
            $verify_user->execute([$email, $pass]);
            $row = $verify_user->fetch(PDO::FETCH_ASSOC);
            
            if($verify_user->rowCount() > 0){
               setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
               header('location:home.php');
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
   <title>Register</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../css/style_plat.css">
</head>
<body>



<!-- Affichage des messages -->
<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<section class="form-container">

   <form class="register" action="" method="post" enctype="multipart/form-data">
      <h3>Créer un compte</h3>
      <div class="flex">
         <div class="col">
            <p>Votre nom <span>*</span></p>
            <input type="text" name="name" placeholder="Veuillez entrer votre nom" maxlength="50" required class="box">
            <p>Votre Email <span>*</span></p>
            <input type="email" name="email" placeholder="Veuillez entrer votre email" maxlength="50" required class="box">
         </div>
         <div class="col">
            <p>Votre mot de passe <span>*</span></p>
            <input type="password" name="pass" placeholder="Veuillez entrer votre mot de passe" maxlength="20" required class="box">
            <p>Confirmer le mot de passe <span>*</span></p>
            <input type="password" name="cpass" placeholder="Confirmer votre mot de passe" maxlength="20" required class="box">
         </div>
      </div>
      <p>Sélectionner une image <span>*</span></p>
      <input type="file" name="image" accept="image/*" required class="box">
      
      <!-- Checkbox pour accepter les conditions d'utilisation -->
      <div class="checkbox">
         <input type="checkbox" name="terms" id="terms" required>
         <label for="terms">J'accepte les <a href="terms.php">Termes et conditions</a></label>
      </div>

      <p class="link">Vous avez déjà un compte ? <a href="login.php">Connectez-vous maintenant</a></p>
      <input type="submit" name="submit" value="Inscrivez-vous maintenant" class="btn">
   </form>

</section>


<!-- Custom JS -->
<script src="../js/script_plat.js"></script>

</body>
</html>
