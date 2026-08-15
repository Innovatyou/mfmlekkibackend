"use client";

import { useEffect, useState } from "react";
import { AnimatePresence, motion } from "framer-motion";
import type { GalleryImage, LandingContent } from "@/lib/api";
import { Reveal } from "@/components/ui/reveal";
import { MediaImage } from "@/components/ui/media-image";
import { hasText } from "@/lib/utils";

export function GallerySection({
  content,
  gallery,
}: {
  content: LandingContent;
  gallery: GalleryImage[];
}) {
  const [activeIndex, setActiveIndex] = useState<number | null>(null);
  const usable = gallery.filter((g) => hasText(g.image));

  useEffect(() => {
    if (activeIndex === null) return;
    function onKey(e: KeyboardEvent) {
      if (e.key === "Escape") setActiveIndex(null);
      if (e.key === "ArrowRight") setActiveIndex((i) => (i === null ? i : (i + 1) % usable.length));
      if (e.key === "ArrowLeft")
        setActiveIndex((i) => (i === null ? i : (i - 1 + usable.length) % usable.length));
    }
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, [activeIndex, usable.length]);

  return (
    <section id="gallery" className="bg-background py-24">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <Reveal className="mx-auto max-w-2xl text-center">
          <h2 className="font-heading text-3xl font-semibold text-foreground sm:text-4xl">
            {content.gallery_title}
          </h2>
          {hasText(content.gallery_subtitle) && (
            <p className="mt-4 text-lg text-muted-foreground">{content.gallery_subtitle}</p>
          )}
        </Reveal>

        {usable.length === 0 ? (
          <p className="mt-12 text-center text-muted-foreground">
            Photos from our community will appear here soon.
          </p>
        ) : (
          <div className="mt-14 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            {usable.map((item, i) => (
              <Reveal key={`${item.image}-${i}`} delay={Math.min(i * 0.04, 0.24)}>
                <button
                  type="button"
                  onClick={() => setActiveIndex(i)}
                  className="group relative block aspect-square w-full overflow-hidden rounded-2xl"
                >
                  <MediaImage
                    src={item.image}
                    alt={item.title || "Gallery photo"}
                    className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                    fallback={
                      <div
                        className="h-full w-full"
                        style={{
                          backgroundImage:
                            "linear-gradient(135deg, color-mix(in srgb, var(--primary) 45%, transparent), color-mix(in srgb, var(--primary) 12%, transparent))",
                        }}
                      />
                    }
                  />
                  {hasText(item.title) && (
                    <span className="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent px-3 py-2 text-left text-xs font-medium text-white opacity-0 transition-opacity group-hover:opacity-100">
                      {item.title}
                    </span>
                  )}
                </button>
              </Reveal>
            ))}
          </div>
        )}
      </div>

      <AnimatePresence>
        {activeIndex !== null && usable[activeIndex] && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 z-[100] flex items-center justify-center bg-black/85 p-4"
            role="dialog"
            aria-modal="true"
            onClick={() => setActiveIndex(null)}
          >
            <button
              type="button"
              aria-label="Close"
              onClick={() => setActiveIndex(null)}
              className="absolute right-5 top-5 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20"
            >
              <svg viewBox="0 0 24 24" fill="none" className="h-5 w-5" aria-hidden="true">
                <path
                  d="M6 6l12 12M18 6L6 18"
                  stroke="currentColor"
                  strokeWidth="1.8"
                  strokeLinecap="round"
                />
              </svg>
            </button>
            <motion.div
              initial={{ scale: 0.94, opacity: 0 }}
              animate={{ scale: 1, opacity: 1 }}
              exit={{ scale: 0.96, opacity: 0 }}
              transition={{ duration: 0.25 }}
              className="relative max-h-[85vh] max-w-4xl"
              onClick={(e) => e.stopPropagation()}
            >
              <MediaImage
                src={usable[activeIndex].image}
                alt={usable[activeIndex].title || "Gallery photo"}
                className="max-h-[85vh] w-auto rounded-xl object-contain"
                fallback={
                  <div className="flex h-64 w-80 max-w-[80vw] items-center justify-center rounded-xl bg-white/10 text-sm text-white/70">
                    Image unavailable
                  </div>
                }
              />
              {hasText(usable[activeIndex].title) && (
                <p className="mt-3 text-center text-sm text-white/80">{usable[activeIndex].title}</p>
              )}
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </section>
  );
}
