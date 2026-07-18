<?php
// Vérifier si l'utilisateur a accepté les termes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accept_terms'])) {
        // Rediriger vers la page d'accueil ou un autre espace
        header('Location: register.php');
        exit();
    } else {
        // Retourner une erreur si la case n'est pas cochée
        echo "<script>alert('Vous devez accepter les conditions pour continuer.');</script>";
        header('Refresh: 0; URL=terms.php');
        exit();
    }
}
?>
