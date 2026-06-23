<?php
$title = 'Заявки — ' . ($site_name ?? 'eFix') . ' Admin';
$header = 'Заявки';
$extra_head = <<<HTML
<style>
.badge--new{background:rgba(239,68,68,.12);color:#DC2626}
.badge--in_progress{background:rgba(245,158,11,.12);color:#D97706}
.badge--completed{background:rgba(16,185,129,.12);color:#059669}
.badge--archived{background:rgba(107,114,128,.12);color:#6B7280}

.filter-bar{display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap}
.filter-bar__input{flex:1;min-width:160px;padding:8px 12px;border:2px solid var(--border);border-radius:var(--radius-sm);font-family:var(--font);font-size:13px;background:var(--surface);transition:border-color .2s}
.filter-bar__input:focus{outline:none;border-color:var(--accent)}
.filter-bar__date{width:130px;padding:8px 12px;border:2px solid var(--border);border-radius:var(--radius-sm);font-family:var(--font);font-size:13px;background:var(--surface)}
.filter-bar__date:focus{outline:none;border-color:var(--accent)}

.status-tabs{display:flex;gap:4px;margin-bottom:16px;flex-wrap:wrap}
.status-tab{padding:6px 14px;border-radius:100px;border:1px solid var(--border);background:var(--surface);font-size:12px;font-weight:600;color:var(--text-muted);cursor:pointer;transition:all .2s;text-decoration:none}
.status-tab:hover{border-color:var(--accent);color:var(--accent)}
.status-tab--active{background:var(--accent);color:#fff;border-color:var(--accent)}
.status-tab--active:hover{color:#fff}

tr.request-new td:first-child{box-shadow:inset 3px 0 0 var(--accent)}
tr.request-archived{opacity:.5}
.request-checkbox{width:16px;height:16px;cursor:pointer;accent-color:var(--accent)}
.request-phone{font-weight:600;color:var(--accent);text-decoration:none}
.request-phone:hover{text-decoration:underline}
.request-preview{max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--text-muted);font-size:13px}
.request-actions{display:flex;gap:4px}
.request-actions .btn{padding:4px 8px;font-size:11px}
.request-time{font-size:11px;color:var(--text-light);white-space:nowrap}

.bulk-bar{display:none;align-items:center;gap:10px;padding:10px 14px;background:rgba(255,107,53,.06);border:1px solid rgba(255,107,53,.15);border-radius:var(--radius-sm);margin-bottom:12px;font-size:13px}
.bulk-bar--show{display:flex}
.bulk-bar__count{font-weight:700;color:var(--accent)}

.empty-state{padding:48px 24px;text-align:center;color:var(--text-light)}
.empty-state__icon{width:48px;height:48px;margin:0 auto 12px;opacity:.2}
.empty-state__text{font-size:14px;margin-bottom:4px}
.empty-state__sub{font-size:12px}

.toast-new{position:fixed;bottom:24px;right:24px;z-index:9999;padding:14px 20px;background:var(--primary);color:#fff;border-radius:var(--radius);box-shadow:0 8px 32px rgba(0,0,0,.2);display:flex;align-items:center;gap:12px;font-size:14px;animation:slideUp .4s ease;cursor:pointer;transition:opacity .3s}
@keyframes slideUp{from{transform:translateY(20px);opacity:0}to{transform:translateY(0);opacity:1}}
.toast-new__close{width:24px;height:24px;border-radius:50%;border:none;background:rgba(255,255,255,.1);color:#fff;cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.toast-new__close:hover{background:rgba(255,255,255,.2)}

@keyframes spin{to{transform:rotate(360deg)}}
[x-cloak]{display:none!important}
.table-spinner{position:fixed;inset:0;z-index:500;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;background:rgba(255,255,255,.6);backdrop-filter:blur(2px);font-size:14px;color:var(--text-muted)}
.spinner{width:36px;height:36px;border:3px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:spin .7s linear infinite}
</style>
HTML;
ob_start();
?>
<div x-data="requestsApp()" x-init="init()">
    <div class="status-tabs" hx-boost="true" hx-target="#requestsTableWrap" hx-swap="outerHTML" hx-select="#requestsTableWrap">
        <a href="<?= url_for('admin.requests_list', ['q' => $search ?? '', 'from' => $dateFrom ?? '', 'to' => $dateTo ?? '']) ?>"
           class="status-tab <?= empty($statusFilter) ? 'status-tab--active' : '' ?>">Активные</a>
        <a href="<?= url_for('admin.requests_list', ['status' => 'new', 'q' => $search ?? '', 'from' => $dateFrom ?? '', 'to' => $dateTo ?? '']) ?>"
           class="status-tab <?= ($statusFilter ?? '') === 'new' ? 'status-tab--active' : '' ?>">Новые</a>
        <a href="<?= url_for('admin.requests_list', ['status' => 'in_progress', 'q' => $search ?? '', 'from' => $dateFrom ?? '', 'to' => $dateTo ?? '']) ?>"
           class="status-tab <?= ($statusFilter ?? '') === 'in_progress' ? 'status-tab--active' : '' ?>">В работе</a>
        <a href="<?= url_for('admin.requests_list', ['status' => 'completed', 'q' => $search ?? '', 'from' => $dateFrom ?? '', 'to' => $dateTo ?? '']) ?>"
           class="status-tab <?= ($statusFilter ?? '') === 'completed' ? 'status-tab--active' : '' ?>">Готовые</a>
        <a href="<?= url_for('admin.requests_list', ['status' => 'archived', 'q' => $search ?? '', 'from' => $dateFrom ?? '', 'to' => $dateTo ?? '']) ?>"
           class="status-tab <?= ($statusFilter ?? '') === 'archived' ? 'status-tab--active' : '' ?>">Архив</a>
    </div>

    <form method="GET" id="filterForm" class="filter-bar"
        hx-get="<?= url_for('admin.requests_list') ?>"
        hx-target="#requestsTableWrap"
        hx-swap="outerHTML"
        hx-trigger="keyup changed delay:300ms from:#filterSearch, change from:.filter-bar__date, search from:#filterSearch, submit"
        hx-select="#requestsTableWrap"
    >
        <?php if(!empty($statusFilter)): ?><input type="hidden" name="status" value="<?= e($statusFilter) ?>"><?php endif ?>
        <input type="hidden" name="per_page" id="filterPerPage" value="<?= $perPage ?? 10 ?>">
        <input type="search" name="q" id="filterSearch" class="filter-bar__input" placeholder="Поиск по имени, телефону, устройству..." value="<?= e($search ?? '') ?>">
        <input type="date" name="from" class="filter-bar__date" value="<?= e($dateFrom ?? '') ?>" title="Дата с">
        <input type="date" name="to" class="filter-bar__date" value="<?= e($dateTo ?? '') ?>" title="Дата по">
    </form>

    <div class="bulk-bar" :class="selected.length > 0 ? 'bulk-bar--show' : ''">
        <span>Выбрано <strong class="bulk-bar__count" x-text="selected.length"></strong></span>
        <button class="btn btn--primary btn--sm" @click="bulkAction('in_progress')">В работу</button>
        <button class="btn btn--success btn--sm" @click="bulkAction('completed')">Готово</button>
        <button class="btn btn--outline btn--sm" @click="bulkAction('archive')">В архив</button>
        <button class="btn btn--danger btn--sm" @click="bulkAction('delete')" style="margin-left:auto">Удалить</button>
    </div>

    <div class="table-spinner" x-show="$store.loading.active" x-cloak>
        <div class="spinner"></div>
        <span>Загрузка...</span>
    </div>

    <?php include __DIR__ . '/_requests_table.php' ?>

    <div class="modal-overlay" :class="detailOpen ? 'open' : ''"
         @keydown.escape.window="detailOpen = false" style="z-index:2000">
        <div class="modal" @click.away="detailOpen = false" style="max-width:640px">
            <div class="modal__header">
                <div class="modal__title" x-text="detailTitle"></div>
                <button class="modal__close" @click="detailOpen = false">&times;</button>
            </div>
            <div class="modal__body" x-html="detailHtml"></div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
ob_start();
?>
<script>
function requestsApp() {
    return {
        selected: [],
        newCount: <?= (int)($newCount ?? 0) ?>,
        detailOpen: false,
        detailTitle: '',
        detailHtml: '',
        init() {
            this.pollNew();
            document.addEventListener('htmx:afterSwap', () => {
                this.selected = [];
                this.pollNew();
            });
        },
        toggleAll(checked) {
            document.querySelectorAll('.request-checkbox').forEach(cb => {
                if (cb !== this.$el) cb.checked = checked;
            });
            this.selected = checked
                ? Array.from(document.querySelectorAll('.request-checkbox:not([type=hidden])'))
                    .filter(cb => cb.value).map(cb => parseInt(cb.value))
                : [];
        },
        bulkAction(action) {
            if (this.selected.length === 0) return;
            if (action === 'delete' && !confirm('Удалить ' + this.selected.length + ' заявок?')) return;
            const form = new FormData();
            form.append('action', action);
            form.append('ids', this.selected.join(','));
            fetch('<?= url_for("admin.request_bulk") ?>', { method: 'POST', body: form }).then(() => {
                this.selected = [];
                this.refreshTable();
            });
        },
        async doAction(rid, action, extra) {
            const form = new FormData();
            form.append('action', action);
            if (extra) Object.entries(extra).forEach(([k, v]) => form.append(k, v));
            await fetch('/admin/requests/' + rid + '/update', { method: 'POST', body: form });
            this.detailOpen = false;
            this.refreshTable();
        },
        refreshTable() {
            const f = document.getElementById('filterForm');
            if (!f) return;
            const p = new URLSearchParams(new FormData(f));
            ['status', 'q', 'from', 'to', 'per_page'].forEach(k => { if (!p.get(k)) p.delete(k); });
            const u = new URL(window.location);
            if (u.searchParams.get('page')) p.set('page', u.searchParams.get('page'));
            htmx.ajax('GET', '/admin/requests?' + p.toString(), {
                target: '#requestsTableWrap', swap: 'outerHTML', select: '#requestsTableWrap'
            });
        },
        openDetail(id) {
            this.detailTitle = 'Заявка #' + id;
            this.detailHtml = '<div style="text-align:center;padding:32px;color:var(--text-light)"><div class="spinner" style="margin:0 auto 12px"></div>Загрузка...</div>';
            this.detailOpen = true;
            fetch('/admin/requests/' + id).then(r => r.text()).then(html => {
                this.detailHtml = html;
            });
        },
        pollNew() {
            if (this._pollTimer) clearInterval(this._pollTimer);
            this._pollTimer = setInterval(() => {
                fetch('<?= url_for("admin.requests_check") ?>').then(r => r.json()).then(d => {
                    const prev = this.newCount;
                    this.newCount = d.count;
                    if (d.has_new && d.count > prev && prev > 0) {
                        this.showNotification(d.count - prev);
                        this.playSound();
                        this.refreshTable();
                    }
                }).catch(() => {});
            }, 15000);
        },
        showNotification(count) {
            const existing = document.querySelector('.toast-new');
            if (existing) existing.remove();
            const toast = document.createElement('div');
            toast.className = 'toast-new';
            toast.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> <span><strong>+' + count + '</strong> новая заявка</span><button class="toast-new__close" onclick="this.parentElement.remove()">&times;</button>';
            document.body.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 6000);
            toast.addEventListener('click', (e) => { if (e.target.tagName !== 'BUTTON') { this.detailOpen = false; this.refreshTable(); } });
        },
        playSound() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain); gain.connect(ctx.destination);
                osc.frequency.setValueAtTime(800, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(1200, ctx.currentTime + 0.1);
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                osc.start(ctx.currentTime); osc.stop(ctx.currentTime + 0.3);
            } catch(e) {}
        }
    };
}
</script>
<?php
$extra_scripts = ob_get_clean();
include __DIR__ . '/base.php';
?>
