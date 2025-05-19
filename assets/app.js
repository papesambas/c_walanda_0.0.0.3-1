import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

//importation de jquery
import 'jquery';
import $ from 'jquery';
// things on "window" become global variables
window.$ = $;
window.jQuery = $;

// Importations communes
import 'bootstrap/dist/css/bootstrap.min.css'; // Import du CSS de Bootstrap
import 'bootstrap'; // Import du JavaScript de Bootstrap
import '@popperjs/core'; // Import de Popper.js (requis par Bootstrap)

import * as bootstrap from 'bootstrap'; // Importation complète de Bootstrap

//import './styles/app.css';
//import './styles/scss/app.css'; // Importation du fichier SCSS principal
import './styles/app.scss';
import './scripts/register.js'; // Importation du fichier JavaScript d'enregistrement
//import './scripts/test.js'; // Importation du fichier JavaScript de test
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

