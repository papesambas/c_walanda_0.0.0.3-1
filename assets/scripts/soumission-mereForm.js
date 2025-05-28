import TomSelect from 'tom-select';

document.addEventListener('DOMContentLoaded', () => {
  // 1) On cible précisément le <form id="peres_form">
  const form = document.querySelector('#meres_form');
  if (!form) {
    // Pas de formulaire -> on arrête tout
    return;
  }

  // 2) On liste tous les champs focusables
  const focusableSelectors = `
    input:not([type=hidden]):not(:disabled),
    select:not(:disabled),
    textarea:not(:disabled),
    button:not(:disabled)
  `;
  const fields = Array.from(form.querySelectorAll(focusableSelectors));

  // 3) On intercepte Enter
  form.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' && !event.target.matches('textarea')) {
      event.preventDefault();
      const idx = fields.indexOf(event.target);
      if (idx > -1 && idx < fields.length - 1) {
        fields[idx + 1].focus();
      } else {
        // Si c'est le dernier champ, on peut aussi soumettre
        // form.submit();
      }
    }
  });
});
