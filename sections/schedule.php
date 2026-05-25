<?php
/**
 * Schedule / Program section
 *
 * Данные программы редактируются в includes/functions.php -> get_schedule().
 * Для добавления пункта: добавьте event в нужный день с полями
 * time / title / speaker (опционально) / location / tag.
 */
$schedule = get_schedule();
$flags = get_feature_flags();
$isEnabled = !empty($flags['schedule_enabled']);

$tagClasses = [
    'Пленарное' => 'tag--plenary',
    'Доклад' => 'tag--report',
    'Мастер-класс' => 'tag--workshop',
    'Круглый стол' => 'tag--roundtable',
    'Секция' => 'tag--section',
    'Дискуссия' => 'tag--discussion',
];

$clockIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
$pinIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>';
?>
<section id="schedule" class="section section--alt" aria-labelledby="schedule-heading">
    <div class="container">
        <div class="text-center">
            <p class="section-label">Программа</p>
            <h2 id="schedule-heading" class="text-balance" style="margin-top:0.75rem;">Программа конференции</h2>
            <p class="section-desc">Пленарные заседания, секции, мастер-классы и дискуссии на протяжении трёх дней.</p>
        </div>

        <?php if (!$isEnabled): ?>
            <div class="section-soon-card" role="status" aria-live="polite" style="margin-top:2rem;">
                <div class="section-soon-card__title">Скоро будет</div>
                <p class="section-soon-card__text">Программа конференции появится в ближайшее время.</p>
            </div>
        <?php else: ?>
            <div class="text-center" style="margin-top:2rem;">
                <a href="#" class="btn btn--secondary" download>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Скачать программу
                </a>
            </div>

            <div class="schedule__tabs" role="tablist">
                <?php foreach ($schedule as $i => $day): ?>
                    <button type="button" role="tab"
                        class="schedule__tab<?= $i === 0 ? ' is-active' : '' ?>"
                        aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"
                    ><?= e($day['label']) ?></button>
                <?php endforeach; ?>
            </div>

            <?php foreach ($schedule as $i => $day): ?>
                <div class="schedule__panel<?= $i === 0 ? ' is-active' : '' ?>" role="tabpanel">
                    <?php foreach ($day['events'] as $event): ?>
                        <div class="schedule__event">
                            <div class="schedule__event-time"><?= e($event['time']) ?></div>
                            <div class="schedule__event-body">
                                <div class="schedule__event-header">
                                    <h3 class="schedule__event-title"><?= e($event['title']) ?></h3>
                                    <?php if (!empty($event['tag'])): ?>
                                        <span class="tag <?= e($tagClasses[$event['tag']] ?? '') ?>"><?= e($event['tag']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($event['speaker'])): ?>
                                    <p class="schedule__event-speaker"><?= e($event['speaker']) ?></p>
                                <?php endif; ?>
                                <div class="schedule__event-meta">
                                    <span class="flex items-center gap-2"><?= $clockIcon ?> <?= e($event['time']) ?></span>
                                    <span class="flex items-center gap-2"><?= $pinIcon ?> <?= e($event['location']) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <p class="schedule__note">Организатор имеет право вносить изменения в программу.</p>
        <?php endif; ?>
    </div>
</section>
