"use client";

import { useState, useCallback } from "react";

const speakers = [
  { name: "Иванов А.Б.", role: "ФСТЭК России", topic: "Актуальные требования к подготовке кадров ИБ" },
  { name: "Петрова В.Г.", role: "Минобрнауки России", topic: "Государственная политика в сфере образования ИБ" },
  { name: "Сидоров Д.Е.", role: "РТУ МИРЭА", topic: "Модернизация образовательных программ ИБ" },
  { name: "Козлова И.К.", role: "ГК ИнфоТеКС", topic: "Потребности индустрии в специалистах ИБ" },
  { name: "Николаев С.М.", role: "СПК-ИТ", topic: "Профессиональные стандарты и квалификации" },
  { name: "Алексеева Н.П.", role: "АЗИ", topic: "Международное сотрудничество в подготовке кадров" },
  { name: "Михайлов О.Р.", role: "ФСБ России", topic: "Криптографическая подготовка специалистов" },
  { name: "Кузнецова Т.С.", role: "АНО НТЦ ЦК", topic: "Центры компетенций: опыт и перспективы" },
];

const UserIcon = () => (
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" /><circle cx="12" cy="7" r="4" /></svg>
);

const CARDS_PER_PAGE = 4;

export function SpeakersSection() {
  const [page, setPage] = useState(0);
  const totalPages = Math.ceil(speakers.length / CARDS_PER_PAGE);

  const prev = useCallback(() => setPage((p) => Math.max(0, p - 1)), []);
  const next = useCallback(() => setPage((p) => Math.min(totalPages - 1, p + 1)), [totalPages]);

  const visible = speakers.slice(page * CARDS_PER_PAGE, (page + 1) * CARDS_PER_PAGE);

  return (
    <section id="speakers" className="section" aria-labelledby="speakers-heading">
      <div className="container">
        <div className="text-center">
          <p className="section-label">{"Спикеры"}</p>
          <h2 id="speakers-heading" className="text-balance" style={{ marginTop: "0.75rem" }}>
            {"Ключевые докладчики"}
          </h2>
          <p className="section-desc">
            {"Представители образовательных организаций, регуляторов, органов власти и отраслевых компаний."}
          </p>
        </div>

        <div className="speakers__carousel">
          <button type="button" className="speakers__arrow speakers__arrow--prev" aria-label="Предыдущий спикер" onClick={prev} disabled={page === 0}>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="15 18 9 12 15 6" /></svg>
          </button>
          <button type="button" className="speakers__arrow speakers__arrow--next" aria-label="Следующий спикер" onClick={next} disabled={page === totalPages - 1}>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polyline points="9 18 15 12 9 6" /></svg>
          </button>

          <div className="speakers__track">
            {visible.map((speaker) => (
              <article key={speaker.name} className="speakers__card">
                <div className="speakers__avatar"><UserIcon /></div>
                <h3 className="speakers__name">{speaker.name}</h3>
                <p className="speakers__role">{speaker.role}</p>
                <p className="speakers__topic">{speaker.topic}</p>
              </article>
            ))}
          </div>

          <div className="speakers__dots">
            {Array.from({ length: totalPages }).map((_, i) => (
              <button
                key={i}
                type="button"
                className={`speakers__dot${i === page ? " is-active" : ""}`}
                aria-label={`Перейти к странице ${i + 1}`}
                onClick={() => setPage(i)}
              />
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}
