<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Projects' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="/css/projects-index.css" rel="stylesheet">
</head>
<body>

    <div class="header">
        <h1>📂 My Projects</h1>
        <div>
            <button onclick="openMsgModal()" class="btn" style="background: white; color: #5a67d8; margin-right: 10px; cursor:pointer; border:none; font-weight:600;">📞 Contact Admin</button>

            <?php if ($_SESSION['user']['role'] !== 'developer'): ?>
                <a href="/projects/create" class="btn btn-primary">+ New Project</a>
            <?php endif; ?>

            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <a href="/admin" class="btn" style="background: #e53e3e; color: white; margin-right: 10px;">🛡️ Admin Panel</a>
            <?php endif; ?>
            
            <a href="/" class="btn btn-outline">Home</a>
        </div>
    </div>

    <div class="project-grid">
        <?php if (empty($projects)): ?>
            <div class="empty-state">
                <div style="font-size: 3rem; margin-bottom: 10px;">✨</div>
                <h2>No projects yet</h2>
                <p>Create your first project to get started with TaskFlow.</p>
            </div>
        <?php else: ?>
            <?php foreach ($projects as $project): ?>
                <div class="project-card">
                    <div>
                        <h3><?= htmlspecialchars($project['name']) ?></h3>
                        <p><?= htmlspecialchars($project['description'] ?: 'No description provided.') ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="/board?id=<?= $project['id'] ?>" class="btn-open">Open Board →</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="msgModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); backdrop-filter:blur(5px); justify-content:center; align-items:center; z-index:1000;">
        <div style="background:white; padding:30px; border-radius:20px; width:400px; text-align:center; box-shadow:0 20px 50px rgba(0,0,0,0.2);">
            <h2 style="margin-top:0; color:#2d3748;">📬 Contact Admin</h2>
            <p style="color:#718096; font-size:0.9rem; margin-bottom:20px;">Need a role upgrade or have a question?</p>

            <form action="/messages/store" method="POST">
                <textarea name="content" rows="5" required placeholder="Hello Admin, please upgrade me to Manager..." style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:10px; font-family:inherit; resize:none; margin-bottom:20px;"></textarea>

                <div style="display:flex; justify-content:space-between;">
                    <button type="button" onclick="closeMsgModal()" style="background:transparent; color:#718096; border:none; cursor:pointer;">Cancel</button>
                    <button type="submit" style="background:#667eea; color:white; border:none; padding:10px 20px; border-radius:8px; cursor:pointer; font-weight:bold;">Send Message</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openMsgModal() { document.getElementById('msgModal').style.display = 'flex'; }
        function closeMsgModal() { document.getElementById('msgModal').style.display = 'none'; }
    </script>
</body>
</html>