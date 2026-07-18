<?php

// Inclusion du fichier de connexion à la base de données
include '../componnents/connect.php';

// Vérification si le formulaire de connexion a été soumis
if(isset($_POST['submit'])){

   // Récupération des valeurs du formulaire et nettoyage des entrées
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_SPECIAL_CHARS); // Protection contre les caractères spéciaux
   $pass = sha1($_POST['pass']); // Hachage du mot de passe pour plus de sécurité
   $pass = filter_var($pass, FILTER_SANITIZE_SPECIAL_CHARS); // Protection contre les caractères spéciaux

   // Vérification des identifiants de l'utilisateur dans la base de données
   $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? AND password = ? LIMIT 1");
   $select_tutor->execute([$email, $pass]);
   $row = $select_tutor->fetch(PDO::FETCH_ASSOC);
   
   // Si les identifiants sont corrects, on crée un cookie et redirige vers le tableau de bord
   if($select_tutor->rowCount() > 0){
     setcookie('tutor_id', $row['id'], time() + 60*60*24*30, '/'); // Création d'un cookie pour garder l'utilisateur connecté
     header('location:dashboard.php'); // Redirection vers le tableau de bord
   }else{
      // Si les identifiants sont incorrects, on affiche un message d'erreur
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
   <title>Se connecter</title>

   <!-- Lien vers la bibliothèque Font Awesome pour les icônes -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé -->
   <link rel="stylesheet" href="../css/style_adm.css">

</head>
<body style="padding-left: 0;">

<?php
// Affichage des messages d'erreur si présents
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message form">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- Section du formulaire de connexion -->
<section class="form-container">

   <!-- Formulaire de connexion -->
   <form action="" method="post" enctype="multipart/form-data" class="login">
      <h3>Bienvenue de retour !</h3> <!-- Titre du formulaire -->
      <p>Votre email <span>*</span></p> <!-- Label pour l'email -->
      <input type="email" name="email" placeholder="Veuillez entrer votre email" maxlength="20" required class="box"> <!-- Champ de saisie pour l'email -->
      <p>Votre mot de passe<span>*</span></p> <!-- Label pour le mot de passe -->
      <input type="password" name="pass" placeholder="Veuillez entrer votre mot de passe" maxlength="20" required class="box"> <!-- Champ de saisie pour le mot de passe -->
      <p class="link">Vous n'avez pas de compte ? <a href="register.php">Inscrivez-vous maintenant</a></p> <!-- Lien vers la page d'inscription -->
      <input type="submit" name="submit" value="Connectez-vous maintenant" class="btn"> <!-- Bouton de soumission -->
   </form>

</section>

<!-- Script JavaScript pour gérer le mode sombre -->
<script>

let darkMode = localStorage.getItem('dark-mode'); // Récupère l'état du mode sombre dans le stockage local
let body = document.body;

// Fonction pour activer le mode sombre
const enabelDarkMode = () =>{
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled'); // Enregistre l'état du mode sombre dans le stockage local
}

// Fonction pour désactiver le mode sombre
const disableDarkMode = () =>{
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled'); // Enregistre l'état du mode sombre dans le stockage local
}

// Si le mode sombre est activé, on l'active, sinon on le désactive
if(darkMode === 'enabled'){
   enabelDarkMode();
}else{
   disableDarkMode();
}

</script>
   
</body>
</html>
