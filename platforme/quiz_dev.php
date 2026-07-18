<?PHP include '../componnents/connect.php';
  if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
 }else{
    $user_id = '';
 }

// Récupérer les questions et réponses pour la catégorie "Développement"
$category_id = 1; // ID de la catégorie Développement
$questions_query = $conn->prepare("SELECT q.id as question_id, q.question_text, a.id as answer_id, a.answer_text 
    FROM quiz_questions q 
    JOIN quiz_answers a ON q.id = a.question_id 
    WHERE q.category_id = ? ORDER BY q.id");
$questions_query->execute([$category_id]);

// Organiser les questions et réponses
$questions = [];
while ($row = $questions_query->fetch(PDO::FETCH_ASSOC)) {
    $questions[$row['question_id']]['question_text'] = $row['question_text'];
    $questions[$row['question_id']]['answers'][$row['answer_id']] = $row['answer_text'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - Développement</title>
    <link rel="stylesheet" href="../css/quiz_style.css">
</head>
<body>
<?php include '../componnents/user_header.php'; ?>

<section class="quiz">
    <h1>Quiz sur le Développement</h1>
    <form action="submit_quiz.php" method="POST">
        <?php foreach ($questions as $question_id => $question): ?>
            <div class="question-block">
                <h3><?= htmlspecialchars($question['question_text']); ?></h3>
                <?php foreach ($question['answers'] as $answer_id => $answer_text): ?>
                    <label>
                        <input type="radio" name="answers[<?= $question_id; ?>]" value="<?= $answer_id; ?>">
                        <?= htmlspecialchars($answer_text); ?>
                    </label><br>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        <input type="submit" value="Soumettre">
    </form>
</section>
<?php include '../componnents/footer.php'; ?>
</body>
</html>
