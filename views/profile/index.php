<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - TaskFlow</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="/css/profile-index.css" rel="stylesheet">
</head>
<body>

    <?php
        $backLink = '/'; 
        $backText = '← Back to Home';

        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $backLink = '/admin';
            $backText = '← Back to Admin Dashboard';
        } elseif (isset($_SESSION['user'])) {
            $backLink = '/projects'; 
            $backText = '← Back to Projects';
        }
    ?>
    <a href="<?= $backLink ?>" class="back-link"><?= $backText ?></a>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="container">
        <?php 
            $avatarPath = $_SESSION['user']['avatar'] ?? null; 
            $avatarUrl = $avatarPath 
                ? $avatarPath 
                : "https://ui-avatars.com/api/?name=" . urlencode($user['username']) . "&size=120&background=random&color=fff";
        ?>
        <img src="<?= $avatarUrl ?>" alt="Avatar" class="avatar-preview">

        <h1><?= htmlspecialchars($user['username']) ?></h1>
        <span class="role-badge"><?= htmlspecialchars($user['role'] ?? 'User') ?></span>

        <div class="info-grid">
            <div class="info-item"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></div>
            <div class="info-item"><strong>Member since:</strong> <?= date('M Y') ?></div>
        </div>

        <form action="/profile/update" method="POST" enctype="multipart/form-data">
            <label>📷 Change Profile Picture</label>
            <input type="file" name="avatar" accept="image/*">

            <label>🔒 New Password <span style="font-weight:400; font-size:0.8rem; color:#718096;">(leave empty to keep current)</span></label>
            <input type="password" name="password" placeholder="••••••••">

            <button type="submit">Save Changes</button>
        </form>
    </div>

</body>
</html>