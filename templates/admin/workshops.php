<?php
$title = 'Мастерские — ' . ($site_name ?? 'eFix') . ' Admin';
$header = 'Партнёрские мастерские';
$extra_head = <<<HTML
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
<style>
    .map-preview{height:220px;border-radius:var(--radius-sm);border:2px solid var(--border);margin-bottom:12px;z-index:1}
    .geo-btn{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--bg);cursor:pointer;font-size:12px;font-weight:600;color:var(--text-muted);transition:all .2s}
    .geo-btn:hover{border-color:var(--accent);color:var(--accent);background:rgba(255,107,53,.06)}
    .geo-btn:disabled{opacity:.5;cursor:wait}
    .coords-hint{font-size:11px;color:var(--text-light);margin-top:2px}
    .modal__body .leaflet-container{z-index:1}
    .address-wrap{position:relative}
    .address-suggestions{
        position:absolute;top:100%;left:0;right:0;z-index:100;
        background:var(--surface);border:1px solid var(--border);
        border-radius:0 0 var(--radius-sm) var(--radius-sm);
        max-height:200px;overflow-y:auto;box-shadow:var(--shadow)
    }
    .address-suggestions__item{
        padding:10px 14px;cursor:pointer;font-size:13px;
        border-bottom:1px solid var(--border);transition:background .15s
    }
    .address-suggestions__item:last-child{border-bottom:none}
    .address-suggestions__item:hover{background:rgba(255,107,53,.06);color:var(--accent)}
    .address-suggestions__item small{display:block;font-size:11px;color:var(--text-light);margin-top:2px}
    #workshopModal.modal-overlay{display:flex}
</style>
HTML;
ob_start();
?>
<div x-data="workshopForm()">
<div class="card">
    <div class="card__header">
        <h2 class="card__title">Мастерские на карте</h2>
        <button class="btn btn--primary" @click="openAdd()">+ Добавить мастерскую</button>
    </div>
    <div x-data="bulkDelete()" class="table-wrap">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px" x-show="anySelected" x-cloak>
            <span class="text-sm" style="color:var(--text-muted)">Выбрано: <strong x-text="selectedCount"></strong></span>
            <button class="btn btn--danger btn--sm" @click="deleteSelected('<?= url_for('admin.workshops', ['page' => $pagination['page'] ?? 1, 'per_page' => $perPage ?? 10]) ?>')">Удалить выбранные</button>
        </div>
        <div id="workshops-table-container">
            <?php include __DIR__ . '/_workshops_container.php' ?>
        </div>
    </div>
</div>

<div class="modal-overlay" id="workshopModal" x-show="open" x-cloak
     @keydown.escape.window="close"
     @click.self="close"
     x-transition:enter.opacity.duration.200
     x-transition:leave.opacity.duration.200
>
    <div class="modal" style="max-width:600px"
         x-show="open"
         x-transition:enter="modal-enter"
         x-transition:enter-start="modal-enter-start"
         x-transition:enter-end="modal-enter-end"
         x-transition:leave="modal-leave"
         x-transition:leave-start="modal-leave-start"
         x-transition:leave-end="modal-leave-end"
    >
        <div class="modal__header">
            <h3 class="modal__title" x-text="isEdit ? 'Редактировать мастерскую' : 'Добавить мастерскую'"></h3>
            <button class="modal__close" @click="close" aria-label="Закрыть">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="modal__body">
            <form method="POST" hx-post="<?= url_for('admin.workshops', ['page' => $pagination['page'] ?? 1, 'per_page' => $perPage ?? 10]) ?>" hx-target="#workshops-table-container" hx-swap="innerHTML" @htmx:after-request="if(event.detail.successful) close()">
                <input type="hidden" name="action" :value="isEdit ? 'edit' : 'add'">
                <input type="hidden" name="id" x-model="editId">
                <div class="form-group">
                    <label>Название</label>
                    <input name="name" x-model="name" placeholder="Сервис на Ленина">
                </div>
                <div class="form-group">
                    <label>Адрес</label>
                    <div class="address-wrap">
                        <input name="address" x-model="address" @input.debounce.300ms="searchAddress" @blur="hideSuggestions" @focus="showSuggestions" placeholder="Троллейная 130а, Новосибирск" autocomplete="off">
                        <div class="address-suggestions" x-show="suggestions.length > 0 && showDropdown">
                            <template x-for="(s, i) in suggestions" :key="i">
                                <div class="address-suggestions__item" @mousedown.prevent="selectSuggestion(s)">
                                    <span x-text="s.display_name.split(',')[0]"></span>
                                    <small x-text="s.display_name.split(',').slice(1).join(',').trim()"></small>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="map-preview" x-ref="mapEl"></div>
                <div style="display:flex;gap:16px;align-items:center;margin-bottom:12px">
                    <span class="coords-hint" x-text="'' + lat.toFixed(6) + ', ' + lng.toFixed(6)"></span>
                    <span class="coords-hint">(перетащите метку)</span>
                </div>
                <input type="hidden" name="lat" x-model.number="lat">
                <input type="hidden" name="lng" x-model.number="lng">
                <div class="form-group">
                    <label>Телефон</label>
                    <input name="phone" x-model="phone" placeholder="+7 (999) 999-99-99">
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" x-model="desc" rows="2" placeholder="Телефоны, планшеты, ноутбуки"></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn--outline" @click="close">Отмена</button>
                    <button type="submit" class="btn btn--primary" x-text="isEdit ? 'Сохранить' : 'Добавить'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<?php
$content = ob_get_clean();
ob_start();
?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
document.addEventListener('htmx:afterSwap', (e) => {
    try { if (typeof Alpine !== 'undefined' && e.detail.target) Alpine.initTree(e.detail.target); } catch(ex) {}
});

document.addEventListener('alpine:init', () => {
    Alpine.data('workshopForm', () => ({
        open: false,
        isEdit: false,
        editId: 0,
        name: '',
        address: '',
        lat: 55.0084,
        lng: 82.9357,
        phone: '',
        desc: '',
        geocoding: false,
        suggestions: [],
        showDropdown: false,
        _map: null,
        _marker: null,
        initMap() {
            this.$nextTick(() => {
                const el = this.$refs.mapEl;
                if (!el) return;
                if (this._map) { this._map.invalidateSize(); return; }
                this._map = L.map(el, { zoomControl: false }).setView([this.lat, this.lng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OSM', maxZoom: 18
                }).addTo(this._map);
                this._marker = L.marker([this.lat, this.lng], { draggable: true }).addTo(this._map);
                this._marker.on('dragend', (e) => {
                    const p = e.target.getLatLng();
                    this.lat = p.lat; this.lng = p.lng;
                });
                this._map.on('click', (e) => {
                    this._marker.setLatLng(e.latlng);
                    this.lat = e.latlng.lat; this.lng = e.latlng.lng;
                });
            });
        },
        updateMarker(lat, lng) {
            if (this._map && this._marker) {
                this._map.setView([lat, lng], 16);
                this._marker.setLatLng([lat, lng]);
            }
        },
        openAdd() {
            this.isEdit = false; this.editId = 0;
            this.name = ''; this.address = ''; this.phone = ''; this.desc = '';
            this.lat = 55.0084; this.lng = 82.9357;
            this.suggestions = []; this.showDropdown = false;
            this.open = true;
            this.initMap();
        },
        openEdit(id, name, address, lat, lng, phone, desc) {
            this.isEdit = true; this.editId = id;
            this.name = name || ''; this.address = address || '';
            this.lat = lat; this.lng = lng;
            this.phone = phone || ''; this.desc = desc || '';
            this.suggestions = []; this.showDropdown = false;
            this.open = true;
            this.initMap();
            this.updateMarker(lat, lng);
        },
        close() {
            this.open = false;
        },
        searchAddress() {
            const q = this.address.trim();
            if (q.length < 2) { this.suggestions = []; this.showDropdown = false; return; }
            fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(q + ', Новосибирск')}&format=json&limit=5`)
                .then(r => r.json())
                .then(data => { this.suggestions = data.filter(s => s.lat && s.lon); this.showDropdown = this.suggestions.length > 0; })
                .catch(() => { this.suggestions = []; });
        },
        selectSuggestion(s) {
            this.address = s.display_name;
            this.lat = parseFloat(s.lat); this.lng = parseFloat(s.lon);
            this.updateMarker(this.lat, this.lng);
            if (!this.name.trim()) this.name = s.display_name.split(',')[0].trim();
            this.suggestions = []; this.showDropdown = false;
        },
        showSuggestions() { if (this.suggestions.length > 0) this.showDropdown = true; },
        hideSuggestions() { setTimeout(() => { this.showDropdown = false; }, 200); },
        geocodeExact() {
            if (!this.address.trim()) return;
            this.geocoding = true;
            const q = encodeURIComponent(this.address + ', Новосибирск');
            fetch(`https://nominatim.openstreetmap.org/search?q=${q}&format=json&limit=1`)
                .then(r => r.json())
                .then(data => {
                    if (data.length > 0) {
                        const s = data[0];
                        this.address = s.display_name;
                        this.lat = parseFloat(s.lat); this.lng = parseFloat(s.lon);
                        this.updateMarker(this.lat, this.lng);
                        if (!this.name.trim()) this.name = s.display_name.split(',')[0].trim();
                    } else { alert('Адрес не найден.'); }
                })
                .catch(() => alert('Ошибка геокодирования.'))
                .finally(() => { this.geocoding = false; });
        }
    }));
});
</script>
<?php
$extra_scripts = ob_get_clean();
include __DIR__ . '/base.php';
?>
