<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'none'; script-src 'unsafe-inline'; style-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; font-src 'self' https://cdnjs.cloudflare.com; img-src 'self';">
    <title>Hors-ligne — Ecole Plus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f0fdf8 0%, #e0f7ef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .offline-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(16,185,129,0.15);
            padding: 3rem 2rem;
            text-align: center;
            max-width: 420px;
            width: 90%;
        }
        .offline-icon {
            font-size: 4rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }
        .offline-title {
            color: #1f2937;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .offline-text {
            color: #6b7280;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        .btn-retry {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            color: white;
            padding: 0.7rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-retry:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(16,185,129,0.4);
            color: white;
        }
        .cached-pages {
            margin-top: 1.5rem;
            text-align: left;
        }
        .cached-pages h6 {
            color: #374151;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }
        .cached-pages a {
            display: block;
            padding: 0.4rem 0;
            color: #10b981;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .cached-pages a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="offline-card">
        <div class="offline-icon">
            <i class="fa-solid fa-wifi-slash"></i>
        </div>
        <h1 class="offline-title">Vous êtes hors-ligne</h1>
        <p class="offline-text">
            Votre connexion internet est indisponible. 
            Certaines fonctionnalités sont temporairement inaccessibles.
        </p>
        <button class="btn btn-retry" onclick="location.reload()">
            <i class="fa-solid fa-rotate-right me-2"></i>Réessayer
        </button>

        <div class="cached-pages" id="cached-pages">
            <h6><i class="fa-solid fa-database me-1"></i>Pages disponibles hors-ligne</h6>
            <div id="pages-list"><em class="text-muted small">Chargement...</em></div>
        </div>
    </div>

    <script>
    // Afficher les pages en cache — détection dynamique du cache actif
    if ('caches' in window) {
        caches.keys().then(function(cacheNames) {
            // Utiliser le cache le plus récent (supporte les mises à jour futures)
            var activeCache = cacheNames.filter(function(n) { return n.startsWith('ecoleplus-'); }).sort().pop();
            if (!activeCache) throw new Error('Aucun cache Ecole Plus trouvé');
            return caches.open(activeCache).then(function(cache) { return cache.keys(); });
        }).then(function(requests) {
            var list = document.getElementById('pages-list');
            list.innerHTML = '';
            var pages = [];
            requests.forEach(function(req) {
                var url = new URL(req.url);
                if (url.pathname.endsWith('.php') && !url.pathname.includes('offline')) {
                    pages.push(url.pathname);
                }
            });
            if (pages.length === 0) {
                list.innerHTML = '<em class="text-muted small">Aucune page en cache</em>';
            } else {
                pages.slice(0, 5).forEach(function(p) {
                    var a = document.createElement('a');
                    a.href = p;
                    a.textContent = p.split('/').pop();
                    list.appendChild(a);
                });
            }
        }).catch(function(err) {
            console.warn('[Offline] Erreur chargement cache:', err);
            document.getElementById('pages-list').innerHTML = '<em class="text-muted small">Cache indisponible</em>';
        });
    }
    </script>
</body>
</html>
