<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="/css/board-index.css" rel="stylesheet">
</head>
<body>

    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>

    <div class="header">
        <div style="display: flex; flex-direction: column; gap: 5px;">
            <a href="/projects" class="back-link">← Back to Projects</a>
            <h1>🔨 <?= htmlspecialchars($project['name']) ?></h1>
        </div>
        
        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
            <div class="btn-group">
                <button class="filter-btn active" onclick="filterTasks('all', this)">All Tasks</button>
                <button class="filter-btn" onclick="filterTasks('mine', this)">My Tasks</button>
            </div>
            
            <button class="filter-btn" onclick="toggleStats()">📊 Analytics</button>
            <a href="/profile" class="btn-action">👤 Profile</a>

            <div style="display: flex; align-items: center; background: rgba(255,255,255,0.5); padding: 5px 15px; border-radius: 12px;">
                <div class="avatar-group">
                    <?php foreach ($team as $member): ?>
                        <?php 
                            $memAvatar = $member['avatar'] ? $member['avatar'] : "https://ui-avatars.com/api/?name=".urlencode($member['username'])."&background=random&color=fff";
                        ?>
                        <img src="<?= $memAvatar ?>" title="<?= htmlspecialchars($member['username']) ?>" class="avatar-small">
                    <?php endforeach; ?>
                </div>

                <?php if ($_SESSION['user']['role'] !== 'developer'): ?>
                    <form action="/projects/add-member" method="POST" style="display:flex;">
                        <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                        <input type="email" name="email" placeholder="colleague@email.com" required class="invite-input">
                        <button type="submit" class="invite-btn">+</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="statsPanel" class="stats-container" style="display:none;">
        <div>
            <h3 style="margin:0 0 5px 0;">Project Velocity</h3>
            <p style="margin:0; color:#718096; font-size:0.9rem;">
                To Do: <b><?= $stats['todo'] ?></b> &bull; In Progress: <b><?= $stats['in_progress'] ?></b> &bull; Done: <b><?= $stats['done'] ?></b>
            </p>
        </div>
        <div class="chart-wrapper">
            <canvas id="taskChart"></canvas>
        </div>
    </div>

    <div class="board">
        <?php foreach (['todo' => '📌 To Do', 'in_progress' => '🔥 In Progress', 'done' => '✅ Done'] as $key => $label): ?>
            <div class="column" data-status="<?= $key ?>">
                <div class="column-header">
                    <span><?= $label ?></span>
                    <span style="background:rgba(0,0,0,0.05); padding:2px 8px; border-radius:10px;"><?= count($columns[$key]) ?></span>
                </div>
                
                <?php foreach ($columns[$key] as $task): ?>
                    <?php 
                        $assigneeName = null;
                        if ($task['assigned_to']) {
                            foreach ($team as $m) if ($m['id'] == $task['assigned_to']) { $assigneeName = $m['username']; break; }
                        }
                        
                        // Date Logic
                        $isOverdue = false;
                        $dateText = "";
                        if ($task['due_date']) {
                            $due = new DateTime($task['due_date']);
                            $now = new DateTime();
                            $now->setTime(0,0,0);
                            $dateText = $due->format('M d');
                            if ($due < $now && $task['status'] !== 'done') {
                                $isOverdue = true;
                                $dateText .= " (Late)";
                            }
                        }
                    ?>
                    
                    <div class="task-card priority-<?= $task['priority'] ?> <?= $isOverdue ? 'overdue' : '' ?>" 
                         draggable="true" 
                         data-id="<?= $task['id'] ?>" 
                         data-title="<?= htmlspecialchars($task['title']) ?>"
                         data-description="<?= htmlspecialchars($task['description'] ?? '') ?>"
                         data-priority="<?= $task['priority'] ?>"
                         data-assigned-to="<?= $task['assigned_to'] ?>"
                         data-due-date="<?= $task['due_date'] ?>">
                        
                        <div style="font-weight:600; margin-bottom:5px;"><?= htmlspecialchars($task['title']) ?></div>
                        
                        <div class="card-footer">
                            <div style="display:flex; gap:5px; align-items:center;">
                                <span class="badge bg-<?= $task['priority'] ?>"><?= $task['priority'] ?></span>
                                <?php if($dateText): ?>
                                    <span class="date-badge <?= $isOverdue ? 'date-overdue' : '' ?>">🕒 <?= $dateText ?></span>
                                <?php endif; ?>
                            </div>

                            <?php if ($task['assigned_to']): ?>
                                <?php 
                                    $assigneeAvatar = "";
                                    foreach ($team as $m) {
                                        if ($m['id'] == $task['assigned_to']) {
                                            $assigneeAvatar = $m['avatar'] ? $m['avatar'] : "https://ui-avatars.com/api/?name=".urlencode($m['username'])."&background=random&color=fff&size=24";
                                            break;
                                        }
                                    }
                                ?>
                                <img src="<?= $assigneeAvatar ?>" class="avatar-small">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if ($key === 'todo'): ?>
                    <form action="/tasks/store" method="POST" class="inline-form">
                        <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                        <input type="text" name="title" placeholder="New task title..." required class="inline-input">
                        <div style="display:flex; gap:5px;">
                            <input type="date" name="due_date" class="inline-input" style="flex:1">
                            <select name="priority" class="inline-input" style="flex:1">
                                <option value="low">Low</option> <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <button type="submit" class="inline-btn">Add Card</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="taskModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Task</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            
            <form action="/tasks/update" method="POST" id="editForm">
                <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                <input type="hidden" name="id" id="modalTaskId">
                
                <label>Task Title</label>
                <input type="text" name="title" id="modalTitle" required style="font-size:1.1rem; font-weight:600; margin-bottom:15px;">
                
                <div class="form-grid">
                    <div>
                        <label>Priority</label>
                        <select name="priority" id="modalPriority">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div>
                        <label>Assignee</label>
                        <select name="assigned_to" id="modalAssignee">
                            <option value="">-- Unassigned --</option>
                            <?php foreach ($team as $member): ?>
                                <option value="<?= $member['id'] ?>"><?= htmlspecialchars($member['username']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label>Due Date</label>
                        <input type="date" name="due_date" id="modalDueDate">
                    </div>
                </div>

                <label>Description</label>
                <textarea name="description" id="modalDescription" rows="3" placeholder="Add more details..."></textarea>
                
                <div class="modal-footer">
                    <?php if ($_SESSION['user']['role'] !== 'developer'): ?>
                        <button type="button" onclick="deleteTask()" style="background:transparent; color:#e53e3e; border:1px solid #e53e3e; padding:8px 16px; border-radius:8px; cursor:pointer;">Delete Task</button>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>
                    <button type="submit" class="btn-action">Save Changes</button>
                </div>
            </form>
            
            <form id="deleteForm" action="/tasks/delete" method="POST" style="display:none;"><input type="hidden" name="project_id" value="<?= $project['id'] ?>"><input type="hidden" name="id" id="deleteTaskId"></form>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-top:20px;">
                
                <div>
                    <div class="section-title">📎 Attachments</div>
                    <div id="attachmentList" style="margin-bottom:10px;"></div>
                    <button type="button" onclick="document.getElementById('fileInput').click()" style="background:#edf2f7; color:#4a5568; border:none; padding:5px 10px; border-radius:6px; cursor:pointer; font-size:0.8rem;">+ Upload File</button>
                    <input type="file" id="fileInput" style="display:none;" onchange="uploadFile()">

                    <div class="section-title">🕒 Activity Log</div>
                    <div id="activityLogList" style="max-height: 150px; overflow-y: auto; background:#f9fafb; padding:10px; border-radius:8px; border:1px solid #edf2f7;"></div>
                </div>

                <div>
                    <div class="section-title">💬 Comments</div>
                    <div id="commentsList" style="max-height: 200px; overflow-y: auto; margin-bottom:10px;"></div>
                    <div style="display:flex; gap:5px;">
                        <textarea id="newCommentText" rows="1" placeholder="Type a comment..." style="resize:none;"></textarea>
                        <button type="button" onclick="postComment()" style="background:#667eea; color:white; border:none; border-radius:8px; padding:0 15px; cursor:pointer;">➤</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const ctx = document.getElementById('taskChart');
        const chartData = {
            labels: ['To Do', 'In Progress', 'Done'],
            datasets: [{
                data: [<?= $stats['todo'] ?>, <?= $stats['in_progress'] ?>, <?= $stats['done'] ?>],
                backgroundColor: ['#cbd5e0', '#667eea', '#48bb78'],
                borderWidth: 0
            }]
        };
        const taskChart = new Chart(ctx, { 
            type: 'doughnut', 
            data: chartData,
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'right' } } }
        });

        function toggleStats() {
            const panel = document.getElementById('statsPanel');
            panel.style.display = panel.style.display === 'none' ? 'flex' : 'none';
        }

        const currentUserId = "<?= $_SESSION['user']['id'] ?>";
        const modal = document.getElementById('taskModal');
        let currentTaskId = null;

        function filterTasks(mode, btn) {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.task-card').forEach(card => {
                card.style.display = (mode === 'all' || card.getAttribute('data-assigned-to') === currentUserId) ? 'block' : 'none';
            });
        }

        document.querySelectorAll('.task-card').forEach(card => {
            card.addEventListener('dragstart', () => card.classList.add('dragging'));
            card.addEventListener('dragend', () => {
                card.classList.remove('dragging');
                fetch('/tasks/update-status', {
                    method: 'POST', headers: {'Content-Type':'application/json'},
                    body: JSON.stringify({taskId: card.getAttribute('data-id'), status: card.parentElement.getAttribute('data-status')})
                }).then(() => location.reload());
            });
            card.addEventListener('click', () => openModal(card));
        });

        document.querySelectorAll('.column').forEach(col => {
            col.addEventListener('dragover', e => { e.preventDefault(); col.classList.add('drag-over'); const drag=document.querySelector('.dragging'); if(drag) col.appendChild(drag); });
            col.addEventListener('drop', () => col.classList.remove('drag-over'));
            col.addEventListener('dragleave', () => col.classList.remove('drag-over'));
        });

        function openModal(card) {
            currentTaskId = card.getAttribute('data-id');
            document.getElementById('modalTaskId').value = currentTaskId;
            document.getElementById('deleteTaskId').value = currentTaskId;
            document.getElementById('modalTitle').value = card.getAttribute('data-title');
            document.getElementById('modalDescription').value = card.getAttribute('data-description');
            document.getElementById('modalPriority').value = card.getAttribute('data-priority');
            document.getElementById('modalAssignee').value = card.getAttribute('data-assigned-to') || "";
            document.getElementById('modalDueDate').value = card.getAttribute('data-due-date') || "";
            
            modal.style.display = "flex";
            loadComments(currentTaskId); loadAttachments(currentTaskId); loadLogs(currentTaskId);
        }
        function closeModal() { modal.style.display = "none"; }
        window.onclick = e => { if(e.target == modal) closeModal(); };
        function deleteTask() { if(confirm('Are you sure you want to delete this task?')) document.getElementById('deleteForm').submit(); }

        function loadComments(id) { fetch(`/comments?task_id=${id}`).then(r=>r.json()).then(d=>{ document.getElementById('commentsList').innerHTML=d.map(c=>`<div class="comment-item"><div style="display:flex;justify-content:space-between;font-size:0.8rem;color:#718096;margin-bottom:4px;"><b>${c.username}</b><span>${new Date(c.created_at).toLocaleDateString()}</span></div><div style="font-size:0.9rem;">${escapeHtml(c.content)}</div></div>`).join(''); }); }
        function postComment() { const t=document.getElementById('newCommentText'); if(!t.value)return; fetch('/comments/store',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({task_id:currentTaskId,content:t.value})}).then(r=>r.json()).then(d=>{if(d.success){t.value='';loadComments(currentTaskId);}}); }
        function loadAttachments(id) { fetch(`/attachments?task_id=${id}`).then(r=>r.json()).then(d=>{ document.getElementById('attachmentList').innerHTML=d.map(f=>`<div class="attachment-item"><a href="${f.filepath}" target="_blank" class="attachment-link">📄 ${escapeHtml(f.filename)}</a></div>`).join(''); }); }
        function uploadFile() { const f=document.getElementById('fileInput').files[0]; if(!f)return; const fd=new FormData(); fd.append('file',f); fd.append('task_id',currentTaskId); fetch('/attachments/upload',{method:'POST',body:fd}).then(r=>r.json()).then(d=>{if(d.success){loadAttachments(currentTaskId);loadLogs(currentTaskId);}}); }
        function loadLogs(id) { fetch(`/logs?task_id=${id}`).then(r=>r.json()).then(d=>{ document.getElementById('activityLogList').innerHTML=d.map(l=>`<div class="log-item"><b>${l.username}</b> ${escapeHtml(l.action_text)} <span style="font-size:0.75rem;color:#a0aec0;float:right;">${new Date(l.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span></div>`).join(''); }); }
        function escapeHtml(t) { return t?t.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;"):''; }
    </script>
</body>
</html>