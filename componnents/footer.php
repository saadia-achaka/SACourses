<footer class="footer">
    <div class="container">
        <p>&copy; <?= date('Y'); ?> SACourses. Votre espace d'apprentissage en ligne. Tous droits réservés.</p>
        <nav class="footer-links">
            <a href="../platforme/about.php">À propos</a>
            <a href="../platforme/contact.php">Contact</a>
            <a href="../platforme/terms.php">Conditions d'utilisation</a>
            <a href="../platforme/privacy.php">Politique de confidentialité</a>
        </nav>
        <div class="social-media">
            <a href="https://facebook.com" target="_blank" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
            <a href="https://instagram.com" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="https://linkedin.com" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
        </div>
        <p>Conçu avec ❤️ par l'équipe SACourses.</p>
    </div>
</footer>
<style>
    .footer {
    background-color: #243b55;
    color: #fff;
    text-align: center;
    padding: 20px 10px;
    font-size: 14px;
}

.footer .footer-links a {
    color: #e91e63;
    margin: 0 10px;
    text-decoration: none;
    font-weight: bold;
}

.footer .footer-links a:hover {
    text-decoration: underline;
}

.footer .social-media a {
    margin: 0 5px;
    display: inline-block;
}

.footer .social-media img {
    width: 24px;
    height: 24px;
}

.footer .container {
    max-width: 1200px;
    margin: 0 auto;
}

</style>