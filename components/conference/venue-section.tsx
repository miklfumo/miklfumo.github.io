import { MapPin, Train, Plane, Hotel } from "lucide-react";

export function VenueSection() {
  return (
    <section
      id="venue"
      className="border-t border-border py-24"
      aria-labelledby="venue-heading"
    >
      <div className="mx-auto max-w-7xl px-6">
        <div className="mx-auto max-w-2xl text-center">
          <p className="text-xs font-semibold tracking-widest text-primary uppercase">
            Место проведения
          </p>
          <h2
            id="venue-heading"
            className="mt-3 text-balance text-3xl font-bold tracking-tight text-foreground sm:text-4xl"
          >
            {"МИРЭА \u2014 Российский технологический университет"}
          </h2>
          <p className="mt-4 text-muted-foreground">
            {"г. Москва, проспект Вернадского, 78, стр. 6"}
          </p>
        </div>

        <div className="mt-12 grid gap-8 lg:grid-cols-2">
          {/* Map embed — MIREA location */}
          <div className="relative aspect-video overflow-hidden rounded-lg border border-border">
            <iframe
              src="https://www.openstreetmap.org/export/embed.html?bbox=37.4800%2C55.6680%2C37.5100%2C55.6800&layer=mapnik&marker=55.6700%2C37.4800"
              className="h-full w-full"
              title="МИРЭА на карте"
              loading="lazy"
              style={{ border: 0 }}
            />
          </div>

          {/* Getting there info */}
          <div className="space-y-6">
            <h3 className="text-lg font-semibold text-foreground">
              Как добраться
            </h3>

            <div className="space-y-5">
              <div className="flex gap-4">
                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-primary/10">
                  <Plane className="h-5 w-5 text-primary" />
                </div>
                <div>
                  <h4 className="font-medium text-foreground">Авиасообщение</h4>
                  <p className="mt-1 text-sm text-muted-foreground leading-relaxed">
                    {"Аэропорт Внуково \u2014 30 минут на аэроэкспрессе до Киевского вокзала, далее метро. Аэропорт Домодедово и Шереметьево \u2014 60\u201390 минут."}
                  </p>
                </div>
              </div>

              <div className="flex gap-4">
                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-primary/10">
                  <Train className="h-5 w-5 text-primary" />
                </div>
                <div>
                  <h4 className="font-medium text-foreground">
                    Общественный транспорт
                  </h4>
                  <p className="mt-1 text-sm text-muted-foreground leading-relaxed">
                    {"Станция метро \u00ABЮго-Западная\u00BB (Сокольническая линия) \u2014 5 минут пешком. Также можно доехать от станции \u00ABТропарёво\u00BB."}
                  </p>
                </div>
              </div>

              <div className="flex gap-4">
                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-primary/10">
                  <Hotel className="h-5 w-5 text-primary" />
                </div>
                <div>
                  <h4 className="font-medium text-foreground">Проживание</h4>
                  <p className="mt-1 text-sm text-muted-foreground leading-relaxed">
                    В пешей доступности расположены гостиницы различных категорий. Рекомендуем бронировать заранее.
                  </p>
                </div>
              </div>

              <div className="flex gap-4">
                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-primary/10">
                  <MapPin className="h-5 w-5 text-primary" />
                </div>
                <div>
                  <h4 className="font-medium text-foreground">Адрес</h4>
                  <p className="mt-1 text-sm text-muted-foreground">
                    {"МИРЭА \u2014 Российский технологический университет"}
                    <br />
                    {"проспект Вернадского, 78, стр. 6"}
                    <br />
                    {"г. Москва, 119454"}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
