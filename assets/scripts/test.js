// assets/js/enter-tomselect-navigation.js

import TomSelect from 'tom-select';

document.addEventListener('DOMContentLoaded', () => {
  // 1) Tous les formulaires père/mère
  const forms = Array.from(document.querySelectorAll('#peres_form, #meres_form'));

  // 2) Calcule une fois tous les tabindex valides
  const allTabIndexes = Array.from(document.querySelectorAll('[tabindex]'))
    .map(el => el.tabIndex)
    .filter(n => n > 0)
    .sort((a, b) => a - b)
    .filter((v, i, a) => a.indexOf(v) === i);

  console.log('Ordre des tabindex détectés :', allTabIndexes);

  forms.forEach(form => {
    // ─── TomSelect init ──────────────────────────────────────────
    ['nom','prenom','profession','telephone1','telephone2','nina']
      .forEach(prefix => {
        const sel = form.querySelector(`.tomselect-${prefix}`);
        if (!sel || sel.tomselect) return;

        const nameRegex = prefix.startsWith('telephone')
          ? /^(?:(?:\+223|00223)[2567]\d{8}|(?:\+(?!223)\d{1,3}|00(?!223)\d{1,3})\d{6,12})$/
          : prefix === 'nina'
            ? /^(?=[A-Z0-9]{13} [A-Z]$)(?!(?:[^A-Z]*[A-Z]){6,})[A-Z0-9]{13} [A-Z]$/
            : /^[\p{L}\s\-']+$/u;

        new TomSelect(sel, {
          plugins: ['remove_button'],
          delimiter: ',',
          valueField: 'id',
          labelField: 'text',
          searchField: 'text',
          maxItems: 1,

          create(input, cb) {
            const v = input.trim();
            if (!nameRegex.test(v)) {
              this.wrapper.classList.add('invalid');
              return;
            }
            fetch(`/${prefix}s/create/${encodeURIComponent(v)}`, {
              method: 'POST',
              headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
              }
            })
              .then(r => r.ok ? r.json() : Promise.reject(r.status))
              .then(data => {
                cb({ id: data.id, text: v });
                this.wrapper.classList.remove('invalid');
              })
              .catch(() => this.wrapper.classList.add('invalid'));
          },

          onInitialize() {
            this.control_input.tabIndex = this.input.tabIndex;
          }
        });
      });
    // ────────────────────────────────────────────────────────────────

    // ─── Capture du keydown Enter pour focus suivant ET blocage submit ────
    form.addEventListener('keydown', event => {
      if (event.key !== 'Enter' || event.target.matches('textarea')) return;

      // Laisser les vrais boutons submit déclencher normalement
      if (event.target.matches('button[type="submit"], input[type="submit"]')) {
        return;
      }

      const currentTab = event.target.tabIndex;
      if (currentTab > 0) {
        // Bloquer toute autre réaction (Turbo, navigateur…)
        event.preventDefault();
        event.stopImmediatePropagation();

        const idx = allTabIndexes.indexOf(currentTab);
        if (idx !== -1) {
          const nextTab = allTabIndexes[idx + 1] ?? allTabIndexes[0];
          console.log(`Entrée sur tabindex ${currentTab}, next → ${nextTab}`);

          const nextEl = form.querySelector(`[tabindex="${nextTab}"]`);
          if (nextEl) {
            nextEl.focus();
          }
        }
      }
    }, { capture: true });
    // ───────────────────────────────────────────────────────────────────
  });
});
