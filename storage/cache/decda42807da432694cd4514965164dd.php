<?php $this->layout = 'layout.main'; ?>

<?php $this->startSection('content'); ?>
<div class="todo-app-container">
    <!-- Header Summary Card -->
    <div class="glass-card status-summary-card">
        <div class="summary-info">
            <h1 class="page-title">Workspace Tasks</h1>
            <p class="subtitle">Streamline your productivity with nexion</p>
        </div>
        <div class="stats-container">
            <div class="stat-box">
                <span class="stat-number"><?php echo htmlspecialchars(count($todos)); ?></span>
                <span class="stat-label">Total Tasks</span>
            </div>
            <div class="stat-box">
                <span class="stat-number">
                    <?php 
                        $completedCount = 0;
                        foreach ($todos as $t) {
                            if ($t->completed) $completedCount++;
                        }
                        echo $completedCount;
                    ?>
                </span>
                <span class="stat-label">Completed</span>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    <?php if(isset($success)): ?>
    <div class="alert alert-success">
        <div class="alert-icon">✨</div>
        <div class="alert-body"><?php echo htmlspecialchars($success); ?></div>
    </div>
    <?php endif; ?>

    <?php if(!empty($errors)): ?>
    <div class="alert alert-error">
        <div class="alert-icon">⚠️</div>
        <div class="alert-body">
            <?php foreach($errors as $field => $messages): ?>
                <?php foreach($messages as $msg): ?>
                    <div><?php echo htmlspecialchars($msg); ?></div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Task Creation Section -->
    <div class="glass-card create-todo-card">
        <h2 class="section-title">Add New Task</h2>
        <form action="/todos" method="POST" class="todo-form">
            <div class="form-grid">
                <div class="form-group title-group">
                    <label for="title">What needs to be done?</label>
                    <input type="text" id="title" name="title" placeholder="e.g. Implement database migrations" value="<?php echo htmlspecialchars($old['title'] ?? ''); ?>" required autocomplete="off">
                </div>
                <div class="form-group desc-group">
                    <label for="description">Additional Details (Optional)</label>
                    <input type="text" id="description" name="description" placeholder="e.g. Set up SQLite schema and test connectivity" value="<?php echo htmlspecialchars($old['description'] ?? ''); ?>" autocomplete="off">
                </div>
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        <span class="btn-text">Create Task</span>
                        <span class="btn-icon">＋</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tasks List Section -->
    <div class="todo-list-section">
        <h2 class="section-title">Your Tasks</h2>
        
        <?php if(empty($todos)): ?>
        <div class="glass-card empty-state">
            <div class="empty-icon">☕</div>
            <h3>All caught up!</h3>
            <p>You have no pending tasks. Enjoy your coffee or add a new task above.</p>
        </div>
        <?php else: ?>
        <div class="tasks-grid">
            <?php foreach($todos as $todo): ?>
            <div class="glass-card task-card <?php if($todo->completed): ?> completed-task <?php endif; ?>">
                <div class="task-header">
                    <!-- Toggle Checkbox Form -->
                    <form action="/todos/<?php echo htmlspecialchars($todo->id); ?>/toggle" method="POST" class="toggle-form">
                        <button type="submit" class="btn-toggle" title="Toggle status">
                            <span class="checkbox-custom">
                                <?php if($todo->completed): ?>
                                <span class="checked-indicator">✓</span>
                                <?php endif; ?>
                            </span>
                        </button>
                    </form>

                    <div class="task-details">
                        <h3 class="task-title"><?php echo htmlspecialchars($todo->title); ?></h3>
                        <?php if($todo->description): ?>
                        <p class="task-description"><?php echo htmlspecialchars($todo->description); ?></p>
                        <?php endif; ?>
                        <span class="task-time">Created <?php echo htmlspecialchars(date('M d, H:i', strtotime($todo->created_at))); ?></span>
                    </div>
                </div>

                <div class="task-actions">
                    <a href="/todos/<?php echo htmlspecialchars($todo->id); ?>/edit" class="action-btn edit-btn" title="Edit Task">
                        <span>✏️</span> Edit
                    </a>
                    <form action="/todos/<?php echo htmlspecialchars($todo->id); ?>/delete" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this task?');">
                        <button type="submit" class="action-btn delete-btn" title="Delete Task">
                            <span>🗑️</span> Delete
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Styling System */
    .todo-app-container {
        display: flex;
        flex-direction: column;
        gap: 30px;
        max-width: 900px;
        margin: 0 auto;
    }

    .page-title {
        font-size: 32px;
        font-weight: 700;
        letter-spacing: -0.5px;
        margin-bottom: 4px;
        background: linear-gradient(to right, #06b6d4, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .subtitle {
        color: var(--text-muted);
        font-size: 15px;
    }

    .status-summary-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        background: rgba(255, 255, 255, 0.02);
    }

    .stats-container {
        display: flex;
        gap: 20px;
    }

    .stat-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 12px 20px;
        min-width: 100px;
    }

    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: var(--accent-primary);
    }

    .stat-label {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: #f1f5f9;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Alerts */
    .alert {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 16px 24px;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        animation: slideDown 0.3s ease-out;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.08);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #34d399;
    }

    .alert-error {
        background: rgba(244, 63, 94, 0.08);
        border: 1px solid rgba(244, 63, 94, 0.2);
        color: #fb7185;
    }

    .alert-icon {
        font-size: 20px;
    }

    .alert-body {
        font-size: 14px;
        font-weight: 500;
    }

    /* Forms */
    .todo-form .form-grid {
        display: grid;
        grid-template-columns: 2fr 2fr auto;
        align-items: flex-end;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .todo-form .form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-muted);
        letter-spacing: 0.2px;
    }

    .form-group input {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--glass-border);
        border-radius: 10px;
        padding: 12px 16px;
        color: var(--text-main);
        font-family: inherit;
        font-size: 14px;
        transition: all 0.3s;
        outline: none;
    }

    .form-group input:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--accent-primary);
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.2);
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 10px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: none;
        outline: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4), 0 0 25px rgba(59, 130, 246, 0.2);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    /* Tasks Grid */
    .tasks-grid {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .task-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--glass-border);
        gap: 20px;
    }

    .task-card:hover {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(255, 255, 255, 0.12);
        transform: translateY(-2px);
    }

    .task-header {
        display: flex;
        align-items: flex-start;
        gap: 18px;
        flex: 1;
    }

    .btn-toggle {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        outline: none;
    }

    .checkbox-custom {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 6px;
        border: 2px solid var(--glass-hover-border);
        background: rgba(255, 255, 255, 0.02);
        transition: all 0.25s;
    }

    .btn-toggle:hover .checkbox-custom {
        border-color: var(--accent-primary);
        background: rgba(6, 182, 212, 0.08);
        box-shadow: 0 0 8px rgba(6, 182, 212, 0.3);
    }

    .checked-indicator {
        color: var(--accent-primary);
        font-weight: 700;
        font-size: 14px;
        animation: scaleIn 0.15s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .task-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .task-title {
        font-size: 16px;
        font-weight: 600;
        color: #f1f5f9;
        transition: all 0.3s;
    }

    .task-description {
        font-size: 13px;
        color: var(--text-muted);
    }

    .task-time {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.3);
        margin-top: 2px;
    }

    /* Completed States */
    .completed-task {
        opacity: 0.65;
    }

    .completed-task:hover {
        opacity: 0.85;
    }

    .completed-task .task-title {
        text-decoration: line-through;
        color: var(--text-muted);
    }

    .completed-task .checkbox-custom {
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.1);
    }

    .completed-task .checked-indicator {
        color: #10b981;
    }

    /* Actions Panel */
    .task-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        border: none;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--glass-border);
        color: var(--text-main);
        cursor: pointer;
        transition: all 0.3s;
    }

    .edit-btn:hover {
        background: rgba(6, 182, 212, 0.08);
        border-color: rgba(6, 182, 212, 0.3);
        color: var(--accent-primary);
    }

    .delete-btn {
        background: rgba(244, 63, 94, 0.03);
    }

    .delete-btn:hover {
        background: rgba(244, 63, 94, 0.08);
        border-color: rgba(244, 63, 94, 0.3);
        color: #fb7185;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 50px 30px;
        background: rgba(255, 255, 255, 0.01);
    }

    .empty-icon {
        font-size: 40px;
        margin-bottom: 15px;
    }

    .empty-state h3 {
        font-size: 18px;
        color: #f1f5f9;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: var(--text-muted);
        font-size: 14px;
    }

    /* Animations */
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.7); }
        to { opacity: 1; transform: scale(1); }
    }
</style>
<?php $this->endSection(); ?>
