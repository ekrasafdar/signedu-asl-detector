<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SignEdu - Sign Language Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Space Grotesk', sans-serif; }
        html, body { height: 100%; margin: 0; background: #080b14; color: white; }
        body { display: flex; overflow: hidden; }

        .sidebar {
            width: 260px;
            min-width: 260px;
            height: 100vh;
            background: #0d1117;
            border-right: 1px solid #1a2332;
            display: flex;
            flex-direction: column;
            padding: 24px 16px;
            position: fixed;
            top: 0; left: 0;
            z-index: 50;
        }

        .main-content {
            margin-left: 260px;
            flex: 1;
            height: 100vh;
            overflow-y: auto;
            background: #080b14;
        }

        .topbar {
            position: sticky;
            top: 0;
            background: rgba(8,11,20,0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid #1a2332;
            padding: 16px 32px;
            z-index: 40;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 14px;
            color: #6b7280;
            transition: all 0.2s;
            margin-bottom: 2px;
            text-decoration: none;
        }
        .nav-link:hover { background: #161d2e; color: #e2e8f0; }
        .nav-link.active { background: linear-gradient(135deg, #1a1040, #0f2040); color: #a78bfa; border: 1px solid #2d1f6e; }

        .glass-card {
            background: #0d1117;
            border: 1px solid #1a2332;
            border-radius: 16px;
            padding: 24px;
        }

        .accent-card {
            background: linear-gradient(135deg, #0f0a2e, #0a1628);
            border: 1px solid #2d1f6e;
            border-radius: 16px;
            padding: 24px;
        }

        .stat-card {
            background: #0d1117;
            border: 1px solid #1a2332;
            border-radius: 16px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: border-color 0.3s;
        }
        .stat-card:hover { border-color: #2d1f6e; }

        .badge-admin { background: rgba(167,139,250,0.1); color: #a78bfa; border: 1px solid rgba(167,139,250,0.2); padding: 2px 10px; border-radius: 20px; font-size: 11px; }
        .badge-student { background: rgba(56,189,248,0.1); color: #38bdf8; border: 1px solid rgba(56,189,248,0.2); padding: 2px 10px; border-radius: 20px; font-size: 11px; }

        .btn-primary {
            background: linear-gradient(135deg, #7c3aed, #2563eb);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 8px 25px rgba(124,58,237,0.35); }

        .gradient-text { background: linear-gradient(135deg, #a78bfa, #38bdf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

        .tag { background: rgba(167,139,250,0.1); color: #a78bfa; border: 1px solid rgba(167,139,250,0.15); padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; }

        .section-label { font-size: 10px; font-weight: 700; color: #374151; letter-spacing: 0.12em; text-transform: uppercase; padding: 0 14px; margin: 16px 0 6px; }

        ::-webkit-scrollbar { width: 3px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #1a2332; border-radius: 2px; }

        .glow { box-shadow: 0 0 40px rgba(124,58,237,0.12); }
        .animate-pulse-slow { animation: pulse 3s ease-in-out infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.5} }

        .table-row { border-bottom: 1px solid #1a2332; transition: background 0.15s; }
        .table-row:hover { background: #0d1117; }

        input, select {
            background: #0d1117 !important;
            border: 1px solid #1a2332 !important;
            color: white !important;
            border-radius: 10px;
            padding: 12px 16px;
            width: 100%;
            font-size: 14px;
            transition: border-color 0.2s;
            outline: none;
        }
        input:focus, select:focus { border-color: #7c3aed !important; }
        input::placeholder { color: #4b5563; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:28px;padding:0 6px;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#7c3aed,#2563eb);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;">🤟</div>
        <div>
            <div style="font-weight:700;font-size:16px;color:white;">SignEdu</div>
            <div style="font-size:11px;color:#4b5563;">AI Sign Platform</div>
        </div>
    </div>

    <div style="background:#161d2e;border:1px solid #1a2332;border-radius:12px;padding:14px;margin-bottom:24px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:36px;height:36px;background:linear-gradient(135deg,#7c3aed,#2563eb);border-radius:9px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
                <div style="font-size:13px;font-weight:600;color:white;">{{ auth()->user()->name }}</div>
                <span class="{{ auth()->user()->role === 'admin' ? 'badge-admin' : 'badge-student' }}">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
        </div>
    </div>

    <nav style="flex:1;">
        <div class="section-label">Main</div>
        <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home" style="width:16px;text-align:center;"></i> Dashboard
        </a>
        <a href="/students" class="nav-link {{ request()->is('students*') ? 'active' : '' }}">
            <i class="fas fa-users" style="width:16px;text-align:center;"></i> Students
        </a>
        @if(auth()->user()->role === 'admin')
        <a href="/students/create" class="nav-link">
            <i class="fas fa-user-plus" style="width:16px;text-align:center;"></i> Add Student
        </a>
        @endif

        <div class="section-label" style="margin-top:20px;">AI Tools</div>
        <a href="/sign-detector" class="nav-link {{ request()->is('sign-detector') ? 'active' : '' }}">
            <i class="fas fa-camera" style="width:16px;text-align:center;"></i>
            Sign Detector
            <span style="margin-left:auto;background:rgba(34,197,94,0.15);color:#22c55e;border:1px solid rgba(34,197,94,0.2);padding:1px 8px;border-radius:20px;font-size:10px;font-weight:700;">LIVE</span>
        </a>
    </nav>

    <div style="border-top:1px solid #1a2332;padding-top:16px;margin-top:16px;">
        <form method="POST" action="/logout">
            @csrf
            <button style="width:100%;display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;background:transparent;border:none;color:#6b7280;font-size:14px;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.color='#ef4444';this.style.background='rgba(239,68,68,0.08)'" onmouseout="this.style.color='#6b7280';this.style.background='transparent'">
                <i class="fas fa-sign-out-alt" style="width:16px;text-align:center;"></i> Sign Out
            </button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="topbar" style="display:flex;align-items:center;justify-content:space-between;">
        <div style="font-size:12px;color:#4b5563;">{{ now()->format('l, F j, Y') }}</div>
        <div style="display:flex;align-items:center;gap:8px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.15);padding:6px 14px;border-radius:20px;">
            <div style="width:6px;height:6px;background:#22c55e;border-radius:50%;" class="animate-pulse-slow"></div>
            <span style="font-size:11px;color:#22c55e;font-weight:600;">All Systems Online</span>
        </div>
    </div>

    <div style="padding:32px;">
        @if(session('success'))
        <div style="display:flex;align-items:center;gap:12px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);color:#4ade80;padding:14px 20px;border-radius:12px;margin-bottom:24px;font-size:14px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div style="display:flex;align-items:center;gap:12px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);color:#f87171;padding:14px 20px;border-radius:12px;margin-bottom:24px;font-size:14px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </div>
</div>

</body>
</html>