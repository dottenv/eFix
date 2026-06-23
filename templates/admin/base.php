<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Админ-панель ' . ($site_name ?? 'eFix')) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{
            --primary:#0B2447;
            --primary-light:#19376D;
            --accent:#FF6B35;
            --accent-hover:#E85D2C;
            --bg:#F5F7FA;
            --bg-alt:#EFF2F6;
            --surface:#FFFFFF;
            --text:#1A1A2E;
            --text-muted:#6B7280;
            --text-light:#9CA3AF;
            --border:#E5E7EB;
            --success:#10B981;
            --danger:#EF4444;
            --warning:#F59E0B;
            --radius:12px;
            --radius-sm:8px;
            --shadow:0 4px 20px rgba(0,0,0,.08);
            --font:'Inter',sans-serif;
            --sidebar-w:240px;
            --sidebar-w-collapsed:60px
        }
        html,body{height:100%}
        body{font-family:var(--font);font-size:15px;line-height:1.5;color:var(--text);background:var(--bg)}
        a{color:var(--accent);text-decoration:none}
        a:hover{color:var(--accent-hover)}
        .layout{display:flex;min-height:100vh}
        .sidebar{
            width:var(--sidebar-w);background:var(--primary);color:#fff;
            display:flex;flex-direction:column;flex-shrink:0;
            transition:width .25s ease;overflow:hidden;position:relative
        }
        .sidebar--collapsed{width:var(--sidebar-w-collapsed)}
        .sidebar__logo{
            display:flex;align-items:center;gap:10px;height:60px;
            padding:0 18px;font-weight:800;font-size:18px;
            border-bottom:1px solid rgba(255,255,255,.1);white-space:nowrap;flex-shrink:0
        }
        .sidebar__logo span{color:var(--accent)}
        .sidebar__toggle{
            position:absolute;top:18px;right:-12px;z-index:10;
            width:24px;height:24px;border-radius:50%;
            background:var(--surface);border:1px solid var(--border);
            display:flex;align-items:center;justify-content:center;
            cursor:pointer;color:var(--text-muted);font-size:12px;
            transition:all .2s;box-shadow:var(--shadow)
        }
        .sidebar__toggle:hover{background:var(--accent);color:#fff;border-color:var(--accent)}
        .sidebar__nav{flex:1;padding:8px 0;overflow-y:auto;overflow-x:hidden}
        .sidebar__group{padding:0;margin:0}
        .sidebar__group-title{
            padding:16px 18px 6px;font-size:10px;font-weight:700;
            color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:1px;
            white-space:nowrap;overflow:hidden
        }
        .sidebar--collapsed .sidebar__group-title{visibility:hidden}
        .sidebar__link{
            display:flex;align-items:center;gap:10px;padding:9px 18px;
            color:rgba(255,255,255,.65);font-size:13px;font-weight:500;
            transition:all .15s;border-left:3px solid transparent;
            white-space:nowrap;min-height:38px
        }
        .sidebar__link:hover,.sidebar__link--active{color:#fff;background:rgba(255,255,255,.08)}
        .sidebar__link--active{border-left-color:var(--accent)}
        .sidebar__link svg{flex-shrink:0;width:18px;height:18px}
        .sidebar__link-text{overflow:hidden;transition:opacity .2s}
        .sidebar--collapsed .sidebar__link-text{opacity:0;width:0}
        .sidebar__footer{
            padding:12px 18px;border-top:1px solid rgba(255,255,255,.1);
            font-size:12px;white-space:nowrap;flex-shrink:0
        }
        .sidebar__footer a{color:rgba(255,255,255,.5);display:flex;align-items:center;gap:10px}
        .sidebar__footer a:hover{color:#fff}
        .main{flex:1;display:flex;flex-direction:column;min-width:0}
        .topbar{
            display:flex;align-items:center;justify-content:space-between;
            padding:14px 24px;background:var(--surface);
            border-bottom:1px solid var(--border);gap:16px
        }
        .topbar__left{display:flex;align-items:center;gap:12px}
        .topbar__burger{
            display:none;width:32px;height:32px;border-radius:var(--radius-sm);
            border:none;background:var(--bg);cursor:pointer;
            flex-direction:column;align-items:center;justify-content:center;gap:4px
        }
        .topbar__burger span{display:block;width:16px;height:2px;background:var(--text);border-radius:2px;transition:all .2s}
        .topbar__title{font-weight:700;font-size:17px;color:var(--text)}
        .topbar__user{font-size:13px;color:var(--text-muted)}
        .content{padding:24px;flex:1;overflow-x:auto}
        .card{
            background:var(--surface);border:1px solid var(--border);
            border-radius:var(--radius);padding:24px;margin-bottom:24px
        }
        .card__header{
            display:flex;align-items:center;justify-content:space-between;
            margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border);
            gap:12px;flex-wrap:wrap
        }
        .card__title{font-weight:700;font-size:16px;color:var(--text)}
        @media(max-width:768px){
            .sidebar{position:fixed;inset:0;right:auto;z-index:500;transform:translateX(-100%);width:260px;transition:transform .25s ease}
            .sidebar--open{transform:translateX(0)}
            .sidebar__toggle{display:none}
            .topbar__burger{display:flex}
            .sidebar-overlay{position:fixed;inset:0;z-index:499;background:rgba(0,0,0,.4);display:none}
            .sidebar-overlay--show{display:block}
            .content{padding:16px}
        }
        .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:32px}
        .stat-card{
            background:var(--surface);border:1px solid var(--border);
            border-radius:var(--radius);padding:24px
        }
        .stat-card__num{font-size:32px;font-weight:800;color:var(--primary)}
        .stat-card__label{font-size:13px;color:var(--text-muted);margin-top:4px}
        .btn{
            display:inline-flex;align-items:center;justify-content:center;gap:6px;
            font-family:var(--font);font-weight:600;font-size:13px;
            padding:8px 16px;border-radius:var(--radius-sm);
            border:1px solid transparent;cursor:pointer;
            transition:all .2s;white-space:nowrap
        }
        .btn--primary{background:var(--accent);color:#fff;border-color:var(--accent)}
        .btn--primary:hover{background:var(--accent-hover);border-color:var(--accent-hover)}
        .btn--outline{background:transparent;color:var(--text);border-color:var(--border)}
        .btn--outline:hover{border-color:var(--accent);color:var(--accent)}
        .btn--danger{background:var(--danger);color:#fff;border-color:var(--danger)}
        .btn--danger:hover{background:#DC2626}
        .btn--success{background:var(--success);color:#fff;border-color:var(--success)}
        .btn--sm{padding:4px 10px;font-size:12px}
        .form-group{margin-bottom:16px}
        .form-group label{display:block;font-size:13px;font-weight:600;color:var(--text-muted);margin-bottom:4px}
        .form-group input,.form-group select,.form-group textarea{
            width:100%;padding:10px 14px;border:2px solid var(--border);border-radius:var(--radius-sm);
            font-family:var(--font);font-size:14px;color:var(--text);background:var(--surface);
            transition:all .2s
        }
        .form-group input:focus,.form-group select:focus,.form-group textarea:focus{
            outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(255,107,53,.1)
        }
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        .form-actions{display:flex;gap:8px;justify-content:flex-end;padding-top:16px;border-top:1px solid var(--border);margin-top:16px}
        table{width:100%;border-collapse:collapse;font-size:14px}
        th{padding:10px 12px;text-align:left;font-weight:600;font-size:12px;color:var(--text-muted);background:var(--bg);border-bottom:2px solid var(--border);white-space:nowrap}
        td{padding:10px 12px;border-bottom:1px solid var(--border)}
        tr:hover td{background:rgba(255,107,53,.03)}
        .table-wrap{overflow-x:auto}
        .table-actions{display:flex;gap:4px;flex-wrap:wrap}
        .badge{
            display:inline-block;padding:2px 8px;border-radius:100px;
            font-size:11px;font-weight:600
        }
        .badge--active{background:rgba(16,185,129,.1);color:#059669}
        .badge--inactive{background:rgba(239,68,68,.1);color:#DC2626}
        .badge--phone{background:rgba(99,102,241,.1);color:#6366F1}
        .badge--tablet{background:rgba(16,185,129,.1);color:#059669}
        .badge--laptop{background:rgba(245,158,11,.1);color:#D97706}
        .badge--pc{background:rgba(239,68,68,.1);color:#DC2626}
        .empty{text-align:center;padding:48px;color:var(--text-light)}
        .modal-overlay{
            position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,.4);
            display:none;align-items:center;justify-content:center;padding:24px
        }
        .modal-overlay.open{display:flex}
        .modal{
            background:var(--surface);border-radius:var(--radius);
            width:100%;max-width:520px;max-height:85vh;overflow-y:auto;
            box-shadow:0 12px 40px rgba(0,0,0,.15)
        }
        .modal__header{
            display:flex;align-items:center;justify-content:space-between;
            padding:20px 24px 12px;position:sticky;top:0;background:var(--surface)
        }
        .modal__title{font-weight:700;font-size:16px}
        .modal__close{
            width:32px;height:32px;border-radius:8px;border:none;
            display:flex;align-items:center;justify-content:center;
            cursor:pointer;background:var(--bg);color:var(--text-muted);font-size:18px
        }
        .modal__close:hover{background:var(--danger);color:#fff}
        .modal__body{padding:12px 24px 24px}
        .inline-form{display:inline}
        .text-muted{color:var(--text-muted)}
        .text-sm{font-size:13px}
        .mt-2{margin-top:8px}
        .mb-2{margin-bottom:8px}
        .w-full{width:100%}
        .pagination{display:flex;align-items:center;gap:4px;padding:16px 0 8px;flex-wrap:wrap}
        .pagination__btn{display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--surface);color:var(--text);font-size:13px;font-weight:600;cursor:pointer;transition:all .15s;padding:0 8px}
        .pagination__btn:hover{border-color:var(--accent);color:var(--accent)}
        .pagination__btn--active{background:var(--accent);color:#fff;border-color:var(--accent)}
        .pagination__btn:disabled{opacity:.4;cursor:default;pointer-events:none}
        .pagination__ellipsis{padding:0 4px;color:var(--text-light);font-size:13px}
        .pagination-info{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:8px 0;flex-wrap:wrap}
        .pagination-info__text{font-size:12px;color:var(--text-light)}
        .pagination-info__per-page{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted)}
        .pagination-info__per-page select{padding:4px 8px;border:1px solid var(--border);border-radius:4px;font-size:12px;background:var(--surface);cursor:pointer}
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/htmx.org@2.0.4" crossorigin="anonymous"></script>
    <?= $extra_head ?? '' ?>
    <style>[x-cloak]{display:none!important}</style>
</head>
<body>
<div class="layout" x-data="adminLayout()">
    <div class="sidebar-overlay" :class="mobileOpen ? 'sidebar-overlay--show' : ''" @click="mobileOpen = false" x-show="mobileOpen" x-cloak></div>
    <aside class="sidebar" :class="{'sidebar--collapsed': collapsed, 'sidebar--open': mobileOpen}">
        <button class="sidebar__toggle" @click="collapsed = !collapsed" :style="collapsed ? 'transform:rotate(180deg)' : ''" x-cloak>
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <div class="sidebar__logo">
            <span style="font-size:22px"><?= mb_substr($site_name ?? 'e', 0, 1) ?><span><?= mb_strlen($site_name ?? 'e') > 1 ? mb_substr($site_name ?? 'eFix', 1, 1) : '' ?></span></span>
            <span x-show="!collapsed" x-cloak><?= e($site_name ?? 'eFix') ?> / Admin</span>
        </div>
        <nav class="sidebar__nav">
            <div class="sidebar__group">
                <div class="sidebar__group-title">Обзор</div>
                <a href="<?= url_for('admin.dashboard') ?>" class="sidebar__link <?= ($active ?? '') === 'dashboard' ? 'sidebar__link--active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <span class="sidebar__link-text">Дашборд</span>
                </a>
                <a href="<?= url_for('admin.stats') ?>" class="sidebar__link <?= ($active ?? '') === 'stats' ? 'sidebar__link--active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/></svg>
                    <span class="sidebar__link-text">Аналитика</span>
                </a>
            </div>
            <div class="sidebar__group">
                <div class="sidebar__group-title">Контент</div>
                <a href="<?= url_for('admin.site') ?>" class="sidebar__link <?= ($active ?? '') === 'site' ? 'sidebar__link--active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    <span class="sidebar__link-text">Информация сайта</span>
                </a>
                <a href="<?= url_for('admin.services') ?>" class="sidebar__link <?= ($active ?? '') === 'services' ? 'sidebar__link--active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    <span class="sidebar__link-text">Услуги</span>
                </a>
            </div>
            <div class="sidebar__group">
                <div class="sidebar__group-title">Коммерция</div>
                <a href="<?= url_for('admin.prices') ?>" class="sidebar__link <?= ($active ?? '') === 'prices' ? 'sidebar__link--active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    <span class="sidebar__link-text">Прайс-лист</span>
                </a>
                <a href="<?= url_for('admin.requests_list') ?>" class="sidebar__link <?= str_contains($active ?? '', 'requests') ? 'sidebar__link--active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span class="sidebar__link-text">Заявки</span>
                    <span id="sidebarRequestBadge" class="badge badge--new" style="font-size:10px;margin-left:auto;display:none">0</span>
                </a>
            </div>
            <div class="sidebar__group">
                <div class="sidebar__group-title">Партнёры</div>
                <a href="<?= url_for('admin.workshops') ?>" class="sidebar__link <?= ($active ?? '') === 'workshops' ? 'sidebar__link--active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span class="sidebar__link-text">Мастерские</span>
                </a>
            </div>
            <div class="sidebar__group">
                <div class="sidebar__group-title">Почта</div>
                <a href="<?= url_for('admin.mail_config') ?>" class="sidebar__link <?= ($active ?? '') === 'mail_config' ? 'sidebar__link--active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <span class="sidebar__link-text">Настройки</span>
                </a>
                <a href="<?= url_for('admin.mail_templates') ?>" class="sidebar__link <?= in_array($active ?? '', ['mail_templates', 'mail_template_add', 'mail_template_edit']) ? 'sidebar__link--active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    <span class="sidebar__link-text">Шаблоны</span>
                </a>
            </div>
            <div class="sidebar__group">
                <div class="sidebar__group-title">Система</div>
                <a href="<?= url_for('admin.settings') ?>" class="sidebar__link <?= ($active ?? '') === 'settings' ? 'sidebar__link--active' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    <span class="sidebar__link-text">Настройки БД</span>
                </a>
            </div>
        </nav>
        <div class="sidebar__footer">
            <a href="<?= url_for('main.index') ?>" target="_blank">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                <span class="sidebar__link-text">На сайт</span>
            </a>
        </div>
    </aside>
    <div class="main">
        <header class="topbar">
            <div class="topbar__left">
                <button class="topbar__burger" @click="mobileOpen = !mobileOpen" aria-label="Меню">
                    <span></span><span></span><span></span>
                </button>
                <h1 class="topbar__title"><?= $header ?? 'Админ-панель' ?></h1>
            </div>
            <div class="topbar__user">
                <a href="<?= url_for('admin.logout') ?>" style="color:var(--text-muted)">Выйти</a>
            </div>
        </header>
        <div class="content">
            <?= $content ?? '' ?>
        </div>
    </div>
</div>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('adminLayout', () => ({
        collapsed: window.innerWidth < 1024,
        mobileOpen: false,
        init() {
            if (window.innerWidth < 768) this.collapsed = false;
        }
    }));
    Alpine.data('bulkDelete', () => ({
        selected: [],
        get anySelected() { return this.selected.length > 0; },
        get selectedCount() { return this.selected.length; },
        toggleAll(ctx) {
            const boxes = ctx.querySelectorAll('.bulk-select');
            if (this.selected.length === boxes.length) {
                this.selected = [];
            } else {
                this.selected = Array.from(boxes).map(b => parseInt(b.value));
            }
        },
        deleteSelected(url) {
            if (!this.anySelected) return;
            if (!confirm(`Удалить ${this.selected.length} записей?`)) return;
            const form = document.getElementById('bulk-form');
            form.querySelector('[name=ids]').value = this.selected.join(',');
            htmx.trigger(form, 'submit');
        },
        init() {
            this.$el.addEventListener('htmx:afterSwap', () => {
                this.selected = [];
            });
        }
    }));
    window.setPerPage = function(val, isHtmx) {
        localStorage.setItem('admin_per_page', val);
        if (isHtmx) {
            const container = document.getElementById('workshops-table-container');
            if (container) htmx.ajax('GET', '/admin/workshops?page=1&per_page=' + val, { target: '#workshops-table-container', swap: 'innerHTML' });
        } else {
            const url = new URL(window.location);
            url.searchParams.set('per_page', val);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }
    };
    (function() {
        const saved = localStorage.getItem('admin_per_page');
        if (saved) {
            const url = new URL(window.location);
            if (!url.searchParams.has('per_page')) {
                url.searchParams.set('per_page', saved);
                url.searchParams.set('page', 1);
                window.location.href = url.toString();
            }
        }
    })();
});
</script>
<?= $extra_scripts ?? '' ?>
<script>
(function() {
    const badge = document.getElementById('sidebarRequestBadge');
    if (badge) {
        function update() {
            fetch('/admin/requests/check').then(r => r.json()).then(d => {
                const n = parseInt(d.count) || 0;
                badge.textContent = n;
                badge.style.display = n > 0 ? 'inline' : 'none';
            }).catch(() => {});
        }
        update();
        setInterval(update, 30000);
    }
})();
</script>
</body>
</html>
