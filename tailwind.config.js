/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.{php,html,js}", // Fichiers à la racine
    "./src/app/Views/includes/*.{php,html,js}", // Tous les fichiers dans le dossier includes
    "./src/app/Views/**/*.{php,html,js}", // Fichiers dans les vues de votre app
    "./src/admin/**/*.{php,html,js}", // Fichiers admin
    "./css/**/*.{css}", // Fichiers CSS si des composants sont ajoutés directement
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
