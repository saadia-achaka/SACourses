let body = document.body;

let profile = document.querySelector('.header .flex .profile');

// Bouton pour afficher/masquer le profil utilisateur
document.querySelector('#user-btn').onclick = () => {
   profile.classList.toggle('active'); // Bascule l'affichage du profil
   searchForm.classList.remove('active'); // Masque la barre de recherche si active
}

let searchForm = document.querySelector('.header .flex .search-form');

// Bouton pour afficher/masquer la barre de recherche
document.querySelector('#search-btn').onclick = () => {
   searchForm.classList.toggle('active'); // Bascule l'affichage de la recherche
   profile.classList.remove('active'); // Masque le profil utilisateur si actif
}

let sideBar = document.querySelector('.side-bar');

// Bouton pour afficher/masquer la barre latérale (menu)
document.querySelector('#menu-btn').onclick = () => {
   sideBar.classList.toggle('active'); // Bascule l'affichage de la barre latérale
   body.classList.toggle('active'); // Applique un état actif au corps (ex. empêcher le défilement)
}

// Bouton pour fermer la barre latérale
document.querySelector('.side-bar .close-side-bar').onclick = () => {
   sideBar.classList.remove('active'); // Désactive la barre latérale
   body.classList.remove('active'); // Réinitialise l'état du corps
}

// Limitation des champs numériques au nombre maximal de caractères
document.querySelectorAll('input[type="number"]').forEach(InputNumber => {
   InputNumber.oninput = () => {
      if (InputNumber.value.length > InputNumber.maxLength) {
         InputNumber.value = InputNumber.value.slice(0, InputNumber.maxLength); // Coupe les caractères excédents
      }
   }
});

// Gestion des événements de défilement
window.onscroll = () => {
   profile.classList.remove('active'); // Masque le profil utilisateur
   searchForm.classList.remove('active'); // Masque la barre de recherche

   // Désactive la barre latérale sur des écrans plus petits lors du défilement
   if (window.innerWidth < 1200) {
      sideBar.classList.remove('active');
      body.classList.remove('active');
   }
}

let toggleBtn = document.querySelector('#toggle-btn');
let darkMode = localStorage.getItem('dark-mode');

// Fonction pour activer le mode sombre
const enabelDarkMode = () => {
   toggleBtn.classList.replace('fa-sun', 'fa-moon'); // Remplace l'icône par "lune"
   body.classList.add('dark'); // Ajoute la classe pour activer le mode sombre
   localStorage.setItem('dark-mode', 'enabled'); // Enregistre l'état dans le stockage local
}

// Fonction pour désactiver le mode sombre
const disableDarkMode = () => {
   toggleBtn.classList.replace('fa-moon', 'fa-sun'); // Remplace l'icône par "soleil"
   body.classList.remove('dark'); // Supprime la classe du mode sombre
   localStorage.setItem('dark-mode', 'disabled'); // Enregistre l'état dans le stockage local
}

// Vérifie l'état du mode sombre au chargement de la page
if (darkMode === 'enabled') {
   enabelDarkMode(); // Active le mode sombre si l'état est enregistré comme activé
}

// Gestion du clic sur le bouton pour basculer le mode sombre
toggleBtn.onclick = (e) => {
   let darkMode = localStorage.getItem('dark-mode'); // Récupère l'état actuel
   if (darkMode === 'disabled') {
      enabelDarkMode(); // Active le mode sombre si désactivé
   } else {
      disableDarkMode(); // Désactive le mode sombre si activé
   }
}
