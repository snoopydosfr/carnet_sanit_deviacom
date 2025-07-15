// === Vérification de la connexion réseau ===
window.addEventListener('load', () => {
    // === Notifications d'alerte légionelle ===
    const alerts = document.querySelectorAll('.alert-legionella');
    if (alerts.length > 0) {
        alerts.forEach(alert => {
            alert.style.display = 'block';
        });
    }

    // === Mise à jour dynamique de l'année dans le footer ===
    const yearSpan = document.getElementById('current-year');
    if (yearSpan) {
        yearSpan.textContent = new Date().getFullYear();
    }

    // === Notification réseau (hors ligne) ===
    window.addEventListener('online', () => {
        const offlineAlert = document.getElementById('offline-alert');
        if (offlineAlert) offlineAlert.remove();
    });

    window.addEventListener('offline', () => {
        const container = document.querySelector('.container') || document.body;
        const alertBox = document.createElement('div');
        alertBox.id = 'offline-alert';
        alertBox.className = 'alert alert-warning text-center mt-3';
        alertBox.innerHTML = '<strong>⚠️ Mode hors ligne</strong> – Certaines fonctionnalités peuvent être limitées.';
        container.prepend(alertBox);
    });
});