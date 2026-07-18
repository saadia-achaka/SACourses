<?php

include '../componnents/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link 
   <link rel="stylesheet" href="css/style_plat.css"> -->

</head>
<style>
  /* Variables de couleurs */
:root {
    --primary-color: #3498db; /* Vert vif */
    --secondary-color: #e91e63; /* Jaune lumineux */
    --accent-color: #e91e63; /* Orange vif */
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
    line-height: 1.8;
    overflow-x: hidden;
}

/* Section About */
.about {
    padding: 5rem 2rem;
    background: var(--white);
}

.about .row {
    display: flex;
    align-items: center;
    gap: 3rem;
    flex-wrap: wrap-reverse;
}

.about .image {
    flex: 1;
    max-width: 500px;
    margin: 0 auto;
}

.about .image img {
    width: 100%;
    border-radius: 15px;
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.about .image img:hover {
    transform: scale(1.05);
}

.about .content {
    flex: 1.2;
    text-align: left;
}

.about .content h3 {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.about .content p {
    font-size: 1.5rem;
    color: var(--text-color);
    margin-bottom: 2rem;
}

.about .content .inline-btn {
    display: inline-block;
    background: var(--primary-color);
    color: var(--white);
    padding: 1rem 2rem;
    border-radius: 50px;
    text-transform: uppercase;
    font-weight: bold;
    text-decoration: none;
    transition: all 0.3s ease;
}

.about .content .inline-btn:hover {
    background: var(--secondary-color);
    transform: scale(1.1);
}

/* Statistiques */
.about .box-container {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 3rem;
    flex-wrap: wrap;
}

.about .box {
    flex: 1;
    min-width: 200px;
    max-width: 250px;
    text-align: center;
    padding: 1.5rem;
    border-radius: 15px;
    background: var(--primary-color);
    color: var(--white);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.about .box:hover {
    transform: translateY(-10px);
}

.about .box i {
    font-size: 3rem;
    margin-bottom: 0.5rem;
    color: var(--secondary-color);
}

.about .box h3 {
    font-size: 2.5rem;
    margin: 0.5rem 0;
}

.about .box span {
    font-size: 1.2rem;
    font-weight: lighter;
    background-color: pink;;
}

/* Section Reviews */
.reviews {
    padding: 5rem 2rem;
    background: var(--background-color);
    text-align: center;
}

.reviews .heading {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 2rem;
    font-weight: bold;
}

.reviews .box-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.reviews .box {
    background: var(--white);
    text-align: center;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.reviews .box:hover {
    transform: translateY(-10px);
    box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.2);
}

.reviews .box p {
    font-size: 1.2rem;
    color: var(--text-color);
    margin-bottom: 2rem;
    line-height: 1.8;
}

.reviews .box .user {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-top: 1rem;
}

.reviews .box .user img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--primary-color);
}

.reviews .box .user h3 {
    font-size: 1.5rem;
    color: var(--heading-color);
}

.reviews .box .user .stars i {
    color: var(--secondary-color);
    font-size: 1.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .about .row {
        flex-direction: column;
        text-align: center;
    }

    .about .content h3 {
        font-size: 2.5rem;
    }

    .about .content p {
        font-size: 1.2rem;
    }

    .about .box-container,
    .reviews .box-container {
        grid-template-columns: 1fr;
    }
}


</style>
<body>

<?php include '../componnents/user_header.php'; ?>

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <div class="image">
         <img src="..\images\about2.png" alt="">
      </div>

      <div class="content">
         <h3>Pourquoi nous choisir ?</h3>
         <p>Chez SACourses, nous croyons en l'importance de donner aux étudiants les outils nécessaires pour réussir dans un monde en constante évolution. Notre plateforme propose des leçons interactives, des conseils d'experts, et des projets pratiques pour garantir un apprentissage efficace à chaque étudiant. Que vous soyez débutant ou que vous souhaitiez perfectionner vos compétences, SACourses est votre partenaire éducatif de confiance.</p>
         <a href="cours.php" class="inline-btn">Nos cours</a>
      </div>

   </div>

   <div class="box-container" style="display: flex; justify-content: space-around; gap: 20px; flex-wrap: wrap; margin: 20px 0;">

   <div class="box" style="text-align: center; padding: 20px; border: 1px solid #ddd; border-radius: 10px; width: 200px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); animation: fadeIn 1s ease-in-out;">
      <i class="fas fa-book" style="font-size: 50px; color: #007bff; animation: bounce 2s infinite;"></i>
      <div>
         <h3 style="font-size: 24px; color: #ff69b4; animation: countUp 2s ease-out;">+1k</h3>
         <span style="font-size: 16px; color: #555;">Ressources éducatives</span>
      </div>
   </div>

   <div class="box" style="text-align: center; padding: 20px; border: 1px solid #ddd; border-radius: 10px; width: 200px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); animation: fadeIn 1.2s ease-in-out;">
      <i class="fas fa-child" style="font-size: 50px; color: #007bff; animation: bounce 2s infinite;"></i>
      <div>
         <h3 style="font-size: 24px; color: #ff69b4; animation: countUp 2s ease-out;">+15k</h3>
         <span style="font-size: 16px; color: #555;">Enfants motivés</span>
      </div>
   </div>

   <div class="box" style="text-align: center; padding: 20px; border: 1px solid #ddd; border-radius: 10px; width: 200px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); animation: fadeIn 1.4s ease-in-out;">
      <i class="fas fa-chalkboard-teacher" style="font-size: 50px; color: #007bff; animation: bounce 2s infinite;"></i>
      <div>
         <h3 style="font-size: 24px; color: #ff69b4; animation: countUp 2s ease-out;">+500</h3>
         <span style="font-size: 16px; color: #555;">Enseignants dévoués</span>
      </div>
   </div>

   <div class="box" style="text-align: center; padding: 20px; border: 1px solid #ddd; border-radius: 10px; width: 200px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); animation: fadeIn 1.6s ease-in-out;">
      <i class="fas fa-puzzle-piece" style="font-size: 50px; color: #007bff; animation: bounce 2s infinite;"></i>
      <div>
         <h3 style="font-size: 24px; color: #ff69b4; animation: countUp 2s ease-out;">+300</h3>
         <span style="font-size: 16px; color: #555;">Activités interactives</span>
      </div>
   </div>

</div>

<!-- Animations CSS -->
<style>
@keyframes fadeIn {
   from { opacity: 0; transform: translateY(20px); }
   to { opacity: 1; transform: translateY(0); }
}

@keyframes bounce {
   0%, 100% { transform: translateY(0); }
   50% { transform: translateY(-10px); }
}

@keyframes countUp {
   from { transform: scale(0.8); opacity: 0; }
   to { transform: scale(1); opacity: 1; }
}
</style>

</section>

<!-- about section ends -->

<!-- reviews section starts  -->

<section class="reviews">

   <h1 class="heading">Avis des étudiants</h1>

   <div class="box-container">

      <div class="box">
         <p>Je n'aurais jamais imaginé que l'apprentissage puisse être aussi amusant et interactif. SACourses a transformé ma façon d'aborder mes études, les rendant engageantes et gratifiantes.</p>
         <div class="user">
            <img src="../images/Hoda.png" alt="">
            <div>
               <h3>Hoda</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>

      <div class="box">
         <p>Grâce à SACourses, j'ai gagné la confiance nécessaire pour explorer le codage et la technologie. Les enseignants experts et les leçons pratiques en font la meilleure plateforme éducative.</p>
         <div class="user">
            <img src="../images/amira.jpg" alt="">
            <div>
               <h3>Amira</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>

      <div class="box">
         <p>SACourses ne se contente pas d'enseigner – il inspire à grandir. Les leçons sont pratiques, et les projets sont vraiment amusants à réaliser.</p>
         <div class="user">
            <img src="../images/amir.jpg" alt="">
            <div>
               <h3>Amir</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>




   </div>

</section>

<!-- reviews section ends -->










<?php include '../componnents/footer.php'; ?>

<!-- custom js file link  -->
<script src="../js/script_plat.js"></script>
   
</body>
</html>