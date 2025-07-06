document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[data-delete]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Récupération des données depuis les attributs
            const url = this.getAttribute('href');
            const token = this.dataset.token;
            
            if (confirm("Voulez-vous supprimer ce document ?")) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ "_token": token })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw new Error(err.error || 'Erreur serveur') });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Supprime l'élément parent (ajustez selon votre structure HTML)
                        this.closest('.document-item').remove();
                    } else {
                        alert(data.error || "Erreur lors de la suppression");
                    }
                })
                .catch(error => alert("Erreur : " + error.message));
            }
        });
    });
});

