/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.{php,html,js}", // Fichiers à la racine
    "./src/app/Views/**/*.{php,html,js}", // Fichiers dans les vues de votre app
    "./admin/**/*.{php,html,js}", // Fichiers admin
    "./src/css/**/*.{css}", // Fichiers CSS si des composants sont ajoutés directement
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
