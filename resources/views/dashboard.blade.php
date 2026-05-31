@extends('layouts.app')
@section('content')

<div style="margin-bottom:32px;">
    <div style="font-size:28px;font-weight:800;color:white;margin-bottom:6px;">
        Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }},
        <span class="gradient-text">{{ explode(' ', auth()->user()->name)[0] }}</span> 👋
    </div>
    <p style="color:#6b7280;font-size:14px;">Here's your platform overview for today.</p>
</div>

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
    <div class="stat-card glow">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
            <div style="width:44px;height:44px;background:rgba(124,58,237,0.15);border:1px solid rgba(124,58,237,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-users" style="color:#a78bfa;"></i>
            </div>
            <span style="font-size:11px;color:#22c55e;background:rgba(34,197,94,0.1);padding:3px 8px;border-radius:20px;">Active</span>
        </div>
        <div style="font-size:42px;font-weight:800;" class="gradient-text">{{ $totalStudents }}</div>
        <div style="font-size:13px;color:#6b7280;margin-top:4px;">Total Students</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
            <div style="width:44px;height:44px;background:rgba(56,189,248,0.1);border:1px solid rgba(56,189,248,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-user-shield" style="color:#38bdf8;"></i>
            </div>
            <span style="font-size:11px;color:#38bdf8;background:rgba(56,189,248,0.1);padding:3px 8px;border-radius:20px;">{{ ucfirst(auth()->user()->role) }}</span>
        </div>
        <div style="font-size:42px;font-weight:800;color:#38bdf8;">{{ $totalUsers }}</div>
        <div style="font-size:13px;color:#6b7280;margin-top:4px;">Registered Users</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
            <div style="width:44px;height:44px;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-brain" style="color:#22c55e;"></i>
            </div>
            <span style="font-size:11px;color:#22c55e;background:rgba(34,197,94,0.1);padding:3px 8px;border-radius:20px;">98.4% Acc</span>
        </div>
        <div style="font-size:42px;font-weight:800;color:#22c55e;">26</div>
        <div style="font-size:13px;color:#6b7280;margin-top:4px;">ASL Signs Trained</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:3fr 2fr;gap:16px;">
    <!-- Recent Students -->
    <div class="glass-card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <div style="font-size:15px;font-weight:700;color:white;">Recent Students</div>
            <a href="/students" style="font-size:12px;color:#a78bfa;text-decoration:none;">View all →</a>
        </div>
        @foreach($recentStudents as $student)
        <div style="display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid #1a2332;">
            <div style="width:38px;height:38px;background:linear-gradient(135deg,rgba(124,58,237,0.3),rgba(37,99,235,0.3));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#a78bfa;flex-shrink:0;">
                {{ strtoupper(substr($student->name, 0, 2)) }}
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:13px;font-weight:600;color:white;">{{ $student->name }}</div>
                <div style="font-size:11px;color:#6b7280;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $student->email }}</div>
            </div>
            <span class="tag">{{ $student->course }}</span>
        </div>
        @endforeach
    </div>

    <!-- Right Panel -->
    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="accent-card" style="flex:1;">
            <div style="font-size:32px;margin-bottom:12px;">🤟</div>
            <div style="font-size:16px;font-weight:800;color:white;margin-bottom:6px;">Sign Detector</div>
            <div style="font-size:12px;color:#6b7280;margin-bottom:16px;line-height:1.6;">Real-time ASL detection with 98.4% accuracy using MediaPipe + Random Forest</div>
            <a href="/sign-detector" class="btn-primary">
                <i class="fas fa-camera"></i> Launch AI
            </a>
        </div>

        <div class="glass-card" style="padding:16px;">
            <div style="font-size:12px;font-weight:700;color:white;margin-bottom:12px;">System Info</div>
            <div style="display:flex;flex-direction:column;gap:8px;">
                <div style="display:flex;justify-content:space-between;font-size:12px;">
                    <span style="color:#6b7280;">Dataset</span>
                    <span style="color:white;font-weight:600;">87,000 images</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:12px;">
                    <span style="color:#6b7280;">AI Model</span>
                    <span style="color:#22c55e;font-weight:600;">● Online</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:12px;">
                    <span style="color:#6b7280;">Accuracy</span>
                    <span style="color:white;font-weight:600;">98.4%</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:12px;">
                    <span style="color:#6b7280;">Framework</span>
                    <span style="color:white;font-weight:600;">Laravel + Flask</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection