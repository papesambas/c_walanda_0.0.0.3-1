import TomSelect from 'tom-select';

document.addEventListener('DOMContentLoaded', () => {
    const selectElements = document.querySelectorAll('.tomselect-cercle');
    if (!selectElements) return;

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

                const regionId = this.input.closest('form')?.querySelector('[name*="[region]"]')?.value;
                
                // URL ABSOLUE
                const searchUrl = `${window.location.origin}/cercles/search`;
                
                fetch(`${searchUrl}?term=${encodeURIComponent(query)}${regionId ? `&region_id=${regionId}` : ''}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => {
                    if (!response.ok) {
                        console.error('Search error', response.status);
                        return [];
                    }
                    return response.json();
                })
                .then(data => callback(data))
                .catch(() => callback());
            },

            create: function (input, callback) {
                const cleanedInput = (input || '').trim();
                console.log("Tentative de création:", cleanedInput);

                if (!cleanedInput || !nameRegex.test(cleanedInput)) {
                    console.error("Validation échouée", cleanedInput);
                    this.wrapper.classList.add('invalid');
                    return;
                }

                const regionInput = this.input.closest('form')?.querySelector('[name*="[region]"]');
                const regionId = regionInput?.value;
                console.log("Region ID trouvé:", regionId);

                if (!regionId) {
                    console.error('Region ID manquant');
                    this.wrapper.classList.add('invalid');
                    return;
                }

                // URL ABSOLUE
                const createUrl = `${window.location.origin}/cercles/create`;
                
                fetch(createUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        label: cleanedInput,
                        region_id: regionId
                    })
                })
                .then(response => {
                    console.log("Réponse du serveur:", response.status);
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
                    console.log("Cercle créé:", cleanedInput); // Affiche le label
                    callback({ id: data.id, text: cleanedInput });
                    this.wrapper.classList.remove('invalid');
                })
                .catch(error => {
                    console.error('Erreur complète:', error);
                    this.wrapper.classList.add('invalid');
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
            }
        });
    });
});