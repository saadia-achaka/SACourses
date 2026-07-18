<?PHP include '../componnents/connect.php';
  if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
 }else{
    $user_id = '';
 }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quiz</title>
   <!-- Inclusion de Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
    /* Style global */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

/* Section des catégories de quiz */
.quiz-categories {
    padding: 50px 20px;
    text-align: center;
    background-color: #ffffff;
    border-bottom: 2px solid #ddd;
}

/* Titre de la section */
.quiz-categories h1 {
    font-size: 36px;
    color: #333;
    margin-bottom: 40px;
    font-weight: bold;
}

/* Conteneur des catégories */
.categories {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    justify-items: center;
}

/* Style des boutons des catégories */
.category-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px 25px;
    background-color: #007bff;
    color: white;
    font-size: 18px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease, transform 0.3s ease;
    min-width: 200px;
    text-align: left;
    gap: 10px;
}

/* Icônes des catégories */
.category-btn i {
    font-size: 24px;
    color: pink; /* Couleur rose pour les icônes */
}

/* Effet au survol des boutons */
.category-btn:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

/* Pour les écrans plus petits */
@media (max-width: 768px) {
    .categories {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Pour les très petits écrans */
@media (max-width: 480px) {
    .categories {
        grid-template-columns: 1fr;
    }
}
   </style>
</head>
<body>

<!-- Inclusion de la navbar -->
<?php include '../componnents/user_header.php'; ?>

<section class="quiz-categories">
   <h1>Choisissez un quiz</h1>

   <!-- Liste des catégories de quiz -->
   <div class="categories">
      <a href="quiz_dev.php" class="category-btn">
         <i class="fas fa-code"></i>
         <span>Développement</span>
      </a>
      <a href="quiz_reseau_securite.php" class="category-btn">
         <i class="fas fa-network-wired"></i>
         <span>Réseaux et Sécurité</span>
      </a>
      <a href="quiz_design.php" class="category-btn">
         <i class="fas fa-paint-brush"></i>
         <span>Design</span>
      </a>
      <a href="quiz_info.php" class="category-btn">
         <i class="fas fa-laptop"></i>
         <span>Informatique Général</span>
      </a>
      <a href="quiz_science.php" class="category-btn">
         <i class="fas fa-flask"></i>
         <span>Science et Technologie</span>
      </a>
   </div>
</section>

<!-- Inclusion du pied de page -->
<?php include '../componnents/footer.php'; ?>
<!-- Custom JS file link -->
<script src="../js/script_plat.js"></script>

</body>
</html>
