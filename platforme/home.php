<?php

include '../componnents/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>

   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS file link 
   <link rel="stylesheet" href="../css/style_plat.css">-->

</head>
<style>
/* Variables pour les couleurs */
:root {
    --primary-color: #3498db; 
    --secondary-color: #e91e63; 
    --accent-color: #e91e63; 
    --background-color: #F9F9F9; /* Fond clair */
    --text-color: #333333; /* Couleur du texte */
    --heading-color: #212121; /* Couleur des titres */
}

/* Reset CSS */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', Arial, sans-serif;
    background-color: var(--background-color);
    color: var(--text-color);
    line-height: 1.6;
    overflow-x: hidden;
}

/* En-tête utilisateur */
header {
    background: var(--primary-color);
    padding: 1rem;
    color: white;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Hero Section */
.hero-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 2rem;
    background: linear-gradient(to right, #FFC1CC, #e91e63);
    color: var(--text-color);
    min-height: 80vh;
    gap: 2rem;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.hero-section .content {
    flex: 1;
    max-width: 50%;
    animation: fade-in 1s ease-in-out;
}

.hero-section .content h1 {
    font-size: 3rem;
    font-family: 'Baloo 2', cursive;
    margin-bottom: 1rem;
    color: var(--heading-color);
}

.hero-section .content p {
    font-size: 2rem;
    line-height: 1.6;
    margin-bottom: 2rem;
}

.hero-section .content .cta-btn {
    display: inline-block;
    background-color: var(--accent-color);
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 10px;
    font-weight: bold;
    font-size: 1.9rem;
    text-decoration: none;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    transition: background 0.3s ease, transform 0.2s ease;
}

.hero-section .content .cta-btn:hover {
    background-color: #e91e63;
    transform: scale(1.05);
}

.hero-section .image-container {
    flex: 1;
    text-align: center;
    animation: slide-in 1s ease-in-out;
}

.hero-section .image-container img {
    max-width: 100%;
    height: auto;
    border-radius: 15px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

@keyframes fade-in {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slide-in {
    from {
        transform: translateX(100%);
    }
    to {
        transform: translateX(0);
    }
}

/* Quick Select Section */
.quick-select {
    padding: 2rem;
    background: var(--background-color);
}

.quick-select .heading {
    font-size: 4rem;
    text-align: center;
    color: var(--heading-color);
    margin-bottom: 2rem;
}

.quick-select .box-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    justify-content: center;
    animation: scale-up 1s ease-in-out;
}

.quick-select .box {
    background: black;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.quick-select .box:hover {
    transform: scale(1.05);
    box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.15);
}

.quick-select .box h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.quick-select .box p {
    font-size: 1rem;
    color: var(--text-color);
    margin: 0.5rem 0;
}

.quick-select .box a {
    display: inline-block;
    background: var(--accent-color);
    color: black;
    padding: 0.5rem 1rem;
    margin-top: 0.5rem;
    border-radius: 5px;
    text-decoration: none;
    transition: background 0.3s ease;
}

.quick-select .box a:hover {
    background: #e91e63;
}

@keyframes scale-up {
    from {
        transform: scale(0.8);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

/* Courses Section */
.courses {
    padding: 2rem;
    background: var(--background-color);
}

.courses .heading {
    font-size: 3rem;
    text-align: center;
    color: var(--heading-color);
    margin-bottom: 2rem;
}

.courses .box-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    justify-content: center;
    animation: staggered-fade-in 1s ease-in-out;
}

.courses .box {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: white;
    border-radius: 10px;
    padding: 1rem;
    text-align: center;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.courses .box:hover {
    transform: translateY(-5px);
    box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.15);
}

.courses .box img.thumb {
    width: 100%;
    border-radius: 10px;
    margin-bottom: 1rem;
    transition: transform 0.3s ease;
}

.courses .box:hover img.thumb {
    transform: scale(1.05);
}

.courses .box .title {
    font-size: 1.2rem;
    font-weight: bold;
    margin: 0.5rem 0;
}

.courses .box .inline-btn {
    display: inline-block;
    background: var(--primary-color);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    text-decoration: none;
    margin-top: 0.5rem;
    transition: background 0.3s ease;
}

.courses .box .inline-btn:hover {
    background: #388E3C;
}

@keyframes staggered-fade-in {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Pied de page */
footer {
    background: var(--primary-color);
    color: #3498db;
    padding: 2rem;
    text-align: center;
    margin-top: 2rem;
}

footer .social-icons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1rem;
}

footer .social-icons a {
    display: inline-block;
    background: white;
    color: var(--primary-color);
    width: 40px;
    height: 40px;
    line-height: 40px;
    text-align: center;
    border-radius: 50%;
    transition: transform 0.3s ease, background 0.3s ease;
}

footer .social-icons a:hover {
    background: var(--accent-color);
    transform: scale(1.2);
    color: white;
}

/* Adaptatif */
@media (max-width: 768px) {
    .hero-section {
        flex-direction: column-reverse;
        text-align: center;
    }

    .hero-section .content {
        max-width: 100%;
    }

    .quick-select .box-container,
    .courses .box-container {
        flex-direction: column;
    }
}

.professors {
    padding: 2rem;
    background: var(--background-color);
}

.professors .heading {
    font-size: 3rem;
    text-align: center;
    color: var(--heading-color);
    margin-bottom: 2rem;
}

.professors .box-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    justify-content: center;
}

.professors .box {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.professors .box:hover {
    transform: translateY(-5px);
    box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.15);
}

.professors .box img.photo {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 1rem;
}

.professors .box h3 {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    color: var(--heading-color);
}

.professors .box p {
    font-size: 2rem;
    color: var(--text-color);
    margin-bottom: 1rem;
}

.professors .box .social-links a {
    display: inline-block;
    margin: 0 0.5rem;
    color: var(--primary-color);
    font-size: 1.5rem;
    transition: color 0.3s ease, transform 0.3s ease;
}

.professors .box .social-links a:hover {
    color: var(--accent-color);
    transform: scale(1.2);
}


</style>
<body>

<?php include '../componnents/user_header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
   <div class="content">
      <h1>Bienvenue sur SACourses</h1>
      <p>Explorez nos playlists éducatives spécialement conçues pour les enfants. Apprenez en vous amusant avec des cours interactifs sur le développement, les réseaux, la sécurité, et bien plus encore.</p>
      <a href="cours.php" class="cta-btn">Commencer l'apprentissage</a>
   </div>
   <div class="image-container">
      <img src="../images/home-background.jpg" alt="Enfants apprenant en ligne">
   </div>
</section>
<!-- Hero Section Ends -->

<!-- Quick Select Section Starts -->
<section class="quick-select">

   <h1 class="heading">Choix rapides</h1>

   <div class="box-container">

      <?php if($user_id != '') { ?>
      <div class="box">
         <h3 class="title">J'aime et Commentaires</h3>
         <p>Nombre total de likes: <span><?= $total_likes; ?></span></p>
         <a href="likes.php" class="inline-btn">Voir Likes</a>
         <p>Nombre total de Commentaires: <span><?= $total_comments; ?></span></p>
         <a href="comments.php" class="inline-btn">Voir les Commentaires</a>
         <p>Playlist enregistrée : <span><?= $total_bookmarked; ?></span></p>
         <a href="feedback.php" class="inline-btn">Voir Playlistes</a>
      </div>
      <?php } else { ?>
      <div class="box" style="text-align: center;">
      <div >
         <h3 >Devenir enseignant</h3>
         <p>Partagez vos connaissances et inspirez la prochaine génération d'apprenants ! Rejoignez notre plateforme en tant que tuteur et profitez d'outils puissants pour créer, gérer et dispenser vos cours. Que vous soyez expert en programmation, en design ou en sciences, votre expertise peut faire la différence. Inscrivez-vous dès aujourd'hui et commencez votre aventure en tant qu'éducateur</p>
         <a href="../admin/register.php" class="inline-btn">Commencer</a>
      </div>
      </div>
      <?php } ?>
      

    

      <div class="box">
         <h3 class="title">Thèmes populaires
         </h3>
         <div class="flex">
            <a href="#"><i class="fab fa-css3" style="color: #e91e63;"></i><span>CSS</span></a>
            <a href="#"><i class="fab fa-php" style="color: #e91e63;"></i><span>PHP</span></a>
            <a href="#"><i class="fab fa-js-square"style="color: #e91e63;"></i><span>JavaScript</span></a>
            <a href="#"><i class="fab fa-java"style="color: #e91e63;"></i><span>Java</span></a>
            <a href="#"><i class="fas fa-comment-dots"style="color: #e91e63;"></i><span>Englais</span></a>
         
         </div>
      </div>

        <div class="box">
         <h3 class="title">Meilleurs Categories</h3>
         <div class="flex">
            <a href="search_cour.php?"><i class="fas fa-code" style="color: #e91e63;"></i><span>Development</span></a>
            <a href="search_cour.php?"><i class="fas fa-network-wired" style="color: #e91e63;"></i><span>Réseaux et Sécurité</span></a>
            <a href="search_cour.php?"><i class="fas fa-paint-brush" style="color: #e91e63;"></i><span>Design</span></a>
            <a href="search_cour.php?"><i class="fas fa-laptop"style="color: #e91e63;"></i><span>Informatique Générale</span></a>
            <a href="search_cour.php?"><i class="fas fa-flask"style="color: #e91e63;"></i><span>Sciences et Technologies</span></a>
            <a href="search_cour.php?"><i class="fas fa-language"style="color: #e91e63;"></i><span>Langues</span></a>
         </div>
      </div>

      



   </div>

</section>
<!-- Quick Select Section Ends -->

<!-- Courses Section Starts -->
<section class="courses">

   <h1 class="heading">Meilleurs cours</h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC LIMIT 6");
         $select_courses->execute(['active']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_course['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="tutor">
            <img src="../uploaded/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
            </div>
         </div>
         <img src="../uploaded/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         <a href="playlist.php?get_id=<?= $course_id; ?>" class="inline-btn">Voir Playlist</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">No courses added yet!</p>';
      }
      ?>

   </div>

  

</section>

<!-- Section des Professeurs -->
<section class="professors">
    <h1 class="heading">Nos Professeurs</h1>
    <div class="box-container">
        <!-- Professeur 1 -->
        <div class="box">
            <img src="../images/Mohammed.jpg" alt="Professeur 1" class="photo">
            <h3>Mohammed BARADA</h3>
            <p>Développeur Full-Stack avec 10 ans d'expérience</p>
            <p>Expert en JavaScript, PHP, et CSS</p>
            <div class="social-links">
                <a href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin"></i></a>
                <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook"></i></a>
                <a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <!-- Professeur 2 -->
        <div class="box">
            <img src="../images/Kawter.jpg" alt="Professeur 2" class="photo">
            <h3>Kawter Curie</h3>
            <p>Ingénieure réseau et spécialiste en cybersécurité</p>
            <p>Plus de 8 ans d'expérience dans le domaine</p>
            <div class="social-links">
                <a href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin"></i></a>
                <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook"></i></a>
                <a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <!-- Professeur 3 -->
        <div class="box">
            <img src="../images/team-06.jpg" alt="Professeur 3" class="photo">
            <h3>Amin Ben Ahmed</h3>
            <p>Scientifique en intelligence artificielle</p>
            <p>Spécialiste en apprentissage profond et traitement du langage</p>
            <div class="social-links">
                <a href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin"></i></a>
                <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook"></i></a>
                <a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
</section>

<!-- Courses Section Ends -->

<?php include '../componnents/footer.php'; ?>

<!-- Custom JS file link -->
<script src="../js/script_plat.js"></script>
   
</body>
</html>
