<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'nexion Framework' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #2e0854 100%);
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --glass-hover-bg: rgba(255, 255, 255, 0.06);
            --glass-hover-border: rgba(255, 255, 255, 0.15);
            --accent-primary: #06b6d4; /* Cyan */
            --accent-secondary: #d946ef; /* Magenta */
            --accent-glow: rgba(6, 182, 212, 0.15);
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-gradient);
            background-attachment: fixed;
            color: var(--text-main);
            min-height: 100vh;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-x: hidden;
        }

        .background-glows {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
        }

        .glow-1 {
            position: absolute;
            top: -10%;
            right: -10%;
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.15) 0%, rgba(0,0,0,0) 70%);
            filter: blur(80px);
        }

        .glow-2 {
            position: absolute;
            bottom: -10%;
            left: -10%;
            width: 60vw;
            height: 60vw;
            background: radial-gradient(circle, rgba(217, 70, 239, 0.12) 0%, rgba(0,0,0,0) 70%);
            filter: blur(100px);
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px;
            width: 100%;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 40px;
            border-bottom: 1px solid var(--glass-border);
            margin-bottom: 40px;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(to right, var(--accent-primary), var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo::before {
            content: '⚡';
            font-size: 24px;
        }

        .badge {
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid rgba(6, 182, 212, 0.3);
            color: var(--accent-primary);
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        footer {
            text-align: center;
            padding: 40px 20px;
            border-top: 1px solid var(--glass-border);
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 60px;
        }

        footer a {
            color: var(--accent-primary);
            text-decoration: none;
            transition: color 0.3s;
        }

        footer a:hover {
            color: var(--accent-secondary);
        }

        /* Micro-animations and classes */
        .glass-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 30px;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-card:hover {
            background: var(--glass-hover-bg);
            border-color: var(--glass-hover-border);
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3), 0 0 40px var(--accent-glow);
        }
    </style>
</head>
<body>
    <div class="background-glows">
        <div class="glow-1"></div>
        <div class="glow-2"></div>
    </div>

    <div class="container">
        <header>
            <div class="logo">nexion</div>
            <span class="badge">v2.0 Full-Fledged</span>
        </header>

        <main>
            @yield('content')
        </main>
    </div>

    <footer>
        <p>Powered by ⚡ <strong>nexion Framework</strong>..</p>
    </footer>
</body>
</html>
