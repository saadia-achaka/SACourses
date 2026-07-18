<?php

include '../componnents/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_SPECIAL_CHARS);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_SPECIAL_CHARS);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);
   
   if($select_user->rowCount() > 0){
     setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
     header('location:home.php');
   }else{
      $message[] = 'incorrect email or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style_plat.css">

</head>
<body>



<section class="form-container">

   <form action="" method="post" enctype="multipart/form-data" class="login">
      <h3>Bienvenue de retour !</h3>
      <p>Votre email <span>*</span></p>
      <input type="email" name="email" placeholder="Veuillez entrer votre email" maxlength="50" required class="box">
      <p>Votre mot de passe<span>*</span></p>
      <input type="password" name="pass" placeholder="Veuillez entrer votre mot de passe" maxlength="20" required class="box">
      <p class="link">Vous n'avez pas de compte ? <a href="register.php">Inscrivez-vous maintenant</a></p>
      <input type="submit" name="submit" value="Connectez-vous maintenant" class="btn">
   </form>

</section>














<!-- custom js file link  -->
<script src="../js/script_plat.js"></script>
   
</body>
</html>