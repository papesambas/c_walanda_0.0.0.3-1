import TomSelect from 'tom-select';

// Fonctions utilitaires pour la gestion des erreurs
function handleSelectError(tsInstance, message) {
    tsInstance.wrapper.classList.add('invalid');
    const errorEl = document.createElement('div');
    errorEl.className = 'text-red-500 text-xs mt-1';
    errorEl.textContent = message;
    tsInstance.control.parentNode.appendChild(errorEl);
}

function clearSelectError(tsInstance) {
    tsInstance.wrapper.classList.remove('invalid');
    const errorEl = tsInstance.control.parentNode.querySelector('.text-red-500');
    if (errorEl) errorEl.remove();
}

// Regex centralisées
const REGEX = {
    NAME: /^[\p{L}\s\-']+$/u,
    LOCATION: /^[\p{L}0-9\s\-']+$/u,
    PHONE: /^(?:\+223|00223|0)?[2567]\d{7}$/,
    NUMBER: /^\d+$/,
    NINA: /^[a-zA-ZÀ-ÿ0-9\s\-']+$/
};

document.addEventListener('DOMContentLoaded', () => {
    // Styles globaux
    const style = document.createElement('style');
    style.textContent = `
        .ts-wrapper.invalid .ts-control, 
        .ts-wrapper.invalid .ts-dropdown { 
            border-color: #ff3860 !important; 
        }
        .ts-dropdown { 
            z-index: 1061 !important; 
        }
    `;
    document.head.appendChild(style);

    // Liste des fonctions d'initialisation
    const initFunctions = [
        initRegionSelect, initCercleSelect, initCommuneSelect, initLieuNaissanceSelect,
        initEnseignementSelect, initEtablissementSelect, initCycleSelect, initNiveauSelect,
        initClasseSelect, initNomSelect, initPrenomSelect, initProfessionSelect,
        initTelephone1Select, initTelephone2Select, initNinaSelect, initScolarite1Select,
        initScolarite2Select, initRedoublement1Select, initRedoublement2Select,
        initRedoublement3Select, initStatutSelect
    ];

    // Sélecteurs correspondants
    const selectors = [
        '.tomselect-region', '.tomselect-cercle', '.tomselect-commune', '.tomselect-lieu-naissance',
        '.tomselect-enseignement', '.tomselect-etablissement', '.tomselect-cycle', '.tomselect-niveau',
        '.tomselect-classe', '.tomselect-nom', '.tomselect-prenom', '.tomselect-profession',
        '.tomselect-telephone1', '.tomselect-telephone2', '.tomselect-nina', '.tomselect-scolarite1',
        '.tomselect-scolarite2', '.tomselect-redoublement1', '.tomselect-redoublement2',
        '.tomselect-redoublement3', '.tomselect-statut'
    ];

    // Initialisation de tous les sélecteurs
    selectors.forEach((selector, index) => {
        document.querySelectorAll(selector).forEach(element => {
            initFunctions[index](element);
        });
    });

    // Écouteur pour les changements de date de naissance
    document.querySelectorAll('input[name*="dateNaissance"]').forEach(input => {
        input.addEventListener('change', function () {
            const form = this.closest('form');
            const niveauSelect = form.querySelector('.tomselect-niveau');
            if (niveauSelect && niveauSelect.tomselect) {
                niveauSelect.tomselect.clear();
                niveauSelect.tomselect.clearOptions();

                const cycleSelect = form.querySelector('.tomselect-cycle');
                if (cycleSelect?.value) {
                    niveauSelect.tomselect.load('', (options) => {
                        niveauSelect.tomselect.addOptions(options);
                        niveauSelect.tomselect.refreshOptions();

                        if (options.length === 1) {
                            niveauSelect.tomselect.setValue(options[0].id);
                        }
                    });
                }
            }
        });
    });
});

function initRegionSelect(selectElement) {
    if (selectElement.tomselect) return;

    const nameRegex = /^[\p{L}0-9]+(?:[ \-'][\p{L}0-9]+)*$/u;

    const tsInstance = new TomSelect(selectElement, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },

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
    const cercleRegex = /^[\p{L}0-9]+(?:[ \-'][\p{L}0-9]+)*$/u;

    const tsInstance = new TomSelect(cercleSelect, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },

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
    const communeRegex = /^[\p{L}0-9]+(?:[ \-'][\p{L}0-9]+)*$/u;

    const tsInstance = new TomSelect(communeSelect, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },

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
    const lieuRegex = /^[\p{L}0-9]+(?:[ \-'][\p{L}0-9]+)*$/u;

    const tsInstance = new TomSelect(LieuNaissanceSelect, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
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

    const tsInstance = new TomSelect(selectElement, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
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
    const etablissementRegex = /^[\p{L}0-9]+(?:[ \-'][\p{L}0-9]+)*$/u;

    const tsInstance = new TomSelect(etablissementSelect, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
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
    const cycleRegex = /^[\p{L}0-9]+(?:[ \-'][\p{L}0-9]+)*$/u;

    const tsInstance = new TomSelect(cycleSelect, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
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

function initClasseSelect(classeSelect) {
    if (classeSelect.tomselect) return;

    const niveauSelect = classeSelect.closest('form')?.querySelector('.tomselect-niveau');
    const classeRegex = /^[\p{L}0-9]+(?:[ \-'][\p{L}0-9]+)*$/u;

    const tsInstance = new TomSelect(classeSelect, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
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

    const tsInstance = new TomSelect(selectElement, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
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

    const tsInstance = new TomSelect(selectElement, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
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

    const tsInstance = new TomSelect(selectElement, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
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

    const tsInstance = new TomSelect(selectElement, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
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

    const tsInstance = new TomSelect(selectElement, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
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

    const tsInstance = new TomSelect(selectElement, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
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

    const tsInstance = new TomSelect(scolarite1Select, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
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

    const tsInstance = new TomSelect(scolarite2Select, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
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

function initRedoublement1Select(redoublement1Select) {
    if (redoublement1Select.tomselect) return;

    const form = redoublement1Select.closest('form');
    const niveauSelect = form?.querySelector('.tomselect-niveau');
    const scolarite1Select = form?.querySelector('.tomselect-scolarite1'); // Nouveau sélecteur parent
    const scolarite2Select = form?.querySelector('.tomselect-scolarite2'); // Nouveau sélecteur parent

    const tsInstance = new TomSelect(redoublement1Select, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
        load(query, callback) {
            const niveauId = niveauSelect?.value;
            const scolarite1Id = scolarite1Select?.value; // Récupération du parent
            const scolarite2Id = scolarite2Select?.value; // Récupération du parent

            // Vérification des deux dépendances
            if (!niveauId || !scolarite1Id || !scolarite2Id) {
                this.clearOptions();
                return callback([]);
            }

            // Modification de l'URL avec les deux paramètres
            const url = `/redoublements1/search?term=${encodeURIComponent(query)}&niveau_id=${niveauId}&scolarite1_id=${scolarite1Id}&scolarite2_id=${scolarite2Id}`;

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.ok ? res.json() : [])
                .then(callback)
                .catch(() => callback([]));
        },

        create(input, callback) {
            const cleanedInput = String(input ?? '').trim();
            const niveauId = niveauSelect?.value;
            const scolarite1Id = scolarite1Select?.value; // Récupération du parent
            const scolarite2Id = scolarite2Select?.value; // Récupération du parent

            // Validation des entrées
            if (!cleanedInput) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Nom invalide");
                return;
            }

            // Vérification des deux dépendances
            if (!niveauId || !scolarite1Id || !scolarite2Id) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Sélectionnez d'abord un niveau, une scolarité1 et une scolarité2");
                return;
            }

            // Modification du payload avec les deux parents
            fetch(`/redoublements1/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    designation: cleanedInput,
                    niveau_id: niveauId,
                    scolarite1_id: scolarite1Id,
                    scolarite2_id: scolarite2Id
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
            if (!niveauSelect || !scolarite1Select || !scolarite2Select) return;

            // Gestion des changements sur les deux parents
            const parentChangeHandler = () => {
                this.clear();
                this.clearOptions();

                if (niveauSelect.value && scolarite1Select.value && scolarite2Select.value) {
                    this.load('', (options) => {
                        this.addOptions(options);
                        this.refreshOptions();
                    });
                }
            };

            niveauSelect.addEventListener('change', parentChangeHandler);
            scolarite1Select.addEventListener('change', parentChangeHandler); // Nouvel écouteur
            scolarite2Select.addEventListener('change', parentChangeHandler); // Nouvel écouteur
        }
    });
}

function initRedoublement2Select(redoublement2Select) {
    if (redoublement2Select.tomselect) return;

    const form = redoublement2Select.closest('form');
    const niveauSelect = form?.querySelector('.tomselect-niveau');
    const scolarite1Select = form?.querySelector('.tomselect-scolarite1'); // Nouveau sélecteur parent
    const scolarite2Select = form?.querySelector('.tomselect-scolarite2'); // Nouveau sélecteur parent
    const redoublement1Select = form?.querySelector('.tomselect-redoublement1'); // Nouveau sélecteur parent

    const tsInstance = new TomSelect(redoublement2Select, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
        load(query, callback) {
            const niveauId = niveauSelect?.value;
            const scolarite1Id = scolarite1Select?.value; // Récupération du parent
            const scolarite2Id = scolarite2Select?.value; // Récupération du parent
            const redoublement1Id = redoublement1Select?.value; // Récupération du parent

            // Vérification des deux dépendances
            if (!niveauId || !scolarite1Id || !scolarite2Id || !redoublement1Id) {
                this.clearOptions();
                return callback([]);
            }

            // Modification de l'URL avec les deux paramètres
            const url = `/redoublements2/search?term=${encodeURIComponent(query)}&niveau_id=${niveauId}&scolarite1_id=${scolarite1Id}&scolarite2_id=${scolarite2Id}&redoublement1_id=${redoublement1Id}`;

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.ok ? res.json() : [])
                .then(callback)
                .catch(() => callback([]));
        },

        create(input, callback) {
            const cleanedInput = String(input ?? '').trim();
            const niveauId = niveauSelect?.value;
            const scolarite1Id = scolarite1Select?.value; // Récupération du parent
            const scolarite2Id = scolarite2Select?.value; // Récupération du parent
            const redoublement1Id = redoublement1Select?.value; // Récupération du parent

            // Validation des entrées
            if (!cleanedInput) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Nom invalide");
                return;
            }

            // Vérification des deux dépendances
            if (!niveauId || !scolarite1Id || !scolarite2Id || !redoublement1Id) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Sélectionnez d'abord un niveau, une scolarité1, une scolarité2 et un redoublement1");
                return;
            }

            // Modification du payload avec les deux parents
            fetch(`/redoublements2/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    designation: cleanedInput,
                    niveau_id: niveauId,
                    scolarite1_id: scolarite1Id,
                    scolarite2_id: scolarite2Id,
                    redoublement1_id: redoublement1Id
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
            if (!niveauSelect || !scolarite1Select || !scolarite2Select || !redoublement1Select) return;

            // Gestion des changements sur les deux parents
            const parentChangeHandler = () => {
                this.clear();
                this.clearOptions();

                if (niveauSelect.value && scolarite1Select.value && scolarite2Select.value && redoublement1Select.value) {
                    this.load('', (options) => {
                        this.addOptions(options);
                        this.refreshOptions();
                    });
                }
            };

            niveauSelect.addEventListener('change', parentChangeHandler);
            scolarite1Select.addEventListener('change', parentChangeHandler); // Nouvel écouteur
            scolarite2Select.addEventListener('change', parentChangeHandler); // Nouvel écouteur
            redoublement1Select.addEventListener('change', parentChangeHandler); // Nouvel écouteur
        }
    });
}

function initRedoublement3Select(redoublement3Select) {
    if (redoublement3Select.tomselect) return;

    const form = redoublement3Select.closest('form');
    const niveauSelect = form?.querySelector('.tomselect-niveau');
    const scolarite1Select = form?.querySelector('.tomselect-scolarite1'); // Nouveau sélecteur parent
    const scolarite2Select = form?.querySelector('.tomselect-scolarite2'); // Nouveau sélecteur parent
    const redoublement2Select = form?.querySelector('.tomselect-redoublement2'); // Nouveau sélecteur parent

    const tsInstance = new TomSelect(redoublement3Select, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },
        load(query, callback) {
            const niveauId = niveauSelect?.value;
            const scolarite1Id = scolarite1Select?.value; // Récupération du parent
            const scolarite2Id = scolarite2Select?.value; // Récupération du parent
            const redoublement2Id = redoublement2Select?.value; // Récupération du parent

            // Vérification des deux dépendances
            if (!niveauId || !scolarite1Id || !scolarite2Id || !redoublement2Id) {
                this.clearOptions();
                return callback([]);
            }

            // Modification de l'URL avec les deux paramètres
            const url = `/redoublements3/search?term=${encodeURIComponent(query)}&niveau_id=${niveauId}&scolarite1_id=${scolarite1Id}&scolarite2_id=${scolarite2Id}&redoublement2_id=${redoublement2Id}`;

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.ok ? res.json() : [])
                .then(callback)
                .catch(() => callback([]));
        },

        create(input, callback) {
            const cleanedInput = String(input ?? '').trim();
            const niveauId = niveauSelect?.value;
            const scolarite1Id = scolarite1Select?.value; // Récupération du parent
            const scolarite2Id = scolarite2Select?.value; // Récupération du parent
            const redoublement2Id = redoublement2Select?.value; // Récupération du parent

            // Validation des entrées
            if (!cleanedInput) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Nom invalide");
                return;
            }

            // Vérification des deux dépendances
            if (!niveauId || !scolarite1Id || !scolarite2Id || !redoublement2Id) {
                this.wrapper.classList.add('invalid');
                showError(this.control, "Sélectionnez d'abord un niveau, une scolarité1, une scolarité2 et un redoublement2");
                return;
            }

            // Modification du payload avec les deux parents
            fetch(`/redoublements3/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    designation: cleanedInput,
                    niveau_id: niveauId,
                    scolarite1_id: scolarite1Id,
                    scolarite2_id: scolarite2Id,
                    redoublement2_id: redoublement2Id
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
            if (!niveauSelect || !scolarite1Select || !scolarite2Select || !redoublement2Select) return;

            // Gestion des changements sur les deux parents
            const parentChangeHandler = () => {
                this.clear();
                this.clearOptions();

                if (niveauSelect.value && scolarite1Select.value && scolarite2Select.value && redoublement2Select.value) {
                    this.load('', (options) => {
                        this.addOptions(options);
                        this.refreshOptions();
                    });
                }
            };

            niveauSelect.addEventListener('change', parentChangeHandler);
            scolarite1Select.addEventListener('change', parentChangeHandler); // Nouvel écouteur
            scolarite2Select.addEventListener('change', parentChangeHandler); // Nouvel écouteur
            redoublement2Select.addEventListener('change', parentChangeHandler); // Nouvel écouteur
        }
    });
}

function initStatutSelect(statutSelect) {
    if (statutSelect.tomselect) return;

    const form = statutSelect.closest('form');
    const enseignementSelect = form.querySelector('.tomselect-enseignement');

    // Utilise la même regex que le serveur pour la cohérence
    const statutRegex = /^[\p{L}0-9]+(?:[ \-'\/][\p{L}0-9]+)*$/u;

    const tsInstance = new TomSelect(statutSelect, {
        plugins: ['remove_button', 'clear_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        delimiter: ',',
        loadingClass: 'loading',
        placeholder: 'Sélectionnez un statut',
        hidePlaceholder: true,

        createFilter(input) {
            // Bloque les valeurs nulles, vides ou non-string avant qu’elles ne soient transmises
            return typeof input === 'string' && input.trim() !== '';
        },

        // Ajout d'un indicateur de chargement
        loading: true,

        load: function (query, callback) {
            const enseignementId = enseignementSelect?.value;

            if (!enseignementId) {
                this.clearOptions();
                this.clear();
                this.setMessage('no_results', 'Sélectionnez d\'abord un enseignement');
                return callback([]);
            }

            const url = `/statuts/search?term=${encodeURIComponent(query)}&enseignement_id=${enseignementId}`;

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Erreur réseau');
                    return response.json();
                })
                .then(data => {
                    this.hideLoading();
                    callback(data);
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    this.hideLoading();
                    this.setMessage('no_results', 'Erreur de chargement');
                    callback([]);
                });
        },

        create: function (input, callback) {
            const cleanedInput = input.trim();
            const enseignementId = enseignementSelect?.value;

            // Validation
            if (!cleanedInput) {
                this.setMessage('no_results', 'Le nom ne peut pas être vide');
                return;
            }

            if (!statutRegex.test(cleanedInput)) {
                this.setMessage('no_results', 'Caractères invalides. Utilisez lettres, chiffres, espaces, apostrophes ou tirets.');
                return;
            }

            if (!enseignementId) {
                this.setMessage('no_results', 'Sélectionnez d\'abord un enseignement');
                return;
            }

            fetch(`/statuts/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': form.querySelector('input[name="_token"]')?.value
                },
                body: JSON.stringify({
                    designation: cleanedInput,
                    enseignement_id: enseignementId
                })
            })
                .then(response => {
                    if (!response.ok) throw new Error('Erreur création statut');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        callback({ id: data.id, text: data.designation });
                        this.clearMessage();
                    } else {
                        throw new Error(data.message || 'Erreur inconnue');
                    }
                })
                .catch(error => {
                    console.error('Erreur création:', error);
                    this.setMessage('no_results', 'Échec création: ' + error.message);
                });
        },

        onInitialize: function () {
            if (!enseignementSelect) return;

            // Chargement initial si enseignement déjà sélectionné
            if (enseignementSelect.value) {
                this.load('', (options) => {
                    this.addOptions(options);
                    this.refreshOptions();
                });
            }

            // Gestion du changement d'enseignement
            enseignementSelect.addEventListener('change', () => {
                this.clear();
                this.clearOptions();
                this.clearMessage();

                if (enseignementSelect.value) {
                    this.load('', (options) => {
                        this.addOptions(options);
                        this.refreshOptions();
                    });
                } else {
                    this.setMessage('no_results', 'Sélectionnez un enseignement');
                }
            });
        },

        // Messages personnalisés
        render: {
            no_results: function (data, escape) {
                const label = typeof data.text === 'string' ? data.text.trim() : '';
                return `<div class="no-results">${escape(this.inputValue)} - ${this.options.noResultsText}</div>`;
            }
        }
    });
}

document.querySelector('[name="eleves_form[niveau]"]').addEventListener('change', function () {
    const niveau = this.value;
    const dateField = document.querySelector('[name="dateRecrutement"]');
    const today = new Date();

    const constraints = {
        'Maternelle': { min: 0, max: 1 },
        'Petite Section': { min: 0, max: 1 },
        'Moyenne Section': { min: 0, max: 1 },
        'Grande Section': { min: 0, max: 1 },
        '1ère Année': { min: 1, max: 2 },
        '2ème Année': { min: 2, max: 4 },
        // ... autres niveaux
    };

    if (constraints[niveau]) {
        const minDate = new Date();
        minDate.setFullYear(minDate.getFullYear() - constraints[niveau].max);

        const maxDate = new Date();
        maxDate.setFullYear(maxDate.getFullYear() - constraints[niveau].min);

        dateField.min = minDate.toISOString().split('T')[0];
        dateField.max = maxDate.toISOString().split('T')[0];
    }
})

function initNiveauSelect(select) {
    if (select.tomselect) return;

    const form = select.closest('form');
    const cycleSelect = form.querySelector('.tomselect-cycle');
    const dateInput = form.querySelector('input[name*="dateNaissance"]');
    
    if (!cycleSelect || !dateInput) return;

    const ts = new TomSelect(select, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        createFilter(input) {
            return input != null && typeof input === 'string' && input.trim() !== '';
        },
        load(query, callback) {
            const params = new URLSearchParams();
            if (cycleSelect?.value) params.append('cycle_id', cycleSelect.value);
            if (dateInput?.value) params.append('date_naissance', dateInput.value);
            
            if (params.toString() === '') return callback([]);
            
            fetch(`/niveaux/search?term=${encodeURIComponent(query)}&${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.ok ? res.json() : [])
            .then(callback)
            .catch(() => callback([]));
        },
        create(input, callback) {
            if (input == null) {
                handleSelectError(this, 'Valeur manquante');
                return;
            }
            
            const cleanedInput = String(input).trim();
            if (!cleanedInput || !REGEX.LOCATION.test(cleanedInput)) {
                handleSelectError(this, 'Nom invalide');
                return;
            }

            if (!cycleSelect?.value) {
                handleSelectError(this, 'Sélectionnez un cycle');
                return;
            }

            fetch('/niveaux/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ 
                    designation: cleanedInput, 
                    cycle_id: cycleSelect.value 
                })
            })
            .then(res => res.ok ? res.json() : Promise.reject('Erreur réseau'))
            .then(data => {
                callback({ id: data.id, text: cleanedInput });
                clearSelectError(this);
            })
            .catch(err => {
                handleSelectError(this, 'Échec de création: ' + err);
            });
        },
        onItemAdd() {
            const niveauDesignation = this.options[this.getValue()]?.text || '';
            const dateField = form.querySelector('[name*="dateRecrutement"]');
            if (!dateField) return;

            const today = new Date();
            const constraints = {
                'Maternelle': [1, 1],
                'Petite Section': [1, 1],
                'Moyenne Section': [1, 1],
                'Grande Section': [1, 1],
                '1ère Année': [2, 1],
                '2ème Année': [4, 2],
                '3ème Année': [5, 3],
                '4ème Année': [6, 4],
                '5ème Année': [7, 5],
                '6ème Année': [8, 6],
                '7ème Année': [9, 7],
                '8ème Année': [10, 8],
                '9ème Année': [11, 9],
            };

            if (constraints[niveauDesignation]) {
                const [maxYears, minYears] = constraints[niveauDesignation];
                const minDate = new Date(today);
                minDate.setFullYear(today.getFullYear() - maxYears);
                const maxDate = new Date(today);
                maxDate.setFullYear(today.getFullYear() - minYears);

                dateField.min = minDate.toISOString().split('T')[0];
                dateField.max = maxDate.toISOString().split('T')[0];
            } else {
                dateField.min = '';
                dateField.max = '';
            }
        }
    });

    // Gestion unique des dépendances
    const dependencyHandler = () => {
        ts.clear();
        ts.clearOptions();
        if (cycleSelect.value && dateInput.value) {
            ts.load('', (options) => {
                ts.addOptions(options);
                ts.refreshOptions();
                if (options.length === 1) {
                    ts.setValue(options[0].id);
                }
            });
        }
    };

    cycleSelect.addEventListener('change', dependencyHandler);
    dateInput.addEventListener('change', dependencyHandler);
}