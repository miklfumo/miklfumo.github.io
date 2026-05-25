"use client";

import { useState } from "react";

const tagClasses: Record<string, string> = {
  "Пленарное": "tag--plenary",
  "Доклад": "tag--report",
  "Мастер-класс": "tag--workshop",
  "Круглый стол": "tag--roundtable",
  "Секция": "tag--section",
  "Дискуссия": "tag--discussion",
};

interface ScheduleEvent {
  time: string;
  title: string;
  speaker?: string;
  location: string;
  tag?: string;
}

interface ScheduleDay {
  label: string;
  events: ScheduleEvent[];
}

const schedule: ScheduleDay[] = [
  {
    label: "28 октября",
    events: [
      { time: "09:00", title: "Регистрация участников", location: "Фойе, 1 этаж", tag: "" },
      { time: "10:00", title: "Торжественное открытие XXX Пленума ФУМО ВО ИБ", speaker: "Председатель ФУМО ВО ИБ", location: "Актовый зал", tag: "Пленарное" },
      { time: "10:30", title: "Государственный кластер: Доктрина ИБ РФ и кадровая политика", speaker: "Представитель СБ РФ", location: "Актовый зал", tag: "Доклад" },
      { time: "11:15", title: "Стратегия развития образования в РФ до 2040 г.", speaker: "Минобрнауки России", location: "Актовый зал", tag: "Доклад" },
      { time: "12:00", title: "Обеденный перерыв", location: "Столовая, 2 этаж" },
      { time: "13:30", title: "Концепция подготовки кадров ИБ до 2035 и реализация Поручения Президента РФ \u2116 Пр\u20132330", speaker: "Минцифры России", location: "Актовый зал", tag: "Секция" },
      { time: "15:00", title: "Новая модель высшего образования: подходы к реализации пилотного проекта", speaker: "Минобрнауки России", location: "Зал А", tag: "Дискуссия" },
      { time: "17:00", title: "Круглый стол: Взаимодействие ФОИВ и образовательных организаций", location: "Зал Б", tag: "Круглый стол" },
    ],
  },
  {
    label: "29 октября",
    events: [
      { time: "09:30", title: "Образовательный кластер: ФГОС ВО и СПО нового поколения в области ИБ", speaker: "Рабочая группа ФУМО", location: "Зал А", tag: "Секция" },
      { time: "10:30", title: "Проектирование опережающих образовательных программ в интересах работодателей", speaker: "Представители индустрии ИБ", location: "Зал А", tag: "Доклад" },
      { time: "11:30", title: "Инфраструктура вузов и материально-техническое обеспечение кафедр ИБ", location: "Зал Б", tag: "Секция" },
      { time: "12:30", title: "Обеденный перерыв", location: "Столовая, 2 этаж" },
      { time: "14:00", title: "Промышленно-производственный кластер: Кадровые потребности отраслей и субъектов КИИ", speaker: "Представители отраслей КИИ", location: "Актовый зал", tag: "Секция" },
      { time: "15:30", title: "Профстандарты и аттестация работников. Позиция Минтруда и СПК-ИТ", speaker: "СПК-ИТ", location: "Зал Б", tag: "Доклад" },
      { time: "16:30", title: "Круглый стол: Состояние и качество ДПО (проблемы, опыт, решения)", location: "Зал А", tag: "Круглый стол" },
      { time: "17:30", title: "Мастер-класс по средствам защиты информации", speaker: "Партнёры конференции", location: "Лаборатория 305", tag: "Мастер-класс" },
    ],
  },
  {
    label: "30 октября",
    events: [
      { time: "10:00", title: "Профориентационный кластер: \u00ABПрофессия \u2014 защитник информации\u00BB", location: "Зал А", tag: "Мастер-класс" },
      { time: "11:00", title: "Научная кооперация кафедр ИБ и производителей средств защиты информации", speaker: "Представители вузов и вендоров", location: "Зал Б", tag: "Секция" },
      { time: "12:00", title: "Выставка продукции производителей средств защиты информации", location: "Фойе, 1 этаж" },
      { time: "13:00", title: "Обеденный перерыв", location: "Столовая, 2 этаж" },
      { time: "14:00", title: "Подведение итогов и резолюция XXX Пленума ФУМО ВО ИБ", speaker: "Председатель ФУМО ВО ИБ", location: "Актовый зал", tag: "Пленарное" },
      { time: "15:30", title: "Закрытие конференции", location: "Актовый зал" },
    ],
  },
];

const ClockIcon = () => (
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg>
);

const PinIcon = () => (
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" /><circle cx="12" cy="10" r="3" /></svg>
);

export function ScheduleSection() {
  const [activeDay, setActiveDay] = useState(0);

  return (
    <section id="schedule" className="section section--alt" aria-labelledby="schedule-heading">
      <div className="container">
        <div className="text-center">
          <p className="section-label">{"Программа"}</p>
          <h2 id="schedule-heading" className="text-balance" style={{ marginTop: "0.75rem" }}>
            {"Программа конференции"}
          </h2>
          <p className="section-desc">
            {"Пленарные заседания, секции, мастер-классы и дискуссии на протяжении трёх дней."}
          </p>
        </div>

        <div className="text-center" style={{ marginTop: "2rem" }}>
          <a href="#" className="btn btn--secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" /><polyline points="7 10 12 15 17 10" /><line x1="12" y1="15" x2="12" y2="3" /></svg>
            {"Скачать программу"}
          </a>
        </div>

        <div className="schedule__tabs" role="tablist">
          {schedule.map((day, i) => (
            <button
              key={day.label}
              type="button"
              role="tab"
              className={`schedule__tab${i === activeDay ? " is-active" : ""}`}
              aria-selected={i === activeDay}
              onClick={() => setActiveDay(i)}
            >
              {day.label}
            </button>
          ))}
        </div>

        {schedule.map((day, i) => (
          <div key={day.label} className={`schedule__panel${i === activeDay ? " is-active" : ""}`} role="tabpanel">
            {day.events.map((event, j) => (
              <div key={j} className="schedule__event">
                <div className="schedule__event-time">{event.time}</div>
                <div className="schedule__event-body">
                  <div className="schedule__event-header">
                    <h3 className="schedule__event-title">{event.title}</h3>
                    {event.tag && (
                      <span className={`tag ${tagClasses[event.tag] || ""}`}>{event.tag}</span>
                    )}
                  </div>
                  {event.speaker && (
                    <p className="schedule__event-speaker">{event.speaker}</p>
                  )}
                  <div className="schedule__event-meta">
                    <span className="flex items-center gap-2"><ClockIcon /> {event.time}</span>
                    <span className="flex items-center gap-2"><PinIcon /> {event.location}</span>
                  </div>
                </div>
              </div>
            ))}
          </div>
        ))}

        <p className="schedule__note">
          {"Организатор имеет право вносить изменения в программу."}
        </p>
      </div>
    </section>
  );
}
