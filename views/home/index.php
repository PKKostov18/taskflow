<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow - Workflow Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="/css/home-index.css" rel="stylesheet">
</head>
<body>

    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>

    <div class="container">
        <?php if (isset($user) && $user): ?>
            <div class="welcome-back">
                <?php 
                    $avatar = $user['avatar'] ?? "https://ui-avatars.com/api/?name=".urlencode($user['username'])."&background=random&color=fff&size=128";
                ?>
                <img src="<?= $avatar ?>" class="avatar-home" alt="Avatar">
                
                <h1>Welcome back, <?= htmlspecialchars($user['username']) ?>!</h1>
                <span class="role-badge"><?= htmlspecialchars($user['role'] ?? 'User') ?></span>
                
                <p>Your workspace is ready. What would you like to achieve today?</p>
                
                <div class="btn-group">
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="/admin" class="btn btn-primary">🛡️ Go to Admin Dashboard</a>
                    <?php else: ?>
                        <a href="/projects" class="btn btn-primary">🚀 Go to Projects</a>
                    <?php endif; ?>

                    <a href="/profile" class="btn btn-outline">👤 My Profile</a>
                    <a href="/logout" class="btn btn-danger">Log Out</a>
                </div>
            </div>

        <?php else: ?>
            <div class="welcome-back">
                <div style="font-size: 3rem; margin-bottom: 10px;">⚡</div>
                <h1>TaskFlow</h1>
                <p>Orchestrate your work, collaborate with your team, and hit your deadlines. The modern way to manage projects.</p>
                
                <div class="btn-group">
                    <a href="/login" class="btn btn-primary">Log In</a>
                    <a href="/register" class="btn btn-outline">Sign Up</a>
                </div>
                
                <p style="margin-top: 30px; font-size: 0.9rem; opacity: 0.7;">
                    Trusted by developers and managers.
                </p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>