<div class="table-wrap">
    <table class="prices-table" id="prices-table">
        <thead>
            <tr>
                <th>Тип</th>
                <th>Бренд</th>
                <th>Модель</th>
                <th>Услуга</th>
                <th>Цена</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($items)): ?>
                <?php foreach($items as $item): ?>
                <tr>
                    <td><span class="type-badge type-badge--<?= e($item['device_type']) ?>"><?php global $DEVICE_TYPES; echo $DEVICE_TYPES[$item['device_type']] ?? $item['device_type'] ?></span></td>
                    <td class="cell-brand"><?= e($item['brand'] !== '—' ? $item['brand'] : '') ?></td>
                    <td><?= e($item['model_name']) ?></td>
                    <td><?= e($item['service']) ?></td>
                    <td class="cell-price"><?= get_price_display($item['price_from'], $item['price_to'] ?? null) ?></td>
                </tr>
                <?php endforeach ?>
            <?php else: ?>
            <tr>
                <td colspan="5" class="cell-empty">Ничего не найдено. Попробуйте изменить параметры поиска.</td>
            </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>
<div class="prices-table__footer">
    <span>Найдено: <strong><?= count($items ?? []) ?></strong> позиций</span>
    <span class="prices-table__note">Цены ориентировочные. Точная стоимость после диагностики.</span>
</div>
