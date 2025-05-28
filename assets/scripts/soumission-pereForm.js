import TomSelect from 'tom-select';

// assets/js/enter-to-next.js

document.addEventListener('DOMContentLoaded', () => {
  // 1) Sélectionne ton formulaire
  const form = document.querySelector('#peres_form');
  if (!form) return;

  // 2) Définis les champs focusables
  const focusableSelectors = `
    input:not([type=hidden]):not(:disabled),
    select:not(:disabled),
    textarea:not(:disabled),
    button:not(:disabled)
  `;
  let fields = Array.from(form.querySelectorAll(focusableSelectors));

  // 3) Trie-les par ordre de tabindex croissant
  fields = fields
    .filter(el => el.tabIndex > 0)             // garde ceux avec tabindex positif
    .sort((a, b) => a.tabIndex - b.tabIndex);

  // 4) Sur Enter (hors textarea), passe au champ suivant
  form.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' && !event.target.matches('textarea')) {
      const idx = fields.indexOf(event.target);
      if (idx > -1) {
        event.preventDefault();
        const next = fields[idx + 1] || fields[0];
        next.focus();
      }
    }
  });
});
