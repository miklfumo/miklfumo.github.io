<?php
/**
 * Goals / clusters section
 *
 * Чтобы добавить/изменить направление:
 * 1) отредактируйте массив $clusters ниже,
 * 2) добавьте title / icon / description,
 * 3) карточка появится автоматически.
 */
$clusters = [
    [
        'title' => 'Государственный кластер',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-4h6v4"/></svg>',
        'description' => 'Минобрнауки, Минпросвещения, СБ РФ, Минцифры, Доктрина ИБ РФ, стратегия образования до 2040, модель ВО, поручение Президента РФ № Пр–2330, концепция подготовки кадров ИБ до 2035.',
    ],
    [
        'title' => 'Образовательный кластер',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 4 3 6 3s6-1 6-3v-5"/></svg>',
        'description' => 'Непрерывное образование ИБ, ФГОС ВО и СПО нового поколения, инфраструктура вузов, участие индустрии, компетенции преподавателей, ДПО, научная кооперация, круглый стол.',
    ],
    [
        'title' => 'Промышленно-производственный кластер',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09a1.65 1.65 0 00-1.08-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09a1.65 1.65 0 001.51-1.08 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9c.26.604.852.997 1.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1.08z"/></svg>',
        'description' => 'Кадровые потребности отраслей КИИ по 187-ФЗ, целевой набор, трудоустройство, профстандарты, аттестация, позиция Минтруда и СПК-ИТ.',
    ],
];
?>
<section id="goals" class="section section--alt" aria-labelledby="goals-heading">
    <div class="container">
        <div class="text-center">
            <p class="section-label">Ключевые направления</p>
            <h2 id="goals-heading" class="text-balance" style="margin-top:0.75rem;">Цели конференции</h2>
            <p class="section-desc">Основные направления работы юбилейного Пленума ФУМО ВО ИБ.</p>
        </div>

        <div class="goals__grid">
            <?php foreach ($clusters as $cluster): ?>
                <article class="card">
                    <div class="icon-box"><?= $cluster['icon'] ?></div>
                    <h3 class="goals__card-title"><?= e($cluster['title']) ?></h3>
                    <p class="goals__card-desc"><?= $cluster['description'] ?></p>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="goals__exhibition">
            В рамках Конференции и Пленума запланировано проведение выставки продукции производителей
            средств защиты информации и мастер-классов по применению технологических решений
            в области информационной безопасности.
        </div>
    </div>
</section>
