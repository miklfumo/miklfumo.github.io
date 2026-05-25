<?php
/**
 * Speakers carousel section
 *
 * Данные спикеров редактируются в includes/functions.php -> get_speakers().
 * Для добавления нового спикера: добавьте элемент массива с полями name/role/topic.
 */
$speakers = get_speakers();
$flags = get_feature_flags();
$isEnabled = !empty($flags['speakers_enabled']);
$userIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
?>
<section id="speakers" class="section" aria-labelledby="speakers-heading">
    <div class="container">
        <div class="text-center">
            <p class="section-label">Спикеры</p>
            <h2 id="speakers-heading" class="text-balance" style="margin-top:0.75rem;">Ключевые докладчики</h2>
            <p class="section-desc">Представители образовательных организаций, регуляторов, органов власти и отраслевых компаний.</p>
        </div>

        <?php if (!$isEnabled): ?>
            <div class="section-soon-card" role="status" aria-live="polite">
                <div class="section-soon-card__title">Скоро будет</div>
                <p class="section-soon-card__text">Раздел со спикерами готовится к публикации.</p>
            </div>
        <?php else: ?>
        <div class="speakers__carousel">
            <button type="button" class="speakers__arrow speakers__arrow--prev" aria-label="Предыдущий спикер">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button type="button" class="speakers__arrow speakers__arrow--next" aria-label="Следующий спикер">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>

            <div class="speakers__track">
                <?php foreach ($speakers as $speaker): ?>
                    <article class="speakers__card">
                        <div class="speakers__avatar"><?= $userIcon ?></div>
                        <h3 class="speakers__name"><?= e($speaker['name']) ?></h3>
                        <p class="speakers__role"><?= e($speaker['role']) ?></p>
                        <p class="speakers__topic"><?= e($speaker['topic']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="speakers__dots">
                <?php foreach ($speakers as $i => $s): ?>
                    <button type="button" class="speakers__dot<?= $i === 0 ? ' is-active' : '' ?>" aria-label="Перейти к спикеру <?= $i + 1 ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
