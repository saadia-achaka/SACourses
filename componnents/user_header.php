<?php
// Inclusion du fichier de connexion à la base de données
include_once 'connect.php';

// Vérification si $user_id est défini, sinon initialisation à une chaîne vide
if (!isset($user_id)) {
  $user_id = '';
}

// Si des messages sont définis, les afficher un par un
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

<!-- Début de l'en-tête de la page -->
<header class="header">
   <!-- Lien vers le CDN Font Awesome pour les icônes -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé -->
   <link rel="stylesheet" href="../css/style_plat.css">

   <section class="flex">
      <!-- Logo du site -->
      <a href="home.php" class="logo">
         <img src="../images/Logo.png" alt="Saadia Logo" style="height: 100px;">
      </a>

      <!-- Formulaire de recherche de cours -->
      <form action="search_cour.php" method="post" class="search-form">
         <input type="text" name="search_course" placeholder="search courses..." required maxlength="100">
         <button type="submit" class="fas fa-search" name="search_course_btn"></button>
      </form>

      <!-- Icônes pour le menu, la recherche, le profil et le thème -->
      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <!-- Section profil utilisateur -->
      <div class="profile">
         <?php
            // Récupération des informations du profil utilisateur
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <!-- Affichage de l'image, du nom et du rôle de l'utilisateur -->
         <img src="../uploaded/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <span style="color: #e91e63;">student</span>
         <a href="profile.php" class="btn">Voir le profil</a>
         <
         <a href="../componnents/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">Déconnexion</a>
         <?php
            } else {
         ?>
         <!-- Si l'utilisateur n'est pas connecté, afficher une invitation à se connecter ou s'inscrire -->
         <h3>Veuillez vous connecter ou vous inscrire</h3>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">Se connecter</a>
            <a href="register.php" class="option-btn">S'inscrire</a>
         </div>
         <?php
            }
         ?>
      </div>
   </section>
</header>

<!-- Fin de l'en-tête -->

<!-- Début de la section de la barre latérale -->
<div class="side-bar">

   <!-- Bouton pour fermer la barre latérale -->
   <div class="close-side-bar">
      <i class="fas fa-times"></i>
   </div>

   <!-- Section profil dans la barre latérale -->
   <div class="profile">
      <?php
         // Récupération des informations du profil utilisateur pour la barre latérale
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <!-- Affichage de l'image, du nom et du rôle dans la barre latérale -->
      <img src="../uploaded/<?= $fetch_profile['image']; ?>" alt="">
      <h3><?= $fetch_profile['name']; ?></h3>
      <span style="color: #e91e63;">Étudiant</span>
      <a href="profile.php" class="btn">Voir le profil</a>
      <?php
         } else {
      ?>
      <!-- Si l'utilisateur n'est pas connecté, afficher une invitation à se connecter ou s'inscrire -->
      <h3>Veuillez vous connecter ou vous inscrire</h3>
      <div class="flex-btn" style="padding-top: .5rem;">
         <a href="login.php" class="option-btn">Se connecter</a>
         <a href="register.php" class="option-btn">S'inscrire</a>
      </div>
      <?php
         }
      ?>
   </div>

   <!-- Menu de navigation dans la barre latérale -->
   <nav class="navbar">
      <a href="home.php"><i class="fas fa-home"></i><span>Home</span></a>
      <a href="cours.php"><i class="fas fa-graduation-cap"></i><span>Cours</span></a>
      <a href="teacher.php"><i class="fas fa-chalkboard-user"></i><span>Enseignants</span></a>
      <a href="quiz.php"><i class="fas fa-brain"></i><span>Quiz</span></a>
      <a href="about.php"><i class="fas fa-question"></i><span>À propos de nous</span></a>
      <a href="contact.php"><i class="fas fa-headset"></i><span>Contactez-nous</span></a>
   </nav>
</div>
<!-- Fin de la barre latérale -->
