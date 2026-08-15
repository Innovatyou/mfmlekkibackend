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
          <h2 className="font-heading text-3xl font-semibold text-foreground sm:text-4xl">
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
                  className="h-full w-full"
                  style={{
                    backgroundImage:
                      "linear-gradient(135deg, color-mix(in srgb, var(--primary) 55%, transparent), color-mix(in srgb, var(--primary) 15%, transparent))",
                  }}
                />
              }
            />
          </div>
        </Reveal>
      </div>
    </section>
  );
}
