<?php

include_once '../componnents/connect.php';
if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = ''; // Si l'ID n'existe pas, on redirige l'utilisateur vers la page de connexion
   
}
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

<header class="header">
<link rel="stylesheet"  href='../css/style_adm.css'>

   <section class="flex">

      <a href="dashboard.php" class="logo">
      <img src="../images/Logo.png" alt="Saadia Logo" style="height: 100px;">
      </a>

      <form action="search_page.php" method="post" class="search-form">
         <input type="text" name="search" placeholder="search here..." required maxlength="100">
         <button type="submit" class="fas fa-search" name="search_btn"></button>
      </form>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
            $select_profile->execute([$tutor_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="../uploaded/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <span style="color: #e91e63;"><?= $fetch_profile['profession']; ?></span>
         <a href="profile.php" class="btn">Voir le profil</a>
        
         <a href="../componnents/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">Déconnexion</a>
         <?php
            }else{
         ?>
         <h3>please login or register</h3>
          <div class="flex-btn">
            <a href="log.php" class="option-btn">Se connecter</a>
            <a href="register.php" class="option-btn">S'inscrire</a>
         </div>
         <?php
            }
         ?>
      </div>

   </section>

</header>

<!-- header section ends -->

<!-- side bar section starts  -->

<div class="side-bar">

   <div class="close-side-bar">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
            $select_profile->execute([$tutor_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="../uploaded/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <span style="color: #e91e63;"><?= $fetch_profile['profession']; ?></span>
         <a href="profile.php" class="btn">Voir le profil</a>
         <?php
            }else{
         ?>
         <h3>please login or register</h3>
          <div class="flex-btn">
            <a href="login.php" class="option-btn">Se connecter</a>
            <a href="register.php" class="option-btn">S'inscrire</a>
         </div>
         <?php
            }
         ?>
      </div>

   <nav class="navbar">
      <a href="dashboard.php"><i class="fas fa-home"></i><span>Home</span></a>
      <a href="plays.php"><i class="fa-solid fa-bars-staggered"></i><span>Playlists</span></a>
      <a href="conts.php"><i class="fas fa-graduation-cap"></i><span>Contenus</span></a>
      <a href="comms.php"><i class="fas fa-comment"></i><span>Commentaires</span></a>
      <a href="../componnents/admin_logout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>Se déconnecter</span></a>
   </nav>

</div>


<!-- side bar section ends -->