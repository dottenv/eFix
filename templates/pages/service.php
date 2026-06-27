<section class="service-detail">
    <div class="container">
        <h1 class="service-detail__title"><?= $this->escape($service['title']) ?></h1>
        <div class="service-detail__description">
            <?= nl2br($this->escape($service['description'])) ?>
        </div>
        <a href="/#order" class="btn btn--primary">Оставить заявку</a>
    </div>
</section>
