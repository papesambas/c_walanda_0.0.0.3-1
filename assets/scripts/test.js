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
            z-index: 1061 !important; 
        }
        .ts-loading {
            position: relative;
        }
        .ts-loading::after {
            content: " ";
            display: block;
            width: 20px;
            height: 20px;
            border: 3px solid #3b82f6;
            border-radius: 50%;
            border-color: #3b82f6 transparent #3b82f6 transparent;
            animation: ts-spin 1.2s linear infinite;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
        .ts-error {
            color: #ff3860;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        @keyframes ts-spin {
            0% { transform: translateY(-50%) rotate(0deg); }
            100% { transform: translateY(-50%) rotate(360deg); }
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
        input.addEventListener('change', function() {
            const form = this.closest('form');
            const niveauSelect = form.querySelector('.tomselect-niveau');
            if (niveauSelect && niveauSelect.tomselect) {
                const ts = niveauSelect.tomselect;
                ts.clear();
                ts.clearOptions();

                const cycleSelect = form.querySelector('.tomselect-cycle');
                if (cycleSelect?.value) {
                    ts.load('', (options) => {
                        ts.addOptions(options);
                        ts.refreshOptions();
                        if (options.length === 1) {
                            ts.setValue(options[0].id);
                        }
                    });
                }
            }
        });
    });
});

// Fonction utilitaire pour gérer les erreurs
function handleSelectError(tsInstance, message) {
    tsInstance.wrapper.classList.add('invalid');
    
    // Supprimer les anciennes erreurs
    const existingErrors = tsInstance.wrapper.querySelectorAll('.ts-error');
    existingErrors.forEach(error => error.remove());
    
    // Ajouter le nouveau message d'erreur
    const errorEl = document.createElement('div');
    errorEl.className = 'ts-error';
    errorEl.textContent = message;
    tsInstance.wrapper.appendChild(errorEl);
}

// Fonction utilitaire pour effacer les erreurs
function clearSelectError(tsInstance) {
    tsInstance.wrapper.classList.remove('invalid');
    const errors = tsInstance.wrapper.querySelectorAll('.ts-error');
    errors.forEach(error => error.remove());
}

// Regex centralisées
const REGEX = {
    NAME: /^[\p{L}\s\-']+$/u,
    LOCATION: /^[\p{L}0-9\s\-']+$/u,
    PHONE: /^(?:\+223|00223|0)?[2567]\d{7}$/,
    NUMBER: /^\d+$/,
    NINA: /^[a-zA-ZÀ-ÿ0-9\s\-']+$/
};

// Fonction générique pour les sélecteurs simples
function initSimpleSelect(selectElement, endpoint, regex) {
    if (selectElement.tomselect) return;

    new TomSelect(selectElement, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        createFilter(input) {
            return input != null && typeof input === 'string' && input.trim() !== '';
        },
        load(query, callback) {
            if (!query) return callback();
            fetch(`${endpoint}/search?term=${encodeURIComponent(query)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.ok ? res.json() : [])
            .then(callback)
            .catch(() => callback());
        },
        create(input, callback) {
            if (input == null) {
                handleSelectError(this, 'Valeur manquante');
                return;
            }
            
            const cleanedInput = String(input).trim();
            if (!cleanedInput || !regex.test(cleanedInput)) {
                handleSelectError(this, 'Valeur invalide');
                return;
            }

            fetch(`${endpoint}/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ designation: cleanedInput })
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
        onType(str) {
            if (str && !regex.test(str)) {
                this.wrapper.classList.add('invalid');
            } else {
                clearSelectError(this);
            }
        }
    });
}

// Fonction générique pour les sélecteurs dépendants
function initDependentSelect(selectElement, dependencies, endpoint, regex) {
    if (selectElement.tomselect) return;

    new TomSelect(selectElement, {
        plugins: ['remove_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        createFilter(input) {
            return input != null && typeof input === 'string' && input.trim() !== '';
        },
        load(query, callback) {
            const form = selectElement.closest('form');
            const params = new URLSearchParams();
            
            dependencies.forEach(dep => {
                const depSelect = form.querySelector(dep.selector);
                if (depSelect?.value) params.append(dep.param, depSelect.value);
            });
            
            if (params.toString().length === 0) {
                return callback([]);
            }

            fetch(`${endpoint}/search?term=${encodeURIComponent(query)}&${params}`, {
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
            const form = selectElement.closest('form');
            const body = { designation: cleanedInput };
            let isValid = true;

            dependencies.forEach(dep => {
                const depSelect = form.querySelector(dep.selector);
                if (depSelect?.value) {
                    body[dep.param] = depSelect.value;
                } else {
                    handleSelectError(this, `Sélectionnez d'abord ${dep.name}`);
                    isValid = false;
                }
            });

            if (!isValid) return;
            
            if (!cleanedInput || !regex.test(cleanedInput)) {
                handleSelectError(this, 'Valeur invalide');
                return;
            }

            fetch(`${endpoint}/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(body)
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
        onInitialize() {
            const form = selectElement.closest('form');
            dependencies.forEach(dep => {
                const depSelect = form.querySelector(dep.selector);
                if (depSelect) {
                    depSelect.addEventListener('change', () => {
                        this.clear();
                        this.clearOptions();
                        if (dependencies.every(d => form.querySelector(d.selector)?.value)) {
                            this.load('', (options) => {
                                this.addOptions(options);
                                this.refreshOptions();
                            });
                        }
                    });
                }
            });
        }
    });
}

// Initialisations spécifiques
function initRegionSelect(select) {
    initSimpleSelect(select, '/regions', REGEX.LOCATION);
}

function initCercleSelect(select) {
    initDependentSelect(select, [
        { selector: '.tomselect-region', param: 'region_id', name: 'une région' }
    ], '/cercles', REGEX.LOCATION);
}

function initCommuneSelect(select) {
    initDependentSelect(select, [
        { selector: '.tomselect-cercle', param: 'cercle_id', name: 'un cercle' }
    ], '/communes', REGEX.LOCATION);
}

function initLieuNaissanceSelect(select) {
    initDependentSelect(select, [
        { selector: '.tomselect-commune', param: 'commune_id', name: 'une commune' }
    ], '/lieu/naissances', REGEX.LOCATION);
}

function initEnseignementSelect(select) {
    initSimpleSelect(select, '/enseignements', REGEX.NAME);
}

function initEtablissementSelect(select) {
    initDependentSelect(select, [
        { selector: '.tomselect-enseignement', param: 'enseignement_id', name: 'un enseignement' }
    ], '/etablissements', REGEX.LOCATION);
}

function initCycleSelect(select) {
    initDependentSelect(select, [
        { selector: '.tomselect-enseignement', param: 'enseignement_id', name: 'un enseignement' }
    ], '/cycles', REGEX.LOCATION);
}

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

    // Gestion des dépendances
    cycleSelect.addEventListener('change', () => {
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
    });

    dateInput.addEventListener('change', () => {
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
    });
}

function initClasseSelect(select) {
    initDependentSelect(select, [
        { selector: '.tomselect-niveau', param: 'niveau_id', name: 'un niveau' }
    ], '/classes', REGEX.LOCATION);
}

function initNomSelect(select) {
    initSimpleSelect(select, '/noms', REGEX.NAME);
}

function initPrenomSelect(select) {
    initSimpleSelect(select, '/prenoms', REGEX.NAME);
}

function initProfessionSelect(select) {
    initSimpleSelect(select, '/professions', REGEX.NAME);
}

function initTelephone1Select(select) {
    initSimpleSelect(select, '/telephones1', REGEX.PHONE);
}

function initTelephone2Select(select) {
    initSimpleSelect(select, '/telephones2', REGEX.PHONE);
}

function initNinaSelect(select) {
    initSimpleSelect(select, '/ninas', REGEX.NINA);
}

function initScolarite1Select(select) {
    initDependentSelect(select, [
        { selector: '.tomselect-niveau', param: 'niveau_id', name: 'un niveau' }
    ], '/scolarites1', REGEX.NUMBER);
}

function initScolarite2Select(select) {
    initDependentSelect(select, [
        { selector: '.tomselect-niveau', param: 'niveau_id', name: 'un niveau' },
        { selector: '.tomselect-scolarite1', param: 'scolarite1_id', name: 'une scolarité1' }
    ], '/scolarites2', REGEX.NUMBER);
}

function initRedoublement1Select(select) {
    initDependentSelect(select, [
        { selector: '.tomselect-niveau', param: 'niveau_id', name: 'un niveau' },
        { selector: '.tomselect-scolarite1', param: 'scolarite1_id', name: 'une scolarité1' },
        { selector: '.tomselect-scolarite2', param: 'scolarite2_id', name: 'une scolarité2' }
    ], '/redoublements1', REGEX.NAME);
}

function initRedoublement2Select(select) {
    initDependentSelect(select, [
        { selector: '.tomselect-niveau', param: 'niveau_id', name: 'un niveau' },
        { selector: '.tomselect-scolarite1', param: 'scolarite1_id', name: 'une scolarité1' },
        { selector: '.tomselect-scolarite2', param: 'scolarite2_id', name: 'une scolarité2' },
        { selector: '.tomselect-redoublement1', param: 'redoublement1_id', name: 'un redoublement1' }
    ], '/redoublements2', REGEX.NAME);
}

function initRedoublement3Select(select) {
    initDependentSelect(select, [
        { selector: '.tomselect-niveau', param: 'niveau_id', name: 'un niveau' },
        { selector: '.tomselect-scolarite1', param: 'scolarite1_id', name: 'une scolarité1' },
        { selector: '.tomselect-scolarite2', param: 'scolarite2_id', name: 'une scolarité2' },
        { selector: '.tomselect-redoublement2', param: 'redoublement2_id', name: 'un redoublement2' }
    ], '/redoublements3', REGEX.NAME);
}

function initStatutSelect(select) {
    if (select.tomselect) return;

    const form = select.closest('form');
    const enseignementSelect = form.querySelector('.tomselect-enseignement');
    
    if (!enseignementSelect) return;

    new TomSelect(select, {
        plugins: ['remove_button', 'clear_button'],
        maxItems: 1,
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',
        loadingClass: 'ts-loading',
        placeholder: 'Sélectionnez un statut',
        createFilter(input) {
            return input != null && typeof input === 'string' && input.trim() !== '';
        },
        load(query, callback) {
            const enseignementId = enseignementSelect.value;
            if (!enseignementId) {
                this.clearOptions();
                this.setMessage('no_results', 'Sélectionnez d\'abord un enseignement');
                return callback([]);
            }

            this.wrapper.classList.add(this.settings.loadingClass);
            
            fetch(`/statuts/search?term=${encodeURIComponent(query)}&enseignement_id=${enseignementId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.ok ? res.json() : [])
            .then(data => {
                this.wrapper.classList.remove(this.settings.loadingClass);
                callback(data);
            })
            .catch(() => {
                this.wrapper.classList.remove(this.settings.loadingClass);
                this.setMessage('no_results', 'Erreur de chargement');
                callback([]);
            });
        },
        create(input, callback) {
            if (input == null) {
                this.setMessage('no_results', 'Entrée vide');
                return;
            }
            
            const cleanedInput = String(input).trim();
            const enseignementId = enseignementSelect.value;
            
            if (!cleanedInput || !REGEX.NAME.test(cleanedInput)) {
                this.setMessage('no_results', 'Nom invalide');
                return;
            }
            
            if (!enseignementId) {
                this.setMessage('no_results', 'Sélectionnez d\'abord un enseignement');
                return;
            }

            fetch('/statuts/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    designation: cleanedInput,
                    enseignement_id: enseignementId
                })
            })
            .then(res => res.ok ? res.json() : Promise.reject('Erreur réseau'))
            .then(data => {
                callback({ id: data.id, text: cleanedInput });
            })
            .catch(err => {
                this.setMessage('no_results', 'Échec de création: ' + err);
            });
        },
        onInitialize() {
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
        }
    });
}