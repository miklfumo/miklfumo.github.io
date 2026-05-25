import Image from "next/image";

interface PartnerItem {
  name: string;
  logo: string;
}

interface PartnerGroup {
  title: string;
  items: PartnerItem[];
}

const groups: PartnerGroup[] = [
  {
    title: "Организаторы",
    items: [
      { name: "ФУМО ВО ИБ", logo: "/images/logos/Logo.png" },
      { name: "ФУМО СПО ИБ", logo: "/images/logos/Logo.png" },
      { name: "СПК-ИТ", logo: "/images/logos/spkit.png" },
    ],
  },
  {
    title: "Соорганизаторы",
    items: [
      { name: "ФСТЭК России", logo: "/images/logos/fstek.png" },
      { name: "Минобрнауки России", logo: "/images/logos/minobr.png" },
      { name: "РТУ МИРЭА", logo: "/images/logos/MIREA_Gerb_Colour.png" },
    ],
  },
  {
    title: "Партнёры",
    items: [
      { name: "АНО НТЦ ЦК", logo: "/images/logos/NTCCK.png" },
      { name: "АЗИ", logo: "/images/logos/AZI.png" },
      { name: "\u0413\u041A \u00ABИнфоТеКС\u00BB", logo: "/images/logos/infotecs.png" },
    ],
  },
  {
    title: "При участии",
    items: [
      { name: "ФСБ России", logo: "/images/logos/FSB.png" },
      { name: "Аппарат СБ России", logo: "/images/logos/SBRF.png" },
    ],
  },
  {
    title: "Оператор",
    items: [
      { name: "\u041E\u041E\u041E \u00ABАкадемия \u201CПрофи Скиллс\u201D\u00BB", logo: "/images/logos/Profiskills.png" },
    ],
  },
];

export function PartnersSection() {
  return (
    <section id="partners" className="section" aria-labelledby="partners-heading">
      <div className="container">
        <div className="text-center">
          <p className="section-label">{"Партнёры и организаторы"}</p>
          <h2 id="partners-heading" className="text-balance" style={{ marginTop: "0.75rem" }}>
            {"Организаторы и партнёры"}
          </h2>
        </div>

        {groups.map((group) => (
          <div key={group.title} className="partners__group">
            <h3 className="partners__group-title">{group.title}</h3>
            <div className="partners__grid">
              {group.items.map((item) => (
                <div key={item.name} className="partners__item">
                  <div className="relative h-20 w-20 shrink-0">
                    <Image
                      src={item.logo}
                      alt={item.name}
                      fill
                      className="object-contain"
                    />
                  </div>
                  <span className="partners__item-name">{item.name}</span>
                </div>
              ))}
            </div>
          </div>
        ))}

        <div className="text-center" style={{ marginTop: "3rem" }}>
          <a href="#registration" className="btn btn--primary">{"Стать партнёром"}</a>
        </div>
      </div>
    </section>
  );
}
