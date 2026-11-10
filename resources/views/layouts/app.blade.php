<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Customers') | Customer Management System</title>

    {{-- Google Font, Bootstrap 5 and Bootstrap Icons from CDN --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* ---------- Design tokens (one indigo accent colour) ---------- */
        :root {
            --accent: #4f46e5;
            --accent-dark: #4338ca;
            --accent-soft: #eef2ff;
            --page-bg: #f4f6fb;
            --text: #1e293b;
            --muted: #64748b;
            --border: #e8ebf3;
            --male: #2563eb;
            --male-soft: #e0ecff;
            --female: #db2777;
            --female-soft: #fde7f1;
            --teen: #059669;
            --teen-soft: #dcfce7;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: var(--page-bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ---------- Header ---------- */
        .app-header {
            background: #fff;
            border-bottom: 1px solid var(--border);
        }
        .brand-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: var(--accent);
            color: #fff;
            display: grid; place-items: center;
            font-size: 1.3rem;
        }
        .brand-title { font-weight: 800; font-size: 1.15rem; margin: 0; }
        .brand-sub { color: var(--muted); font-size: .85rem; margin: 0; }

        /* Pill-style navigation tabs */
        .nav-pills-custom {
            background: var(--page-bg);
            border-radius: 999px;
            padding: 4px;
            gap: 2px;
        }
        .nav-pills-custom .nav-link {
            color: var(--muted);
            font-weight: 600;
            font-size: .9rem;
            border-radius: 999px;
            padding: .45rem 1rem;
            white-space: nowrap;
        }
        .nav-pills-custom .nav-link:hover { color: var(--accent); }
        .nav-pills-custom .nav-link.active {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, .3);
        }

        /* ---------- Cards ---------- */
        .card-soft {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, .04), 0 6px 20px rgba(15, 23, 42, .04);
        }

        /* Stat cards (whole card is a link) */
        .stat-card {
            display: flex; align-items: center; gap: 1rem;
            padding: 1.15rem 1.25rem;
            text-decoration: none;
            color: inherit;
            transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, .08);
            color: inherit;
        }
        .stat-card.current { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); }
        .stat-icon {
            width: 48px; height: 48px; flex-shrink: 0;
            border-radius: 50%;
            display: grid; place-items: center;
            font-size: 1.25rem;
        }
        .stat-value { font-size: 1.6rem; font-weight: 800; line-height: 1.1; }
        .stat-label { font-size: .8rem; color: var(--muted); font-weight: 500; }

        /* Soft colour helpers */
        .bg-accent-soft { background: var(--accent-soft); color: var(--accent); }
        .bg-male-soft   { background: var(--male-soft);   color: var(--male); }
        .bg-female-soft { background: var(--female-soft); color: var(--female); }
        .bg-teen-soft   { background: var(--teen-soft);   color: var(--teen); }

        /* ---------- Page heading ---------- */
        .page-title { font-weight: 800; font-size: 1.5rem; margin: 0; }
        .page-desc { color: var(--muted); margin: .25rem 0 0; font-size: .92rem; }
        .btn-accent {
            background: var(--accent); color: #fff; border: none;
            border-radius: 10px; font-weight: 600;
        }
        .btn-accent:hover { background: var(--accent-dark); color: #fff; }
        .btn-soft {
            background: #fff; color: var(--text);
            border: 1px solid var(--border);
            border-radius: 10px; font-weight: 600;
        }
        .btn-soft:hover { border-color: var(--accent); color: var(--accent); background: #fff; }

        /* ---------- Table ---------- */
        .table-wrap { max-height: 65vh; overflow: auto; border-radius: 0 0 16px 16px; }
        .customer-table { margin: 0; font-size: .9rem; }
        .customer-table thead th {
            position: sticky; top: 0; z-index: 2;    /* sticky header */
            background: #f8fafc;
            color: var(--muted);
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 700;
            border-bottom: 1px solid var(--border);
            padding: .85rem 1rem;
            white-space: nowrap;
        }
        .customer-table td {
            padding: .85rem 1rem;
            border-color: var(--border);
            vertical-align: middle;
        }
        .customer-table tbody tr:hover td { background: var(--accent-soft); }

        .text-muted-sm { color: var(--muted); font-size: .82rem; }
        .pill {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .3rem .7rem;
            border-radius: 999px;
            font-size: .78rem; font-weight: 600;
        }

        /* Search box */
        .search-box { position: relative; max-width: 320px; width: 100%; }
        .search-box i {
            position: absolute; left: .85rem; top: 50%;
            transform: translateY(-50%); color: var(--muted);
        }
        .search-box input {
            padding-left: 2.3rem;
            border-radius: 10px;
            border-color: var(--border);
            font-size: .9rem;
        }
        .search-box input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); }

        /* Filter bar */
        .filter-label { font-size: .8rem; font-weight: 600; color: var(--muted); margin-bottom: .35rem; display: block; }
        .filter-select { border-radius: 10px; border-color: var(--border); font-size: .9rem; }
        .filter-select:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); }

        /* Removable active-filter chips */
        .filter-chip {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .3rem .75rem;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: .8rem; font-weight: 600;
            text-decoration: none;
            border: 1px solid transparent;
        }
        .filter-chip i { font-size: .65rem; }

        /* Friendly error message for an invalid date range */
        .filter-error {
            display: flex; align-items: flex-start; gap: .6rem;
            padding: .85rem 1rem;
            border-radius: 12px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            font-size: .88rem;
        }
        .filter-error i { margin-top: .1rem; }
        .filter-chip:hover { border-color: var(--accent); color: var(--accent-dark); }

        .empty-state { padding: 3rem 1rem; text-align: center; color: var(--muted); }
        .empty-state i { font-size: 2.5rem; color: #cbd5e1; display: block; margin-bottom: .5rem; }

        /* ---------- Footer ---------- */
        .app-footer { color: var(--muted); font-size: .85rem; border-top: 1px solid var(--border); background: #fff; }
    </style>
</head>
<body>

    {{-- Header: app logo, name and subtitle --}}
    <header class="app-header">
        <div class="container py-3">
            <a href="{{ route('customers.index') }}" class="d-inline-flex align-items-center gap-3 text-decoration-none text-reset">
                <span class="brand-icon"><i class="bi bi-people-fill"></i></span>
                <span>
                    <p class="brand-title">Customer Management</p>
                    <p class="brand-sub">View and filter customer records</p>
                </span>
            </a>
        </div>
    </header>

    <main class="container py-4 flex-grow-1">
        {{-- Page-specific content --}}
        @yield('content')
    </main>

    <footer class="app-footer py-3 text-center">
        Customer Management System
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
