import TomSelect from 'tom-select';

document.addEventListener('DOMContentLoaded', () => {
    const selectElements = document.querySelectorAll('.tomselect-commune');
    if (!selectElements || selectElements.length === 0) return;

    // Style pour les erreurs de validation
    const style = document.createElement('style');
    style.textContent = `
        .ts-wrapper.invalid .ts-control, 
        .ts-wrapper.invalid .ts-dropdown { 
            border-color: #ff3860 !important; 
        }
    `;
    document.head.appendChild(style);

    const nameRegex = /^[a-zA-ZÀ-ÿ\s\-']+$/;

    selectElements.forEach(selectElement => {
        if (selectElement.tomselect) return;

        new TomSelect(selectElement, {
            plugins: ['remove_button'],
            delimiter: ',',
            valueField: 'id',
            labelField: 'text',
            searchField: 'text',
            maxItems: 1,
            maxOptions: 10,

            load: function (query, callback) {
                if (!query.length) return callback();

                // Récupérer l'ID du cercle parent
                const cercleId = this.input.closest('form')?.querySelector('[name*="[cercle]"]')?.value;
                
                // URL absolue
                const searchUrl = `${window.location.origin}/communes/search`;
                
                fetch(`${searchUrl}?term=${encodeURIComponent(query)}${cercleId ? `&cercle_id=${cercleId}` : ''}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => {
                    if (!response.ok) {
                        console.error('Erreur recherche', response.status);
                        return [];
                    }
                    return response.json();
                })
                .then(data => callback(data))
                .catch(error => {
                    console.error('Erreur recherche:', error);
                    callback([]);
                });
            },

            create: function (input, callback) {
                const cleanedInput = (input || '').trim();
                console.log("Tentative création commune:", cleanedInput);

                if (!cleanedInput || !nameRegex.test(cleanedInput)) {
                    console.error("Validation échouée", cleanedInput);
                    this.wrapper.classList.add('invalid');
                    return;
                }

                // Récupération du cercle parent
                const cercleInput = this.input.closest('form')?.querySelector('[name*="[cercle]"]');
                const cercleId = cercleInput?.value;
                console.log("Cercle ID trouvé:", cercleId);

                if (!cercleId) {
                    console.error('Cercle ID manquant');
                    this.wrapper.classList.add('invalid');
                    return;
                }

                // URL absolue
                const createUrl = `${window.location.origin}/communes/create`;
                
                fetch(createUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        label: cleanedInput,
                        cercle_id: cercleId
                    })
                })
                .then(response => {
                    console.log("Réponse serveur:", response.status);
                    if (!response.ok) {
                        return response.json().then(err => {
                            console.error("Erreur détaillée:", err);
                            throw new Error(err.error || 'Erreur serveur');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Création réussie:", data);
                    console.log("Commune créée:", cleanedInput);
                    callback({ id: data.id, text: cleanedInput });
                    this.wrapper.classList.remove('invalid');
                })
                .catch(error => {
                    console.error('Erreur complète:', error);
                    this.wrapper.classList.add('invalid');
                    alert(`Erreur création: ${error.message || 'Veuillez réessayer'}`);
                });
            },
            
            onItemAdd: function() {
                this.setTextboxValue('');
                this.refreshOptions();
            },
            
            onType: function(str) {
                if (str && !nameRegex.test(str)) {
                    this.wrapper.classList.add('invalid');
                } else {
                    this.wrapper.classList.remove('invalid');
                }
            },
            
            // Rendu personnalisé
            render: {
                option_create: function(data, escape) {
                    return `<div class="create">Ajouter <strong>${escape(data.input)}</strong></div>`;
                },
                no_results: function(data, escape) {
                    return `<div class="no-results">Aucun résultat pour "${escape(data.input)}"</div>`;
                }
            }
        });
    });
});