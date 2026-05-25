<?php
/**
 * Partners & Organizers section
 *
 * Быстрое добавление логотипов:
 * 1) добавьте элемент в нужную группу в массиве $groups,
 * 2) укажите name, logo (путь к файлу), url (опционально),
 * 3) карточка появится автоматически.
 */
$groups = [
    [
        'title' => 'Организаторы',
        'items' => [
            ['name' => 'ФУМО ВО ИБ', 'logo' => 'public/images/logos/Logo.png', 'url' => 'http://xn--90anlixf.xn--p1ai/'],
            ['name' => 'ФУМО СПО ИБ', 'logo' => 'public/images/logos/Logo.png', 'url' => 'http://xn--90anlixf.xn--p1ai/'],
            ['name' => 'СПК-ИТ', 'logo' => 'public/images/logos/spkit.png', 'url' => 'https://spk-it.ru/'],
        ],
    ],
    [
        'title' => 'Соорганизаторы',
        'items' => [
            ['name' => 'ФСТЭК России', 'logo' => 'public/images/logos/fstek.png', 'url' => 'https://fstec.ru'],
            ['name' => 'Минобрнауки', 'logo' => 'public/images/minobr.png', 'url' => 'https://minobrnauki.gov.ru/'],
            ['name' => 'РТУ МИРЭА', 'logo' => 'public/images/logos/MIREA_Gerb_Colour.png', 'url' => 'https://www.mirea.ru/'],
        ],
    ],
    [
        'title' => 'Партнёры',
        'items' => [
            ['name' => 'АНО НТЦ ЦК', 'logo' => 'public/images/logos/NTCCK.png', 'url' => 'https://digitalcryptography.ru/'],
            ['name' => 'АЗИ', 'logo' => 'public/images/logos/AZI.png'],
            ['name' => 'ГК «ИнфоТеКС»', 'logo' => 'public/images/logos/infotecs.png', 'url' => 'https://infotecs.ru/'],
        ],
    ],
    [
        'title' => 'При участии',
        'items' => [
            ['name' => 'ФСБ России', 'logo' => 'public/images/logos/FSB.png', 'url' => 'http://www.fsb.ru/'],
            ['name' => 'Аппарат СБ России', 'logo' => 'public/images/logos/SBRF.png', 'url' => 'http://www.scrf.gov.ru/'],
        ],
    ],
    [
        'title' => 'Оператор',
        'items' => [
            ['name' => 'ООО «Академия "Профи Скиллс"»', 'logo' => 'public/images/logos/Profiskills.png', 'url' => 'https://academyprofiskills.ru/ob-organizacii/'],
        ],
    ],
];
?>
<section id="partners" class="section" aria-labelledby="partners-heading">
    <div class="container">
        <div class="text-center">
            <p class="section-label">Партнёры и организаторы</p>
            <h2 id="partners-heading" class="text-balance" style="margin-top:0.75rem;">Организаторы и партнёры</h2>
        </div>

        <?php foreach ($groups as $group): ?>
            <div class="partners__group">
                <h3 class="partners__group-title"><?= e($group['title']) ?></h3>
                <div class="partners__grid">
                    <?php foreach ($group['items'] as $item): ?>
                        <?php if (!empty($item['url'])): ?>
                            <a class="partners__item" href="<?= e($item['url']) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e($item['name']) ?> — перейти на сайт">
                                <img src="<?= e($item['logo']) ?>" alt="<?= e($item['name']) ?>" class="partners__logo" loading="lazy" decoding="async">
                                <span class="partners__item-name"><?= e($item['name']) ?></span>
                            </a>
                        <?php else: ?>
                            <div class="partners__item">
                                <img src="<?= e($item['logo']) ?>" alt="<?= e($item['name']) ?>" class="partners__logo" loading="lazy" decoding="async">
                                <span class="partners__item-name"><?= e($item['name']) ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="text-center" style="margin-top:3rem;">
            <a href="#registration" class="btn btn--primary">Стать партнёром</a>
        </div>
    </div>
</section>
