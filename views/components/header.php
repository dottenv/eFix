<header class="header" x-data="{ mobileOpen: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 50">
    <div class="container">
        <a href="/" class="logo">eFix<span>.</span></a>
        <nav class="nav" :class="{ 'open': mobileOpen }">
            <a href="/">Главная</a>
            <a href="/services">Услуги</a>
            <a href="/prices">Цены</a>
            <a href="/about">О нас</a>
            <a href="/contacts">Контакты</a>
        </nav>
        <div class="header__cta">
            <a href="#" class="btn btn--primary" @click.prevent="$dispatch('open-modal')">Заказать звонок</a>
        </div>
        <button class="hamburger" @click="mobileOpen = !mobileOpen" :class="{ 'active': mobileOpen }">
            <span></span>
        </button>
    </div>
</header>
