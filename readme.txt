/carnet-sanitaire-digital/
│
├── index.php                        # Page d'accueil / Dashboard
├── login.php                        # Authentification utilisateur
├── logout.php                       # Déconnexion
├── install.sql                      # Script SQL pour la base de données
│
├── /includes/
│   ├── db.php                       # Connexion à la base de données
│   ├── header.php                   # En-tête commun avec navbar + alertes légionelles
│   ├── footer.php                   # Pied de page commun
│   └── auth.php                     # Protection des pages réservées aux admins
│
├── /pages/
│   ├── fiche_intervenants.php       # Gestion des intervenants internes/externes
│   ├── fiche_installation.php       # Fiche installation (origine eau, diagnostic...)
│   ├── maintenance_hebdo.php        # Maintenance hebdomadaire
│   ├── surveillance_temperatures.php # Suivi mensuel des températures ECS
│   ├── analyse_legionelle.php        # Analyses légionelles annuelles
│   ├── graphiques.php               # Visualisation Chart.js des mesures
│   └── utilisateurs.php             # Gestion des comptes utilisateurs (admin/technicien)
│
├── /rapports/
│   └── generate_pdf.php            # Export PDF automatisé (avec TCPDF)
│
├── /assets/
│   ├── css/
│   │   └── style.css               # Styles supplémentaires si nécessaire
│   ├── js/
│   │   └── app.js                  # JS pour graphiques/alertes/pwa
│   └── icons/
│       ├── icon-192x192.png        # Icône 192px pour PWA
│       └── icon-512x512.png        # Icône 512px pour PWA
│
├── sw.js                           # Service Worker pour fonctionner hors ligne (PWA)
├── manifest.json                   # Manifeste de l'application web progressive
├── offline.html                    # Page affichée en cas de connexion perdue
│
└── README.md                       # Documentation technique (facultatif mais recommandé)