<aside class="sidebar" x-data="{ collapsed: false }">
    <div class="sidebar__header">
        <a href="/admin/dashboard">eFix Admin</a>
        <button @click="collapsed = !collapsed">|||</button>
    </div>
    <nav class="sidebar__nav" :class="{ collapsed }">
        <a href="/admin/dashboard">Дашборд</a>
        <a href="/admin/site">Контент</a>
        <a href="/admin/services">Услуги</a>
        <a href="/admin/prices">Прайс-лист</a>
        <a href="/admin/requests" hx-trigger="load, every 30s" hx-get="/admin/requests/count">
            Заявки <span class="badge" id="new-requests-count">0</span>
        </a>
        <a href="/admin/workshops">Мастерские</a>
        <a href="/admin/stats">Аналитика</a>
        <a href="/admin/mail-config">SMTP</a>
        <a href="/admin/mail-templates">Шаблоны писем</a>
        <a href="/admin/settings">Настройки</a>
        <a href="/admin/logout">Выход</a>
    </nav>
</aside>
