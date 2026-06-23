<header class="header" x-data="{ mobileOpen: false }">
    <div class="container">
        <a href="/" class="logo">eFix</a>
        <nav class="nav" :class="{ 'open': mobileOpen }">
            <a href="/">Главная</a>
            <a href="/services">Услуги</a>
            <a href="/prices">Цены</a>
            <a href="/about">О нас</a>
            <a href="/contacts">Контакты</a>
        </nav>
        <a href="#" class="btn btn--accent" @click.prevent="$dispatch('open-modal')">Заказать звонок</a>
        <button class="hamburger" @click="mobileOpen = !mobileOpen" :class="{ 'active': mobileOpen }">
            <span></span>
        </button>
    </div>
</header>
