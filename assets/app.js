import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

// Configuration globale
import 'jquery';
import $ from 'jquery';
import TomSelect from 'tom-select';

// Export pour utilisation globale si nécessaire
window.$ = $;
window.jQuery = $;
window.TomSelect = TomSelect;

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
import './scripts/tom-select-nom.js'; // Importation du fichier JavaScript de Tom Select
import './scripts/tom-select-prenom.js'; // Importation du fichier JavaScript de Tom Select pour les prénoms
import './scripts/tom-select-profession.js'; // Importation du fichier JavaScript de Tom Select pour les professions
import './scripts/tom-select-telephone1.js'; // Importation du fichier JavaScript de Tom Select pour les numéros de téléphone
import './scripts/tom-select-telephone2.js'; // Importation du fichier JavaScript de Tom Select pour les numéros de téléphone secondaires
import './scripts/tom-select-nina.js'; // Importation du fichier JavaScript de Tom Select pour les NINA

//import './scripts/test.js'; // Importation du fichier JavaScript de test
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

