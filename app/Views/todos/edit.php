@extends('layout.main')

@section('content')
<div class="todo-edit-container">
    <!-- Back Navigation Link -->
    <div class="nav-back-wrapper">
        <a href="/" class="back-link">
            <span class="back-icon">←</span> Back to Workspace
        </a>
    </div>

    <!-- Edit Task Card -->
    <div class="glass-card edit-card">
        <h1 class="page-title">Modify Task</h1>
        <p class="subtitle">Refine the details of your task item</p>

        <!-- Alert Notifications -->
        @if(!empty($errors))
        <div class="alert alert-error">
            <div class="alert-icon">⚠️</div>
            <div class="alert-body">
                @foreach($errors as $field => $messages)
                    @foreach($messages as $msg)
                        <div>{{ $msg }}</div>
                    @endforeach
                @endforeach
            </div>
        </div>
        @endif

        <form action="/todos/{{ $todo->id }}/update" method="POST" class="edit-form">
            <div class="form-group">
                <label for="title">Task Title</label>
                <input type="text" id="title" name="title" placeholder="e.g. Write migration script" value="{{ $todo->title }}" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="description">Additional Details</label>
                <textarea id="description" name="description" rows="4" placeholder="Describe the task and any important notes here...">{{ $todo->description }}</textarea>
            </div>

            <div class="action-footer">
                <a href="/" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <span class="btn-text">Save Changes</span>
                    <span class="btn-icon">✓</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .todo-edit-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
        max-width: 600px;
        margin: 0 auto;
    }

    .nav-back-wrapper {
        display: flex;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .back-link:hover {
        color: var(--accent-primary);
        transform: translateX(-4px);
    }

    .edit-card {
        padding: 40px;
        background: rgba(255, 255, 255, 0.02);
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        letter-spacing: -0.5px;
        margin-bottom: 4px;
        background: linear-gradient(to right, #06b6d4, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .subtitle {
        color: var(--text-muted);
        font-size: 14px;
        margin-bottom: 30px;
    }

    /* Alerts */
    .alert {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 16px 24px;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        margin-bottom: 25px;
        animation: slideDown 0.3s ease-out;
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

    /* Form Fields */
    .edit-form {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        letter-spacing: 0.2px;
    }

    .form-group input,
    .form-group textarea {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--glass-border);
        border-radius: 10px;
        padding: 14px 18px;
        color: var(--text-main);
        font-family: inherit;
        font-size: 14px;
        transition: all 0.3s;
        outline: none;
        resize: vertical;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--accent-primary);
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.2);
    }

    /* Buttons */
    .action-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 16px;
        margin-top: 10px;
        border-top: 1px solid var(--glass-border);
        padding-top: 24px;
    }

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
        text-decoration: none;
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

    .btn-secondary {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--glass-border);
        color: var(--text-main);
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--glass-hover-border);
    }

    .btn-primary:active,
    .btn-secondary:active {
        transform: translateY(0);
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
