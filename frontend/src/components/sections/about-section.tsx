"use client";

import type { LandingContent } from "@/lib/api";
import { Reveal } from "@/components/ui/reveal";
import { MediaImage } from "@/components/ui/media-image";

export function AboutSection({ content }: { content: LandingContent }) {
  const paragraphs = content.about_content.split(/\n+/).filter((p) => p.trim().length > 0);

  return (
    <section id="about" className="bg-background py-24">
      <div className="mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
        <Reveal>
          <span className="text-xs font-semibold uppercase tracking-[0.2em] text-[var(--primary)]">
            Our Story
          </span>
          <h2 className="mt-3 font-heading text-3xl font-semibold text-foreground sm:text-4xl">
            {content.about_title}
          </h2>
          <div className="mt-6 space-y-4 text-base leading-relaxed text-muted-foreground sm:text-lg">
            {paragraphs.map((paragraph, i) => (
              <p key={i}>{paragraph}</p>
            ))}
          </div>
        </Reveal>

        <Reveal delay={0.1}>
          <div className="relative aspect-[4/3] w-full overflow-hidden rounded-3xl shadow-xl">
            <MediaImage
              src={content.about_image}
              alt={content.about_title}
              className="h-full w-full object-cover"
              fallback={
                <div
                  className="relative flex h-full w-full items-center justify-center"
                  style={{
                    backgroundImage:
                      "radial-gradient(circle at 30% 25%, color-mix(in srgb, var(--primary) 55%, transparent), transparent 60%), linear-gradient(150deg, color-mix(in srgb, var(--primary) 85%, #1c1917), color-mix(in srgb, var(--primary) 35%, #1c1917))",
                  }}
                >
                  <div
                    className="absolute inset-0 opacity-20"
                    style={{
                      backgroundImage:
                        "radial-gradient(circle, rgba(255,255,255,0.7) 1px, transparent 1px)",
                      backgroundSize: "22px 22px",
                    }}
                  />
                  <AboutMark className="relative h-16 w-16 text-[var(--primary-foreground)] opacity-90" />
                </div>
              }
            />
          </div>
        </Reveal>
      </div>
    </section>
  );
}

function AboutMark({ className }: { className?: string }) {
  return (
    <svg viewBox="0 0 24 24" fill="none" className={className} aria-hidden="true">
      <path
        d="M5 21V11.5C5 7.36 8.13 4 12 4s7 3.36 7 7.5V21"
        stroke="currentColor"
        strokeWidth="1.4"
        strokeLinecap="round"
      />
      <path d="M9 21v-6.5a3 3 0 0 1 6 0V21" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" />
      <path d="M3 21h18" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" />
    </svg>
  );
}
