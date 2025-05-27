import TomSelect from 'tom-select';

document.addEventListener('DOMContentLoaded', () => {
    const selectElement = document.querySelector('.tomselect-prenom');
    if (!selectElement) return;

    // Définir la regex pour la validation des prenoms
    const nameRegex = /^[a-zA-ZÀ-ÿ\s\-']+$/;

    const tomselect = new TomSelect(selectElement, {
        plugins: ['remove_button'],
        delimiter: ',',
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        maxItems: 1,
        create: function (input, callback) {
            const cleanedInput = input.trim();

            // Validation avec la regex
            if (!nameRegex.test(cleanedInput)) {
                this.wrapper.classList.add('invalid');
                return;
            }

            fetch(`/prenoms/create/${encodeURIComponent(cleanedInput)}`, {
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

