<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perfect Solution')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f7f7f7;
            --ink: #000733;
            --muted: #777777;
            --card: #ffffff;
            --line: #e6e8ee;
            --teal: #0053ce;
            --teal-soft: #e8f0ff;
            --navy: #03113e;
            --gold: #0053ce;
            --gold-soft: #e8f0ff;
            --danger: #b42318;
            --danger-soft: #fdeceb;
            --ok: #067647;
            --ok-soft: #e8f7ef;
            --shadow: 0 20px 80px rgba(0, 7, 51, .08);
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: "IBM Plex Sans Arabic", Tahoma, sans-serif; background: var(--bg); color: var(--ink); }
        a { color: var(--teal); }
        h1, h2, p { margin: 0; }
        .shell { min-height: 100vh; display: flex; }
        .sidebar { width: 272px; flex: none; background: var(--navy); color: #fff; display: flex; flex-direction: column; padding: 24px 18px; }
        .brand { display: block; text-decoration: none; color: #fff; margin-bottom: 28px; }
        .brand img { width: 100%; max-width: 210px; height: auto; display: block; mix-blend-mode: screen; }
        .brand small { display: block; color: #cbd5f1; font-family: Poppins, "IBM Plex Sans Arabic", sans-serif; font-weight: 600; letter-spacing: .02em; margin-top: 10px; font-size: 12px; }
        .nav-link { display: flex; align-items: center; justify-content: space-between; color: #d7deea; text-decoration: none; padding: 12px 14px; border-radius: 12px; margin-bottom: 6px; }
        .nav-link.active, .nav-link:hover { background: rgba(255,255,255,.08); color: #fff; }
        .sidebar-foot { margin-top: auto; padding-top: 18px; border-top: 1px solid rgba(255,255,255,.08); }
        .who { color: #b8c2d2; font-size: 13px; margin-bottom: 10px; }
        .workspace { flex: 1; min-width: 0; }
        .topbar { display: flex; justify-content: space-between; gap: 16px; align-items: flex-end; padding: 28px 32px 0; }
        .topbar h1 { font-size: 28px; }
        .topbar p, .muted { color: var(--muted); }
        .content { padding: 22px 32px 40px; }
        .actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .button, button { display: inline-flex; align-items: center; justify-content: center; background: var(--teal); color: #fff; border: 0; border-radius: 12px; padding: 10px 16px; text-decoration: none; cursor: pointer; font: inherit; }
        .button.ghost, button.ghost { background: transparent; color: var(--ink); border: 1px solid var(--line); }
        .button.light { background: #fff; color: var(--navy); }
        .stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 18px; }
        .stat, .card { background: var(--card); border: 1px solid var(--line); border-radius: 18px; box-shadow: var(--shadow); }
        .stat { padding: 16px 18px; }
        .stat span { display: block; color: var(--muted); font-size: 13px; }
        .stat strong { display: block; margin-top: 6px; font-size: 26px; }
        .card { padding: 22px; }
        .toolbar { display: flex; gap: 10px; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; }
        input, select { width: 100%; padding: 11px 12px; border: 1px solid #d9d2c5; border-radius: 12px; background: #fff; font: inherit; color: inherit; }
        .search { width: min(360px, 100%); }
        .filters { display: flex; gap: 8px; flex-wrap: wrap; }
        .chip { border: 1px solid var(--line); background: #fff; color: var(--ink); border-radius: 999px; padding: 8px 12px; cursor: pointer; font: inherit; }
        .chip.active { background: var(--navy); color: #fff; border-color: var(--navy); }
        .table-wrap { overflow: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 980px; }
        th, td { text-align: right; padding: 14px 12px; border-bottom: 1px solid var(--line); vertical-align: top; }
        th { color: var(--muted); font-size: 13px; font-weight: 600; }
        tr:hover td { background: #fbf8f2; }
        .company-name { font-weight: 700; color: inherit; text-decoration: none; }
        .sub { display: block; color: var(--muted); font-size: 13px; margin-top: 3px; }
        .pill { display: inline-flex; align-items: center; border-radius: 999px; padding: 4px 10px; font-size: 12px; font-weight: 600; background: var(--gold-soft); color: var(--gold); }
        .pill.ready { background: var(--ok-soft); color: var(--ok); }
        .pill.failed { background: var(--danger-soft); color: var(--danger); }
        .pill.pending { background: var(--gold-soft); color: var(--gold); }
        .num { font-variant-numeric: tabular-nums; font-weight: 600; }
        .row-actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .row-actions a { font-size: 13px; }
        label { display: block; margin: 14px 0 6px; font-weight: 600; }
        .error { color: var(--danger); margin-top: 6px; }
        .status { background: var(--ok-soft); color: var(--ok); padding: 12px 14px; border-radius: 12px; margin-bottom: 16px; }
        .grid-2 { display: grid; grid-template-columns: 1.2fr .8fr; gap: 14px; }
        .facts { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 18px; margin-top: 16px; }
        .facts span { display: block; color: var(--muted); font-size: 13px; }
        .metrics { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; margin-top: 16px; }
        .metric { background: #f7f4ee; border-radius: 14px; padding: 12px; }
        .metric span { color: var(--muted); font-size: 12px; display: block; }
        .metric strong { font-size: 20px; }
        .steps { list-style: none; padding: 0; margin: 20px 0; }
        .steps li { display: flex; gap: 12px; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--line); }
        .dot { width: 22px; height: 22px; border-radius: 50%; border: 2px solid #d0d7de; flex: 0 0 auto; }
        .done .dot { background: var(--teal); border-color: var(--teal); }
        .current .dot { border-color: var(--teal); box-shadow: inset 0 0 0 4px #d6e4ff; }
        .running .dot { animation: pulse 1s ease-in-out infinite; }
        .failed .dot { border-color: var(--danger); background: var(--danger); }
        @keyframes pulse { 50% { opacity: .35; } }
        .guest { min-height: 100vh; display: grid; place-items: center; padding: 24px; background:
            radial-gradient(circle at top right, rgba(0,83,206,.35), transparent 36%),
            linear-gradient(160deg, #03113e 0%, #000733 58%, #f7f7f7 58%); }
        .guest-wrap { width: min(460px, 100%); }
        .guest-card { width: 100%; }
        .guest-logo { width: min(240px, 78%); height: auto; display: block; margin: 0 auto 18px; mix-blend-mode: screen; }
        .guest-card h1 { margin-bottom: 8px; }
        .split { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .note { margin-top: 16px; }
        @media (max-width: 1100px) {
            .stats, .metrics { grid-template-columns: 1fr 1fr; }
            .grid-2, .split, .facts { grid-template-columns: 1fr; }
        }
        @media (max-width: 800px) {
            .shell { display: block; }
            .sidebar { width: auto; }
            .sidebar-foot { margin-top: 12px; }
            .topbar, .content { padding-right: 16px; padding-left: 16px; }
            .stats, .metrics { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
@auth('platform')
    <div class="shell">
        <aside class="sidebar">
            <a class="brand" href="{{ route('platform.companies.index') }}">
                <img src="{{ asset('assets/admin/images/logo/logo.png') }}" alt="Perfect Solution">
                <small>What you want is here</small>
            </a>
            <a class="nav-link {{ request()->routeIs('platform.companies.index') || request()->routeIs('platform.companies.show') ? 'active' : '' }}" href="{{ route('platform.companies.index') }}">الشركات</a>
            <a class="nav-link {{ request()->routeIs('platform.companies.create') ? 'active' : '' }}" href="{{ route('platform.companies.create') }}">شركة جديدة</a>
            <div class="sidebar-foot">
                <div class="who">{{ auth('platform')->user()->name ?? auth('platform')->user()->email }}</div>
                <form action="{{ route('platform.logout') }}" method="POST">
                    @csrf
                    <button class="light" type="submit">خروج</button>
                </form>
            </div>
        </aside>
        <div class="workspace">
            <header class="topbar">
                <div>
                    <h1>@yield('heading')</h1>
                    <p>@yield('lead')</p>
                </div>
                <div class="actions">@yield('actions')</div>
            </header>
            <main class="content">
                @yield('content')
            </main>
        </div>
    </div>
@else
    <div class="guest">
        <div class="guest-wrap">
            <img class="guest-logo" src="{{ asset('assets/admin/images/logo/logo.png') }}" alt="Perfect Solution">
            <main class="guest-card card">
                @yield('content')
            </main>
        </div>
    </div>
@endauth
@stack('scripts')
</body>
</html>
