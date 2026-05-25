"use client";

import Image from "next/image";
import { useState } from "react";
import { X, ChevronLeft, ChevronRight, ChevronDown } from "lucide-react";

type GalleryYear = {
  year: string;
  images: { src: string; alt: string }[];
};

const galleryData: GalleryYear[] = [
  {
    year: "2024",
    images: [
      { src: "/images/gallery-1.jpg", alt: "Конференционный зал, 2024" },
      { src: "/images/gallery-2.jpg", alt: "Выступление докладчика, 2024" },
      { src: "/images/gallery-3.jpg", alt: "Нетворкинг участников, 2024" },
    ],
  },
  {
    year: "2025",
    images: [
      { src: "/images/gallery-4.jpg", alt: "Панельная дискуссия, 2025" },
      { src: "/images/gallery-5.jpg", alt: "Выставочная зона, 2025" },
      { src: "/images/gallery-6.jpg", alt: "Закрытие конференции, 2025" },
    ],
  },
];

export function GallerySection() {
  const [openYear, setOpenYear] = useState<string | null>(null);
  const [lightbox, setLightbox] = useState<{ yearIdx: number; imgIdx: number } | null>(null);

  const toggleYear = (year: string) => {
    setOpenYear((prev) => (prev === year ? null : year));
  };

  const allImagesFlat = galleryData.flatMap((y) => y.images);
  const lightboxIndex =
    lightbox !== null
      ? galleryData
          .slice(0, lightbox.yearIdx)
          .reduce((sum, y) => sum + y.images.length, 0) + lightbox.imgIdx
      : null;

  const openLightbox = (yearIdx: number, imgIdx: number) =>
    setLightbox({ yearIdx, imgIdx });

  const closeLightbox = () => setLightbox(null);

  const navigateLightbox = (direction: number) => {
    if (lightboxIndex === null) return;
    const newIndex =
      (lightboxIndex + direction + allImagesFlat.length) % allImagesFlat.length;
    let count = 0;
    for (let yi = 0; yi < galleryData.length; yi++) {
      for (let ii = 0; ii < galleryData[yi].images.length; ii++) {
        if (count === newIndex) {
          setLightbox({ yearIdx: yi, imgIdx: ii });
          return;
        }
        count++;
      }
    }
  };

  return (
    <section
      id="gallery"
      className="border-t border-border bg-card py-24"
      aria-labelledby="gallery-heading"
    >
      <div className="mx-auto max-w-7xl px-6">
        <div className="mx-auto max-w-2xl text-center">
          <p className="text-xs font-semibold tracking-widest text-primary uppercase">
            Галерея
          </p>
          <h2
            id="gallery-heading"
            className="mt-3 text-balance text-3xl font-bold tracking-tight text-foreground sm:text-4xl"
          >
            Фотографии с мероприятий
          </h2>
          <p className="mt-4 text-muted-foreground">
            Моменты с прошедших Пленумов ФУМО ВО ИБ.
          </p>
        </div>

        {/* Accordion by year */}
        <div className="mt-12 space-y-4">
          {galleryData.map((yearData, yearIdx) => (
            <div
              key={yearData.year}
              className="rounded-lg border border-border overflow-hidden"
            >
              <button
                type="button"
                onClick={() => toggleYear(yearData.year)}
                className="flex w-full items-center justify-between bg-background px-6 py-4 text-left transition-colors hover:bg-secondary/50"
                aria-expanded={openYear === yearData.year}
              >
                <span className="text-lg font-semibold text-foreground">
                  {yearData.year}
                </span>
                <ChevronDown
                  className={`h-5 w-5 text-muted-foreground transition-transform duration-300 ${
                    openYear === yearData.year ? "rotate-180" : ""
                  }`}
                />
              </button>

              {openYear === yearData.year && (
                <div className="bg-background/50 p-6 pt-2">
                  <div className="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3">
                    {yearData.images.map((image, imgIdx) => (
                      <button
                        key={image.src}
                        type="button"
                        className="group relative aspect-[4/3] overflow-hidden rounded-lg"
                        onClick={() => openLightbox(yearIdx, imgIdx)}
                        aria-label={`Увеличить: ${image.alt}`}
                      >
                        <Image
                          src={image.src || "/placeholder.svg"}
                          alt={image.alt}
                          fill
                          className="object-cover transition-transform duration-500 group-hover:scale-105"
                        />
                        <div className="absolute inset-0 bg-background/0 transition-colors group-hover:bg-background/20" />
                      </button>
                    ))}
                  </div>
                </div>
              )}
            </div>
          ))}
        </div>
      </div>

      {/* Lightbox */}
      {lightbox !== null && (
        <div
          className="fixed inset-0 z-50 flex items-center justify-center bg-background/95 backdrop-blur-sm"
          role="dialog"
          aria-modal="true"
          aria-label="Просмотр фотографии"
        >
          <button
            type="button"
            className="absolute top-6 right-6 text-foreground transition-colors hover:text-primary"
            onClick={closeLightbox}
            aria-label="Закрыть"
          >
            <X className="h-6 w-6" />
          </button>

          <button
            type="button"
            className="absolute left-4 text-foreground transition-colors hover:text-primary md:left-8"
            onClick={() => navigateLightbox(-1)}
            aria-label="Предыдущее фото"
          >
            <ChevronLeft className="h-8 w-8" />
          </button>

          <div className="relative mx-16 aspect-video w-full max-w-4xl">
            <Image
              src={galleryData[lightbox.yearIdx].images[lightbox.imgIdx].src || "/placeholder.svg"}
              alt={galleryData[lightbox.yearIdx].images[lightbox.imgIdx].alt}
              fill
              className="rounded-lg object-cover"
            />
          </div>

          <button
            type="button"
            className="absolute right-4 text-foreground transition-colors hover:text-primary md:right-8"
            onClick={() => navigateLightbox(1)}
            aria-label="Следующее фото"
          >
            <ChevronRight className="h-8 w-8" />
          </button>

          <p className="absolute bottom-8 text-sm text-muted-foreground">
            {lightboxIndex !== null ? lightboxIndex + 1 : 0} /{" "}
            {allImagesFlat.length}
          </p>
        </div>
      )}
    </section>
  );
}
