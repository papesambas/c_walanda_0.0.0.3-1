import TomSelect from 'tom-select';

document.addEventListener('DOMContentLoaded', () => {
    // Styles globaux
    const style = document.createElement('style');
    style.textContent = `
        .ts-wrapper.invalid .ts-control, 
        .ts-wrapper.invalid .ts-dropdown { 
            border-color: #ff3860 !important; 
        }
        .ts-dropdown { 
            z-index: 1050 !important; 
        }
    `;
    document.head.appendChild(style);

    document.querySelectorAll('.tomselect-region').forEach(initRegionSelect);
    document.querySelectorAll('.tomselect-cercle').forEach(initCercleSelect);
    document.querySelectorAll('.tomselect-commune').forEach(initCommuneSelect);
    document.querySelectorAll('.tomselect-lieu-naissance').forEach(initLieuNaissanceSelect);
});

function initRegionSelect(selectElement) {
    if (selectElement.tomselect) return;

    const nameRegex = /^[a-zA-ZÀ-ÿ\s\-']+$/;

    new TomSelect(selectElement, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        load(query, callback) {
            if (!query.length) return callback();

            fetch(`/regions/search?term=${encodeURIComponent(query)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.ok ? res.json() : [])
                .then(callback)
                .catch(() => callback());
        },

        create(input, callback) {
            const cleanedInput = String(input ?? '').trim();
            if (!cleanedInput || !nameRegex.test(cleanedInput)) {
                this.wrapper.classList.add('invalid');
                return;
            }

            fetch(`/regions/create/${encodeURIComponent(cleanedInput)}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.ok ? res.json() : Promise.reject('Erreur réseau'))
                .then(data => {
                    callback({ id: data.id, text: cleanedInput });
                    this.wrapper.classList.remove('invalid');
                })
                .catch(err => {
                    console.error(err);
                    this.wrapper.classList.add('invalid');
                });
        },

        onItemAdd() {
            this.setTextboxValue('');
            this.refreshOptions();
        },

        onType(str) {
            if (str && !nameRegex.test(str)) {
                this.wrapper.classList.add('invalid');
            } else {
                this.wrapper.classList.remove('invalid');
            }
        }
    });
}

function initCercleSelect(cercleSelect) {
    if (cercleSelect.tomselect) return;

    const regionSelect = cercleSelect.closest('form')?.querySelector('.tomselect-region');
    const cercleRegex = /^\p{L}+(?:[ \-']\p{L}+)*$/u;

    new TomSelect(cercleSelect, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        load(query, callback) {
            const regionId = regionSelect?.value;
            if (!regionId) {
                this.clearOptions();
                return callback([]);
            }

            const url = `/cercles/search?term=${encodeURIComponent(query)}&region_id=${encodeURIComponent(regionId)}`;
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.ok ? res.json() : [])
                .then(callback)
                .catch(() => callback([]));
        },

        create(input, callback) {
            const cleanedInput = String(input ?? '').trim();
            const regionId = regionSelect?.value;

            if (!cleanedInput || !cercleRegex.test(cleanedInput)) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Nom invalide");
                return;
            }

            if (!regionId) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Sélectionnez d'abord une région");
                return;
            }

            fetch(`/cercles/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ designation: cleanedInput, region_id: regionId })
            })
                .then(res => res.ok ? res.json() : Promise.reject('Erreur réseau'))
                .then(data => {
                    callback({ id: data.id, text: data.text });
                    this.wrapper.classList.remove('invalid');
                    clearError(this.control);
                })
                .catch(err => {
                    console.error(err);
                    this.wrapper.classList.add('invalid');
                    showError(this.control, "Échec de création: " + err);
                });
        },

        onInitialize() {
            if (!regionSelect) return;

            regionSelect.addEventListener('change', () => {
                this.clear();
                this.clearOptions();

                if (regionSelect.value) {
                    this.load('', (options) => {
                        this.addOptions(options);
                        this.refreshOptions();
                    });
                }
            });
        }
    });

    function showError(control, message) {
        clearError(control);
        const errorEl = document.createElement('div');
        errorEl.className = 'text-red-500 text-xs mt-1';
        errorEl.textContent = message;
        control.parentNode.appendChild(errorEl);
    }

    function clearError(control) {
        const existingError = control.parentNode.querySelector('.text-red-500');
        if (existingError) existingError.remove();
    }
}

function initCommuneSelect(communeSelect) {
    if (communeSelect.tomselect) return;

    const cercleSelect = communeSelect.closest('form')?.querySelector('.tomselect-cercle');
    const communeRegex = /^\p{L}+(?:[ \-']\p{L}+)*$/u;

    new TomSelect(communeSelect, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        load(query, callback) {
            const cercleId = cercleSelect?.value;
            if (!cercleId) {
                this.clearOptions();
                return callback([]);
            }

            const url = `/communes/search?term=${encodeURIComponent(query)}&cercle_id=${encodeURIComponent(cercleId)}`;
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.ok ? res.json() : [])
                .then(callback)
                .catch(() => callback([]));
        },

        create(input, callback) {
            const cleanedInput = String(input ?? '').trim();
            const cercleId = cercleSelect?.value;

            if (!cleanedInput || !communeRegex.test(cleanedInput)) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Nom invalide");
                return;
            }

            if (!cercleId) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Sélectionnez d'abord une région");
                return;
            }

            fetch(`/communes/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ designation: cleanedInput, cercle_id: cercleId })
            })
                .then(res => res.ok ? res.json() : Promise.reject('Erreur réseau'))
                .then(data => {
                    callback({ id: data.id, text: data.text });
                    this.wrapper.classList.remove('invalid');
                    clearError(this.control);
                })
                .catch(err => {
                    console.error(err);
                    this.wrapper.classList.add('invalid');
                    showError(this.control, "Échec de création: " + err);
                });
        },

        onInitialize() {
            if (!cercleSelect) return;

            cercleSelect.addEventListener('change', () => {
                this.clear();
                this.clearOptions();

                if (cercleSelect.value) {
                    this.load('', (options) => {
                        this.addOptions(options);
                        this.refreshOptions();
                    });
                }
            });
        }
    });

    function showError(control, message) {
        clearError(control);
        const errorEl = document.createElement('div');
        errorEl.className = 'text-red-500 text-xs mt-1';
        errorEl.textContent = message;
        control.parentNode.appendChild(errorEl);
    }

    function clearError(control) {
        const existingError = control.parentNode.querySelector('.text-red-500');
        if (existingError) existingError.remove();
    }
}

function initLieuNaissanceSelect(LieuNaissanceSelect) {
    if (LieuNaissanceSelect.tomselect) return;

    const communeSelect = LieuNaissanceSelect.closest('form')?.querySelector('.tomselect-commune');
    const lieuRegex = /^\p{L}+(?:[ \-']\p{L}+)*$/u;

    new TomSelect(LieuNaissanceSelect, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        load(query, callback) {
            const communeId = communeSelect?.value;
            if (!communeId) {
                this.clearOptions();
                return callback([]);
            }

            const url = `/lieu/naissances/search?term=${encodeURIComponent(query)}&commune_id=${encodeURIComponent(communeId)}`;
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.ok ? res.json() : [])
                .then(callback)
                .catch(() => callback([]));
        },

        create(input, callback) {
            const cleanedInput = String(input ?? '').trim();
            const communeId = communeSelect?.value;

            if (!cleanedInput || !lieuRegex.test(cleanedInput)) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Nom invalide");
                return;
            }

            if (!communeId) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Sélectionnez d'abord une région");
                return;
            }

            fetch(`/lieu/naissances/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ designation: cleanedInput, commune_id: communeId })
            })
                .then(res => res.ok ? res.json() : Promise.reject('Erreur réseau'))
                .then(data => {
                    callback({ id: data.id, text: data.text });
                    this.wrapper.classList.remove('invalid');
                    clearError(this.control);
                })
                .catch(err => {
                    console.error(err);
                    this.wrapper.classList.add('invalid');
                    showError(this.control, "Échec de création: " + err);
                });
        },

        onInitialize() {
            if (!communeSelect) return;

            communeSelect.addEventListener('change', () => {
                this.clear();
                this.clearOptions();

                if (communeSelect.value) {
                    this.load('', (options) => {
                        this.addOptions(options);
                        this.refreshOptions();
                    });
                }
            });
        }
    });

    function showError(control, message) {
        clearError(control);
        const errorEl = document.createElement('div');
        errorEl.className = 'text-red-500 text-xs mt-1';
        errorEl.textContent = message;
        control.parentNode.appendChild(errorEl);
    }

    function clearError(control) {
        const existingError = control.parentNode.querySelector('.text-red-500');
        if (existingError) existingError.remove();
    }
}


