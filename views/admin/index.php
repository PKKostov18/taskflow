<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - TaskFlow</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="/css/admin-index.css" rel="stylesheet">
</head>
<body>

    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="container">
        
        <div class="header">
            <h1>🛡️ Admin Panel</h1>
            <div>
                <a href="/profile" class="nav-btn btn-profile">👤 Profile</a>
                <a href="/" class="nav-btn btn-back">Go to App</a>
                <a href="/logout" class="nav-btn btn-logout">Log Out</a>
            </div>
        </div>

        <div class="admin-grid">
            
            <div class="glass-panel">
                <h2>👥 Users Management</h2>
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <?php $av = $u['avatar'] ? $u['avatar'] : "https://ui-avatars.com/api/?name=".urlencode($u['username']); ?>
                                <img src="<?= $av ?>" class="avatar-img">
                                <b><?= htmlspecialchars($u['username']) ?></b>
                            </td>
                            <td style="color:#718096;"><?= htmlspecialchars($u['email']) ?></td>
                            <td><span class="role-badge badge-<?= $u['role'] ?>"><?= $u['role'] ?></span></td>
                            <td>
                                <?php if ($u['id'] != $_SESSION['user']['id']): ?>
                                    <div style="display:flex; gap:5px; align-items:center;">
                                        <form action="/admin/users/role" method="POST">
                                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                            <select name="role" onchange="this.form.submit()">
                                                <option value="developer" <?= $u['role']=='developer'?'selected':'' ?>>Dev</option>
                                                <option value="manager" <?= $u['role']=='manager'?'selected':'' ?>>Mgr</option>
                                                <option value="admin" <?= $u['role']=='admin'?'selected':'' ?>>Adm</option>
                                            </select>
                                        </form>
                                        <form action="/admin/users/delete" method="POST" onsubmit="return confirm('Delete user?');">
                                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                            <button type="submit" class="btn-icon" title="Delete">🗑️</button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <span style="color:#cbd5e0;">(You)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="glass-panel inbox-container">
                <h2>
                    📨 Inbox 
                    <?php if(count($messages) > 0): ?>
                        <span style="background:#e53e3e; color:white; font-size:0.7rem; padding:2px 6px; border-radius:10px;"><?= count($messages) ?></span>
                    <?php endif; ?>
                </h2>

                <?php if (empty($messages)): ?>
                    <div style="text-align:center; color:#a0aec0; padding:20px; font-size:0.9rem;">
                        <div style="font-size:2rem; margin-bottom:5px;">📭</div>
                        No new messages.
                    </div>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                        <div class="msg-card">
                            <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                                <div style="font-size:0.85rem; font-weight:bold;">
                                    <?= htmlspecialchars($msg['username']) ?>
                                    <span style="color:#718096; font-weight:normal;">(<?= $msg['role'] ?>)</span>
                                </div>
                                <small style="color:#cbd5e0; font-size:0.7rem;"><?= date('d M', strtotime($msg['created_at'])) ?></small>
                            </div>
                            
                            <p style="margin:0 0 10px 0; color:#4a5568; font-size:0.85rem; line-height:1.4;">
                                <?= nl2br(htmlspecialchars($msg['content'])) ?>
                            </p>

                            <form action="/messages/delete" method="POST" style="text-align:right;">
                                <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                                <button type="submit" style="background:none; color:#e53e3e; border:none; font-size:0.75rem; cursor:pointer; font-weight:600;">× Dismiss</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>

        <div class="glass-panel full-width">
            <h2>📂 Global Projects</h2>
            <table>
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Owner</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $p): ?>
                    <tr>
                        <td style="font-weight:600;"><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= htmlspecialchars($p['owner_name']) ?></td>
                        <td style="color:#718096;"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                        <td>
                            <form action="/admin/projects/delete" method="POST" onsubmit="return confirm('Delete project?');">
                                <input type="hidden" name="project_id" value="<?= $p['id'] ?>">
                                <button type="submit" class="btn-icon" title="Delete Project">❌</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>