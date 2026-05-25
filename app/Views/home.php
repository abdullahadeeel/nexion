@extends('layout.main')

@section('content')
<style>
    .hero {
        text-align: center;
        margin-bottom: 50px;
    }
    
    .hero h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 16px;
        background: linear-gradient(135deg, #fff 0%, #cbd5e1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -1px;
    }
    
    .hero p {
        font-size: 18px;
        color: var(--text-muted);
        max-width: 600px;
        margin: 0 auto;
    }
    
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 50px;
    }
    
    .card-title {
        font-size: 18px;
        font-weight: 600;
        color: #fff;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .card-title .icon {
        color: var(--accent-primary);
        font-size: 20px;
    }
    
    .card-body {
        color: var(--text-muted);
        font-size: 14px;
    }
    
    .status-panel {
        display: flex;
        flex-direction: column;
        gap: 16px;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 50px;
    }
    
    .status-title {
        font-size: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }
    
    .status-indicator.success {
        background-color: #10b981;
        box-shadow: 0 0 10px #10b981;
    }
    
    .status-indicator.warning {
        background-color: #f59e0b;
        box-shadow: 0 0 10px #f59e0b;
    }
    
    .links-section {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    .links-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    .links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
    
    .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: #fff;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s;
        backdrop-filter: blur(12px);
    }
    
    .btn:hover {
        background: rgba(6, 182, 212, 0.1);
        border-color: var(--accent-primary);
        color: var(--accent-primary);
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.2);
    }
</style>

<div class="hero">
    <h1>{{ $title }}</h1>
    <p>A full-fledged, extremely organized MVC framework inspired by Laravel. Engineered with a powerful router, input validation, active record, error handler, and dotenv config.</p>
</div>

<div class="status-panel">
    <div class="status-title">
        <span class="status-indicator success"></span> System Bootstrap Status: Fully Functional
    </div>
    <div class="card-body">
        Environment: <code>{{ env('APP_ENV', 'local') }}</code> | Debug Mode: <code>{{ env('APP_DEBUG', 'true') ? 'Enabled' : 'Disabled' }}</code>
    </div>
</div>

<div class="links-section">
    <h3 class="links-title">Interact & Test Framework Features</h3>
    <div class="links-grid">
        <a href="/" class="btn">🏠 Home Dashboard</a>
        <a href="/user/42" class="btn">👤 Test ORM Dynamic Route</a>
        <a href="/admin" class="btn">🔒 Test Admin Middleware (Block)</a>
        <a href="/admin?auth=1" class="btn">🔓 Test Admin Middleware (Allow)</a>
    </div>
</div>

<div style="margin-top: 50px;">
    <h3 class="links-title">Core Engine Enhancements</h3>
    <div class="grid">
        <div class="glass-card">
            <div class="card-title"><span class="icon">🛣️</span> Advanced Router</div>
            <div class="card-body">Handles GET/POST/PUT/PATCH/DELETE/OPTIONS, nested Route Grouping with shared prefixes & middleware stack inheritance, and named routes with dynamic parameter resolving.</div>
        </div>
        <div class="glass-card">
            <div class="card-title"><span class="icon">🔒</span> Request Validation</div>
            <div class="card-body">Enables fully-structured input sanitation and validation rules (e.g. <code>required</code>, <code>email</code>, <code>numeric</code>, <code>min</code>, <code>max</code>), automatically responding with 422 JSON errors for REST API clients.</div>
        </div>
        <div class="glass-card">
            <div class="card-title"><span class="icon">🗄️</span> ORM & Query Builder</div>
            <div class="card-body">Fluent, secure Query Builder supporting chainable constraints (<code>where</code>, <code>orderBy</code>, <code>limit</code>) and standard Active Record relations (<code>hasMany</code>, <code>belongsTo</code>).</div>
        </div>
        <div class="glass-card">
            <div class="card-title"><span class="icon">🧩</span> Output Buffer Template</div>
            <div class="card-body">Completely refactored view engine utilizing an output-buffered stack for sections and layouts, resolving previous scopes and static regex limitation bugs.</div>
        </div>
    </div>
</div>
@endsection
