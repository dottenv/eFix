<?php
/**
 * Request detail modal content (loaded via fetch)
 * Variable: $request - ContactRequest array
 */
$r = $request;
?>
<div class="modal-detail" x-data="{ tab: 'info' }">
    <div class="modal-detail__header">
        <h3 class="modal-detail__title">Заявка #<?= $r['id'] ?></h3>
        <span class="badge badge--<?= e($r['status']) ?>" style="font-size:12px"><?= e(status_label($r['status'])) ?></span>
    </div>

    <div class="modal-detail__tabs">
        <button class="modal-detail__tab" :class="tab === 'info' ? 'modal-detail__tab--active' : ''" @click="tab = 'info'">Информация</button>
        <button class="modal-detail__tab" :class="tab === 'notes' ? 'modal-detail__tab--active' : ''" @click="tab = 'notes'">Заметки</button>
        <button class="modal-detail__tab" :class="tab === 'history' ? 'modal-detail__tab--active' : ''" @click="tab = 'history'">Действия</button>
    </div>

    <div x-show="tab === 'info'" class="modal-detail__body">
        <div class="modal-detail__grid">
            <div class="modal-detail__field">
                <span class="modal-detail__label">Имя</span>
                <span class="modal-detail__value"><?= e($r['name']) ?></span>
            </div>
            <div class="modal-detail__field">
                <span class="modal-detail__label">Телефон</span>
                <a href="tel:<?= e($r['phone']) ?>" class="modal-detail__value modal-detail__value--link"><?= e($r['phone']) ?></a>
            </div>
            <div class="modal-detail__field">
                <span class="modal-detail__label">Тип устройства</span>
                <span class="modal-detail__value"><?= e(device_label($r['device_type'] ?? '')) ?: '—' ?></span>
            </div>
            <div class="modal-detail__field">
                <span class="modal-detail__label">Модель</span>
                <span class="modal-detail__value"><?= e($r['device_model'] ?? '—') ?></span>
            </div>
            <div class="modal-detail__field">
                <span class="modal-detail__label">Дата</span>
                <span class="modal-detail__value"><?= format_datetime($r['created_at']) ?></span>
            </div>
            <div class="modal-detail__field">
                <span class="modal-detail__label">Описание проблемы</span>
                <p class="modal-detail__value"><?= e($r['message'] ?? '—') ?></p>
            </div>
        </div>
        <div class="modal-detail__actions">
            <?php if($r['status'] === 'new'): ?>
            <button class="btn btn--primary btn--sm" @click="doAction(<?= $r['id'] ?>, 'status', {status: 'in_progress'})">Взять в работу</button>
            <?php endif ?>
            <?php if($r['status'] === 'in_progress'): ?>
            <button class="btn btn--success btn--sm" @click="doAction(<?= $r['id'] ?>, 'status', {status: 'completed'})">Готово</button>
            <?php endif ?>
            <?php if($r['status'] !== 'archived'): ?>
            <button class="btn btn--outline btn--sm" @click="doAction(<?= $r['id'] ?>, 'archive')">В архив</button>
            <?php endif ?>
        </div>
    </div>

    <div x-show="tab === 'notes'" class="modal-detail__body">
        <form x-data @submit.prevent="doAction(<?= $r['id'] ?>, 'notes', {admin_notes: $event.target.admin_notes.value})">
            <textarea name="admin_notes" rows="6" style="width:100%;padding:12px;border:2px solid var(--border);border-radius:var(--radius-sm);font-family:var(--font);font-size:14px;resize:vertical"><?= e($r['admin_notes'] ?? '') ?></textarea>
            <div style="display:flex;gap:8px;margin-top:12px">
                <button type="submit" class="btn btn--primary btn--sm">Сохранить</button>
            </div>
            <p class="text-muted text-sm mt-2">Заметки видны только вам</p>
        </form>
    </div>

    <div x-show="tab === 'history'" class="modal-detail__body">
        <div style="display:flex;gap:8px;flex-direction:column">
            <?php if($r['status'] === 'archived'): ?>
            <button class="btn btn--outline btn--sm" @click="doAction(<?= $r['id'] ?>, 'restore')">Восстановить из архива</button>
            <?php else: ?>
            <button class="btn btn--outline btn--sm" @click="doAction(<?= $r['id'] ?>, 'archive')">Архивировать заявку</button>
            <?php endif ?>
        </div>
    </div>
</div>
