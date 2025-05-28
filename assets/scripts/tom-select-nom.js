import TomSelect from 'tom-select';

document.addEventListener('DOMContentLoaded', () => {
    const selectElement = document.querySelector('.tomselect-nom');
    if (!selectElement) return;

    // Définir la regex pour la validation des noms
    const nameRegex = /^[a-zA-ZÀ-ÿ\s\-']+$/;

    const tomselect = new TomSelect(selectElement, {
        plugins: ['remove_button'],
        delimiter: ',',
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        maxItems: 1,
        
        maxOptions: 10,
        load: function (query, callback) {
            if (!query.length) {
                return callback();
            }
            fetch(`/noms/search?term=${encodeURIComponent(query)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(response => {
                    if (!response.ok) {
                        // Si la réponse n'est pas OK, on signale un échec
                        return callback();
                    }
                    return response.json();
                })
                .then(data => {
                    // Données au format [{id, text}, …]
                    callback(data);
                })
                .catch(() => {
                    // En cas d'erreur réseau ou parsing
                    callback();
                });
        },
        create: function (input, callback) {
            const cleanedInput = input.trim();

            // Validation avec la regex
            if (!nameRegex.test(cleanedInput)) {
                this.wrapper.classList.add('invalid');
                return;
            }

            fetch(`/noms/create/${encodeURIComponent(cleanedInput)}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    callback({ id: data.id, text: cleanedInput });
                    this.wrapper.classList.remove('invalid');
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.wrapper.classList.add('invalid');
                });
        },
        onItemAdd: function () {
            this.setTextboxValue('');
            this.refreshOptions();
        },
        onType: function (str) {
            // Validation en temps réel
            if (str && !nameRegex.test(str)) {
                this.wrapper.classList.add('invalid');
            } else {
                this.wrapper.classList.remove('invalid');
            }
        },
        onInitialize: function () {
            // Ajout du style pour l'état invalide
            const style = document.createElement('style');
            style.textContent = `
                .ts-wrapper.invalid .ts-control { 
                    border-color: #ff3860 !important; 
                }
                .ts-wrapper.invalid .ts-dropdown { 
                    border-color: #ff3860 !important;
                }
            `;
            document.head.appendChild(style);
        }
    });
});

