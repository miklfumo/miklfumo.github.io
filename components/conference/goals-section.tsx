const clusters = [
  {
    title: "Государственный кластер",
    icon: (
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M3 21h18" /><path d="M5 21V7l7-4 7 4v14" /><path d="M9 21v-4h6v4" /></svg>
    ),
    subtitle: "Позиция Минобрнауки, Минпросвещения, СБ РФ, Минцифры",
    items: [
      "Вызовы в области образования и в информационной сфере.",
      "Доктрина ИБ РФ, Стратегия развития образования в РФ до 2040 г.",
      "Новая модель высшего образования.",
      "Концепция подготовки, профпереподготовки и повышения квалификации кадров в области обеспечения ИБ РФ на период до 2035 и её реализация.",
      "Реализация Поручения Президента Российской Федерации от 6.12.2022 \u2116 Пр\u20132330.",
    ],
  },
  {
    title: "Образовательный кластер",
    icon: (
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z" /><path d="M6 12v5c0 2 4 3 6 3s6-1 6-3v-5" /></svg>
    ),
    subtitle: "Позиция образовательных организаций и представителей индустрии ИБ",
    items: [
      "Модель и траектории непрерывного образования в области ИБ, облик выпускника вуза и колледжа \u2013 2035.",
      "Проект ФГОС ВО нового поколения и ФГОС СПО в области ИБ.",
      "Проектирование опережающих практико-ориентированных образовательных программ в интересах работодателей и бизнес-партнёров.",
      "Подходы к реализации пилотного проекта новой модели высшего образования.",
      "Состояние и развитие инфраструктуры образовательных организаций, материально-технического обеспечения выпускающих кафедр по ИБ.",
      "Формы участия представителей индустрии ИБ в образовательном процессе.",
      "Проблемы профессиональной и научной компетентности преподавательского состава, вопросы мотивации, обучения и закрепления педагогических кадров ИБ.",
      "Развитие научного потенциала и научных исследований выпускающих кафедр. Научная кооперация кафедр ИБ и производителей средств защиты информации и вендоров.",
      "Круглый стол: Состояние и качество ДПО (проблемы, опыт, решения).",
      "Формирование сквозных компетенций и актуализация дополнительных профессиональных программ ИБ.",
    ],
  },
  {
    title: "Промышленно-производственный кластер",
    icon: (
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="3" /><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09a1.65 1.65 0 00-1.08-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09a1.65 1.65 0 001.51-1.08 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9c.26.604.852.997 1.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1.08z" /></svg>
    ),
    subtitle: "Кадровые потребности отраслей и субъектов КИИ",
    items: [
      "Кадровые потребности отраслей и субъектов КИИ по 187-ФЗ.",
      "Целевой набор, трудоустройство, развитие молодых специалистов.",
      "Профессиональные стандарты и аттестация работников.",
      "Позиция Минтруда и СПК-ИТ.",
      "Практический опыт предприятий по выстраиванию системы кадрового обеспечения ИБ.",
    ],
  },
  {
    title: "Профориентационный кластер",
    icon: (
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 00-3-3.87" /><path d="M16 3.13a4 4 0 010 7.75" /></svg>
    ),
    subtitle: "\u00ABПрофессия \u2014 защитник информации\u00BB",
    items: [
      "Профориентационные квесты и мастер-классы для школьников и студентов.",
      "Карьерные треки и стажировки в области ИБ.",
      "Наставничество и взаимодействие с работодателями.",
      "Ярмарка вакансий.",
    ],
  },
];

export function GoalsSection() {
  return (
    <section id="goals" className="section section--alt" aria-labelledby="goals-heading">
      <div className="container">
        <div className="text-center">
          <p className="section-label">{"Ключевые направления"}</p>
          <h2 id="goals-heading" className="text-balance" style={{ marginTop: "0.75rem" }}>
            {"Кластеры конференции"}
          </h2>
          <p className="section-desc">
            {"Работа юбилейного Пленума ФУМО ВО ИБ организована по четырём тематическим кластерам."}
          </p>
        </div>

        <div className="goals__grid" style={{ gridTemplateColumns: "repeat(1, 1fr)" }}>
          {clusters.map((cluster) => (
            <article key={cluster.title} className="card" style={{ padding: "1.25rem 1.25rem", overflow: "hidden" }}>
              <div style={{ display: "flex", alignItems: "flex-start", gap: "0.75rem" }}>
                <div className="icon-box" style={{ marginTop: "0.125rem" }}>{cluster.icon}</div>
                <div style={{ flex: 1, minWidth: 0, overflow: "hidden" }}>
                  <h3 className="goals__card-title" style={{ marginTop: 0, wordBreak: "break-word" }}>{cluster.title}</h3>
                  <p style={{ marginTop: "0.25rem", fontSize: "0.875rem", color: "var(--color-primary)", opacity: 0.8 }}>
                    {cluster.subtitle}
                  </p>
                  <ul style={{ marginTop: "0.75rem", display: "flex", flexDirection: "column", gap: "0.375rem" }}>
                    {cluster.items.map((item, i) => (
                      <li key={i} style={{ display: "flex", alignItems: "baseline", gap: "0.5rem", fontSize: "0.8125rem", color: "var(--color-fg-muted)", lineHeight: 1.6, overflowWrap: "break-word", wordBreak: "break-word" }}>
                        <span style={{ width: "0.375rem", height: "0.375rem", borderRadius: "9999px", backgroundColor: "var(--color-primary)", flexShrink: 0, marginTop: "0.5rem" }} />
                        <span style={{ minWidth: 0 }}>{item}</span>
                      </li>
                    ))}
                  </ul>
                </div>
              </div>
            </article>
          ))}
        </div>

        <div className="goals__exhibition">
          {"В рамках Конференции и Пленума запланировано проведение выставки продукции производителей средств защиты информации и мастер-классов по применению технологических решений в области информационной безопасности."}
        </div>
      </div>
    </section>
  );
}
