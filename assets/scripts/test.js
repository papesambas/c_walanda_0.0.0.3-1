import TomSelect from 'tom-select';

document.addEventListener('DOMContentLoaded', () => {
    const selectElements = document.querySelectorAll('.tomselect-nom');
    if (selectElements.length === 0) return;

    // Ajout du style global UNE SEULE FOIS
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
        new TomSelect(selectElement, {
            plugins: ['remove_button'],
            valueField: 'id',
            labelField: 'text',
            searchField: 'text',
            maxItems: 1,
            maxOptions: 10,
            
            load: function (query, callback) {
                if (!query.length) return callback();
                
                // URL en dur pour la recherche
                fetch(`/noms/search?term=${encodeURIComponent(query)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => {
                    if (!response.ok) return callback();
                    return response.json();
                })
                .then(data => callback(data))
                .catch(() => callback());
            },
            
            create: function (input, callback) {
                const cleanedInput = input.trim();

                if (!nameRegex.test(cleanedInput)) {
                    this.wrapper.classList.add('invalid');
                    return;
                }

                // URL en dur pour la création
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
                if (str && !nameRegex.test(str)) {
                    this.wrapper.classList.add('invalid');
                } else {
                    this.wrapper.classList.remove('invalid');
                }
            }
        });
    });
});