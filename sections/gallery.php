<?php
/**
 * Photo gallery section (accordion by year + lightbox)
 */
$gallery = get_gallery();
$chevronDown = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>';
?>
<section id="gallery" class="section section--alt" aria-labelledby="gallery-heading">
    <div class="container">
        <div class="text-center">
            <p class="section-label">Галерея</p>
            <h2 id="gallery-heading" class="text-balance" style="margin-top:0.75rem;">Фото и видео с мероприятий</h2>
            <p class="section-desc">Материалы с предыдущих конференций и пленумов.</p>
        </div>

        <div class="gallery__video" style="margin-top:2rem;">
            <video controls preload="metadata" style="width:100%;border-radius:12px;border:1px solid var(--color-border);background:#000;">
                <source src="public/images/2024.mp4" type="video/mp4">
                Ваш браузер не поддерживает видео.
            </video>
        </div>

        <div class="gallery__accordion" style="margin-top:1.5rem;">
            <?php foreach ($gallery as $yearData): ?>
                <div class="gallery__year">
                    <button type="button" class="gallery__year-btn" aria-expanded="false">
                        <span><?= e($yearData['year']) ?></span>
                        <?= $chevronDown ?>
                    </button>
                    <div class="gallery__images">
                        <?php foreach ($yearData['images'] as $image): ?>
                            <div class="gallery__thumb" role="button" tabindex="0" aria-label="Увеличить: <?= e($image['alt']) ?>">
                                <img src="<?= e($image['src']) ?>" alt="<?= e($image['alt']) ?>" loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="lightbox" role="dialog" aria-modal="true" aria-label="Просмотр фотографии">
    <button type="button" class="lightbox__close" aria-label="Закрыть">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <button type="button" class="lightbox__arrow lightbox__arrow--prev" aria-label="Предыдущее фото">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <img class="lightbox__img" src="" alt="">
    <button type="button" class="lightbox__arrow lightbox__arrow--next" aria-label="Следующее фото">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <p class="lightbox__counter"></p>
</div>
