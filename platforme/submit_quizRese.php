<?PHP include '../componnents/connect.php';
  if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
 }else{
    $user_id = '';
 }

// ID de la catégorie "Réseau et Sécurité"
$category_id = 2;

// Récupération des réponses soumises par l'utilisateur
$user_answers = $_POST['answers'] ?? [];

// Récupérer les questions correctes et leur texte pour la catégorie sélectionnée
$correct_answers_query = $conn->prepare("
    SELECT id, question_text, correct_answer_id 
    FROM quiz_questions 
    WHERE category_id = :category_id
");
$correct_answers_query->execute(['category_id' => $category_id]);
$correct_answers = [];
while ($row = $correct_answers_query->fetch(PDO::FETCH_ASSOC)) {
    $correct_answers[$row['id']] = [
        'correct_answer_id' => $row['correct_answer_id'],
        'question_text' => $row['question_text']
    ];
}

// Récupérer les réponses possibles pour les questions de la catégorie
$answers_query = $conn->prepare("
    SELECT id, question_id, answer_text 
    FROM quiz_answers 
    WHERE question_id IN (SELECT id FROM quiz_questions WHERE category_id = :category_id)
");
$answers_query->execute(['category_id' => $category_id]);
$answers = [];
while ($row = $answers_query->fetch(PDO::FETCH_ASSOC)) {
    $answers[$row['question_id']][] = [
        'id' => $row['id'],
        'answer_text' => $row['answer_text']
    ];
}

// Calculer le score
$total_questions = count($correct_answers);
$correct_count = 0;
foreach ($correct_answers as $question_id => $data) {
    $user_answer = isset($user_answers[$question_id]) ? (int)$user_answers[$question_id] : null;

    // Vérifier si la réponse de l'utilisateur correspond à la réponse correcte
    $is_correct = $user_answer == $data['correct_answer_id'];

    if ($is_correct) {
        $correct_count++;
    }
}

// Calcul du pourcentage
$score_percentage = ($total_questions > 0) ? ($correct_count / $total_questions) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats du Quiz</title>
    <link rel="stylesheet" href="../css/quiz_style.css">
</head>
<body>
<?php include '../componnents/user_header.php'; ?>

<section class="quiz-results">
    <h1>Résultats du Quiz</h1>

    <!-- Affichage du score -->
    <div class="score">
        <h2>Votre Score</h2>
        <p><?= $correct_count; ?> / <?= $total_questions; ?> (<?= round($score_percentage, 2); ?>%)</p>
    </div>

    <!-- Affichage des questions avec réponses -->
    <div class="answers-section">
        <h2>Correction des Questions</h2>
        <?php foreach ($correct_answers as $question_id => $data): ?>
            <?php
            $user_answer = isset($user_answers[$question_id]) ? (int)$user_answers[$question_id] : null;
            $is_correct = $user_answer == $data['correct_answer_id'];
            ?>
            <div class="question-block">
                <h3><?= $data['question_text']; ?></h3>

                <?php 
                    // Récupérer les réponses possibles pour cette question
                    $possible_answers = $answers[$question_id] ?? [];
                    $user_answer_text = null;
                    foreach ($possible_answers as $answer) {
                        if ($answer['id'] == $user_answer) {
                            $user_answer_text = $answer['answer_text'];
                            break;
                        }
                    }

                    // Trouver le texte de la bonne réponse
                    $correct_answer_text = null;
                    foreach ($possible_answers as $answer) {
                        if ($answer['id'] == $data['correct_answer_id']) {
                            $correct_answer_text = $answer['answer_text'];
                            break;
                        }
                    }
                ?>

                <p class="<?= $is_correct ? 'correct' : 'incorrect'; ?>">
                    Votre réponse : <?= $user_answer_text ?? 'Non répondue'; ?>
                </p>
                <p class="correct-answer">
                    Réponse correcte : <?= $correct_answer_text; ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include '../componnents/footer.php'; ?>
</body>
</html>
