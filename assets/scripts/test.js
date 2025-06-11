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
    document.querySelectorAll('.tomselect-enseignement').forEach(initEnseignementSelect);
    document.querySelectorAll('.tomselect-etablissement').forEach(initEtablissementSelect);
    document.querySelectorAll('.tomselect-cycle').forEach(initCycleSelect);
    document.querySelectorAll('.tomselect-niveau').forEach(initNiveauSelect);
    document.querySelectorAll('.tomselect-classe').forEach(initClasseSelect);

    document.querySelectorAll('.tomselect-nom').forEach(initNomSelect);
    document.querySelectorAll('.tomselect-prenom').forEach(initPrenomSelect);
    document.querySelectorAll('.tomselect-profession').forEach(initProfessionSelect);
    document.querySelectorAll('.tomselect-telephone1').forEach(initTelephone1Select);
    document.querySelectorAll('.tomselect-telephone2').forEach(initTelephone2Select);
    document.querySelectorAll('.tomselect-nina').forEach(initNinaSelect);

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

function initEnseignementSelect(selectElement) {
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

            fetch(`/enseignements/search?term=${encodeURIComponent(query)}`, {
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

            fetch(`/enseignements/create/${encodeURIComponent(cleanedInput)}`, {
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

function initEtablissementSelect(etablissementSelect) {
    if (etablissementSelect.tomselect) return;

    const enseignementSelect = etablissementSelect.closest('form')?.querySelector('.tomselect-enseignement');
    const etablissementRegex = /^\p{L}+(?:[ \-']\p{L}+)*$/u;

    new TomSelect(etablissementSelect, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        load(query, callback) {
            const enseignementId = enseignementSelect?.value;
            if (!enseignementId) {
                this.clearOptions();
                return callback([]);
            }

            const url = `/etablissements/search?term=${encodeURIComponent(query)}&enseignement_id=${encodeURIComponent(enseignementId)}`;
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.ok ? res.json() : [])
                .then(callback)
                .catch(() => callback([]));
        },

        create(input, callback) {
            const cleanedInput = String(input ?? '').trim();
            const enseignementId = enseignementSelect?.value;

            if (!cleanedInput || !etablissementRegex.test(cleanedInput)) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Nom invalide");
                return;
            }

            if (!enseignementId) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Sélectionnez d'abord une région");
                return;
            }

            fetch(`/etablissements/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ designation: cleanedInput, enseignement_id: enseignementId })
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
            if (!enseignementSelect) return;

            enseignementSelect.addEventListener('change', () => {
                this.clear();
                this.clearOptions();

                if (enseignementSelect.value) {
                    this.load('', (options) => {
                        this.addOptions(options);
                        this.refreshOptions();
                    });
                }
            });
        }
    });
}

function initCycleSelect(cycleSelect) {
    if (cycleSelect.tomselect) return;

    const enseignementSelect = cycleSelect.closest('form')?.querySelector('.tomselect-enseignement');
    const cycleRegex = /^\p{L}+(?:[ \-']\p{L}+)*$/u;

    new TomSelect(cycleSelect, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        load(query, callback) {
            const enseignementId = enseignementSelect?.value;
            if (!enseignementId) {
                this.clearOptions();
                return callback([]);
            }

            const url = `/cycles/search?term=${encodeURIComponent(query)}&enseignement_id=${encodeURIComponent(enseignementId)}`;
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.ok ? res.json() : [])
                .then(callback)
                .catch(() => callback([]));
        },

        create(input, callback) {
            const cleanedInput = String(input ?? '').trim();
            const enseignementId = enseignementSelect?.value;

            if (!cleanedInput || !cycleRegex.test(cleanedInput)) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Nom invalide");
                return;
            }

            if (!enseignementId) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Sélectionnez d'abord un type d'enseignement");
                return;
            }

            fetch(`/cycles/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ designation: cleanedInput, enseignement_id: enseignementId })
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
            if (!enseignementSelect) return;

            enseignementSelect.addEventListener('change', () => {
                this.clear();
                this.clearOptions();

                if (enseignementSelect.value) {
                    this.load('', (options) => {
                        this.addOptions(options);
                        this.refreshOptions();
                    });
                }
            });
        }
    });
}

function initNiveauSelect(niveauSelect) {
    if (niveauSelect.tomselect) return;

    const cycleSelect = niveauSelect.closest('form')?.querySelector('.tomselect-cycle');
    const niveauRegex = /^\p{L}+(?:[ \-']\p{L}+)*$/u;

    new TomSelect(niveauSelect, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        load(query, callback) {
            const cycleId = cycleSelect?.value;
            if (!cycleId) {
                this.clearOptions();
                return callback([]);
            }

            const url = `/niveaux/search?term=${encodeURIComponent(query)}&cycle_id=${encodeURIComponent(cycleId)}`;
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.ok ? res.json() : [])
                .then(callback)
                .catch(() => callback([]));
        },

        create(input, callback) {
            const cleanedInput = String(input ?? '').trim();
            const cycleId = cycleSelect?.value;

            if (!cleanedInput || !niveauRegex.test(cleanedInput)) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Nom invalide");
                return;
            }

            if (!cycleId) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Sélectionnez d'abord une région");
                return;
            }

            fetch(`/niveaux/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ designation: cleanedInput, cycle_id: cycleId })
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
            if (!cycleSelect) return;

            cycleSelect.addEventListener('change', () => {
                this.clear();
                this.clearOptions();

                if (cycleSelect.value) {
                    this.load('', (options) => {
                        this.addOptions(options);
                        this.refreshOptions();
                    });
                }
            });
        }
    });
}

function initClasseSelect(classeSelect) {
    if (classeSelect.tomselect) return;

    const niveauSelect = classeSelect.closest('form')?.querySelector('.tomselect-niveau');
    const classeRegex = /^\p{L}+(?:[ \-']\p{L}+)*$/u;

    new TomSelect(classeSelect, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        load(query, callback) {
            const niveauId = niveauSelect?.value;
            if (!niveauId) {
                this.clearOptions();
                return callback([]);
            }

            const url = `/classes/search?term=${encodeURIComponent(query)}&niveau_id=${encodeURIComponent(niveauId)}`;
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.ok ? res.json() : [])
                .then(callback)
                .catch(() => callback([]));
        },

        create(input, callback) {
            const cleanedInput = String(input ?? '').trim();
            const niveauId = niveauSelect?.value;

            if (!cleanedInput || !classeRegex.test(cleanedInput)) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Nom invalide");
                return;
            }

            if (!niveauId) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Sélectionnez d'abord une région");
                return;
            }

            fetch(`/classes/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ designation: cleanedInput, niveau_id: niveauId })
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
            if (!niveauSelect) return;

            niveauSelect.addEventListener('change', () => {
                this.clear();
                this.clearOptions();

                if (niveauSelect.value) {
                    this.load('', (options) => {
                        this.addOptions(options);
                        this.refreshOptions();
                    });
                }
            });
        }
    });
}

function initNomSelect(selectElement) {
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

            fetch(`/noms/search?term=${encodeURIComponent(query)}`, {
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

            fetch(`/noms/create/${encodeURIComponent(cleanedInput)}`, {
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

function initPrenomSelect(selectElement) {
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

            fetch(`/prenoms/search?term=${encodeURIComponent(query)}`, {
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

            fetch(`/prenoms/create/${encodeURIComponent(cleanedInput)}`, {
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

function initProfessionSelect(selectElement) {
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

            fetch(`/professions/search?term=${encodeURIComponent(query)}`, {
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

            fetch(`/professions/create/${encodeURIComponent(cleanedInput)}`, {
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

function initTelephone1Select(selectElement) {
    if (selectElement.tomselect) return;

    const nameRegex = /^(?:(?:\+223|00223)[2567]\d{7}|(?:(?:\+(?!223)|00(?!223))\d{1,3}\d{6,12}))$/;

    new TomSelect(selectElement, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        load(query, callback) {
            if (!query.length) return callback();

            fetch(`/telephones1/search?term=${encodeURIComponent(query)}`, {
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

            fetch(`/telephones1/create/${encodeURIComponent(cleanedInput)}`, {
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

function initTelephone2Select(selectElement) {
    if (selectElement.tomselect) return;

    const nameRegex = /^(?:(?:\+223|00223)[2567]\d{7}|(?:(?:\+(?!223)|00(?!223))\d{1,3}\d{6,12}))$/;

    new TomSelect(selectElement, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        load(query, callback) {
            if (!query.length) return callback();

            fetch(`/telephones2/search?term=${encodeURIComponent(query)}`, {
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

            fetch(`/telephones2/create/${encodeURIComponent(cleanedInput)}`, {
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

function initNinaSelect(selectElement) {
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

            fetch(`/ninas/search?term=${encodeURIComponent(query)}`, {
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

            fetch(`/ninas/create/${encodeURIComponent(cleanedInput)}`, {
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

function initScolarite1Select(scolarite1Select) {
    if (scolarite1Select.tomselect) return;

    const niveauSelect = scolarite1Select.closest('form')?.querySelector('.tomselect-niveau');
    const scolarite1Regex = /^\d+$/;

    new TomSelect(scolarite1Select, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        load(query, callback) {
            const niveauId = niveauSelect?.value;
            if (!niveauId) {
                this.clearOptions();
                return callback([]);
            }

            const url = `/scolarites1/search?term=${encodeURIComponent(query)}&niveau_id=${encodeURIComponent(niveauId)}`;
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.ok ? res.json() : [])
                .then(callback)
                .catch(() => callback([]));
        },

        create(input, callback) {
            const cleanedInput = String(input ?? '').trim();
            const niveauId = niveauSelect?.value;

            if (!cleanedInput || !scolarite1Regex.test(cleanedInput)) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Nom invalide");
                return;
            }

            if (!niveauId) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Sélectionnez d'abord une région");
                return;
            }

            fetch(`/scolarites1/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ designation: cleanedInput, niveau_id: niveauId })
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
            if (!niveauSelect) return;

            niveauSelect.addEventListener('change', () => {
                this.clear();
                this.clearOptions();

                if (niveauSelect.value) {
                    this.load('', (options) => {
                        this.addOptions(options);
                        this.refreshOptions();
                    });
                }
            });
        }
    });
}

function initScolarite2Select(scolarite2Select) {
    if (scolarite2Select.tomselect) return;

    const form = scolarite2Select.closest('form');
    const niveauSelect = form?.querySelector('.tomselect-niveau');
    const scolarite1Select = form?.querySelector('.tomselect-scolarite1'); // Nouveau sélecteur parent
    const scolarite2Regex = /^\d+$/; // Adaptez la regex selon vos besoins

    new TomSelect(scolarite2Select, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        load(query, callback) {
            const niveauId = niveauSelect?.value;
            const scolarite1Id = scolarite1Select?.value; // Récupération du parent
            
            // Vérification des deux dépendances
            if (!niveauId || !scolarite1Id) {
                this.clearOptions();
                return callback([]);
            }

            // Modification de l'URL avec les deux paramètres
            const url = `/scolarites2/search?term=${encodeURIComponent(query)}&niveau_id=${niveauId}&scolarite1_id=${scolarite1Id}`;
            
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.ok ? res.json() : [])
                .then(callback)
                .catch(() => callback([]));
        },

        create(input, callback) {
            const cleanedInput = String(input ?? '').trim();
            const niveauId = niveauSelect?.value;
            const scolarite1Id = scolarite1Select?.value; // Récupération du parent

            // Validation des entrées
            if (!cleanedInput || !scolarite2Regex.test(cleanedInput)) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Nom invalide");
                return;
            }

            // Vérification des deux dépendances
            if (!niveauId || !scolarite1Id) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Sélectionnez d'abord une région et une scolarité1");
                return;
            }

            // Modification du payload avec les deux parents
            fetch(`/scolarites2/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ 
                    designation: cleanedInput, 
                    niveau_id: niveauId,
                    scolarite1_id: scolarite1Id // Ajout du parent
                })
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
            if (!niveauSelect || !scolarite1Select) return;

            // Gestion des changements sur les deux parents
            const parentChangeHandler = () => {
                this.clear();
                this.clearOptions();

                if (niveauSelect.value && scolarite1Select.value) {
                    this.load('', (options) => {
                        this.addOptions(options);
                        this.refreshOptions();
                    });
                }
            };

            niveauSelect.addEventListener('change', parentChangeHandler);
            scolarite1Select.addEventListener('change', parentChangeHandler); // Nouvel écouteur
        }
    });
}