import 'jquery';
import 'select2'; // PAS de variable à importer ici

import TomSelect from 'tom-select';

// jQuery doit être global pour Select2
import $ from 'jquery';
window.$ = $;
window.jQuery = $;

document.addEventListener('DOMContentLoaded', () => {
  $(".select-nomfamille").select2({
    tags: true,
    tokenSeparators: [',', ' '],
    placeholder: 'Sélectionnez ou entrez un nom',
    allowClear: true,
    containerCssClass: 'select2-container--custom-height',
  }).on('change', function (e) {
    let label = $(this).find("[data-select2-tag=true]");
    if (label.length && $.inArray(label.val(), $(this).val()) === -1) {
      $.ajax({
        url: "/noms/ajout/ajax/" + label.val(),
        type: "POST",
      }).done(function (data) {
        label.replaceWith(`<option selected value="${data.id}">${label.val()}</option>`);
      });
    }
  });
});
