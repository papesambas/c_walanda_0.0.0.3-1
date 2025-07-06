document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-add').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const collectionSelector = btn.getAttribute('data-collection');
            const collectionHolder = document.querySelector(collectionSelector);
            if (!collectionHolder) return;
            const prototype = collectionHolder.dataset.prototype;
            let index = parseInt(collectionHolder.dataset.index, 10) || 0;
            const item = document.createElement('div');
            item.classList.add('collection-item');
            item.innerHTML = prototype.replace(/__name__/g, index) +
                '<button type="button" class="btn btn-sm btn-remove"><i class="bi bi-trash"></i></button>';
            collectionHolder.appendChild(item);
            collectionHolder.dataset.index = index + 1;
        });
    });

    // Délégation pour la suppression (pas besoin de ré-attacher)
    document.body.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove')) {
            const item = e.target.closest('.collection-item');
            if (item) item.remove();
        }
    });
});