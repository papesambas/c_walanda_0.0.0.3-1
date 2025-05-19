import $ from 'jquery';


$(document).ready(function () {
    console.log('✅ jQuery is working!');

    // Exemple de test DOM
    const testDiv = $('<div>')
        .text('✔ jQuery is active!')
        .css({
            backgroundColor: '#d4edda',
            color: '#155724',
            padding: '10px',
            margin: '10px 0',
            border: '1px solid #c3e6cb',
            borderRadius: '4px',
        });

    $('body').prepend(testDiv);
});


import TomSelect from 'tom-select';

// Initialisation pour tous les selects avec la classe 'tom-select'
/*document.addEventListener('DOMContentLoaded', () => {
    // Configuration pour le champ portionSize
    new TomSelect('.tom-select-portionsize', {
        placeholder: 'Choisissez une taille', // Texte par défaut
        allowEmptyOption: false,            // Désactive l'option vide si required
        maxItems: 1,                        // Limite à 1 choix
        closeAfterSelect: true,              // Ferme le dropdown après sélection

        // Personnalisation du rendu des options (ex: icônes, styles)
        render: {
            option: function (data, escape) {
                return `
                    <div class="option-item">
                        <span class="portion-${data.value}">${escape(data.text)}</span>
                    </div>
                `;
            },
            item: function (data, escape) {
                return `<div class="item-selected">${escape(data.text)}</div>`;
            }
        },

        // Callbacks (ex: après sélection)
        onInitialize: function () {
            console.log('TomSelect portionSize initialisé !');
        }
    });
});*/

// Configuration de base pour TOUS les TomSelect
const globalTomSelectConfig = {
    placeholder: 'Choisissez une taille',
    allowEmptyOption: false,
    maxItems: 2,
    closeAfterSelect: true,
    // Ajoutez ces options pour permettre la suppression
    plugins: ['remove_button'], // Active les boutons de suppression
    onDelete: function(values) {
        return confirm('Voulez-vous vraiment supprimer ce choix ?');
    },
    render: {
        option: function(data) {
            return `<div class="tom-option">${data.text}</div>`;
        },
        item: function(data, escape) {
            return `<div class="tom-selected-item">${escape(data.text)} 
                    `;
        }
    }
};

// Initialisation
document.querySelectorAll('.tom-select').forEach(select => {
    const config = {...globalTomSelectConfig};
    
    if (select.dataset.tomselect === 'portionSize') {
        config.maxItems = 2; // Pour le champ portionSize, on limite à 1 choix
        config.placeholder = 'Choisissez une taille de portion';
        config.render.option = function(data) {
            return `<div class="portion-option portion-${data.value}">${data.text}</div>`;
        };
    }

    new TomSelect(select, config);
});