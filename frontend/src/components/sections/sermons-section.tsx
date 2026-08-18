"use client";

import { useState } from "react";
import type { LandingContent, Sermon } from "@/lib/api";
import { HlsPlayer } from "@/components/live/hls-player";
import { Reveal } from "@/components/ui/reveal";
import { TiltCard } from "@/components/ui/tilt-card";
import { MediaImage } from "@/components/ui/media-image";
import { hasText } from "@/lib/utils";

export function SermonsSection({ content, sermons }: { content: LandingContent; sermons: Sermon[] }) {
  return (
    <section id="sermons" className="bg-surface-muted py-24">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <Reveal className="mx-auto max-w-2xl text-center">
          <h2 className="font-heading text-3xl font-semibold text-foreground sm:text-4xl">
            {content.sermons_title}
          </h2>
          {hasText(content.sermons_subtitle) && (
            <p className="mt-4 text-lg text-muted-foreground">{content.sermons_subtitle}</p>
          )}
        </Reveal>

        {sermons.length === 0 ? (
          <p className="mt-12 text-center text-muted-foreground">
            No sermons published yet — check back soon.
          </p>
        ) : (
          <div
            className="mt-14 grid justify-center gap-6"
            style={{ gridTemplateColumns: "repeat(auto-fit, minmax(280px, 360px))" }}
          >
            {sermons.map((sermon, i) => (
              <Reveal key={`${sermon.id}-${i}`} delay={Math.min(i * 0.06, 0.3)}>
                <SermonCard sermon={sermon} />
              </Reveal>
            ))}
          </div>
        )}
      </div>
    </section>
  );
}

function SermonCard({ sermon }: { sermon: Sermon }) {
  const [playing, setPlaying] = useState(false);
  return (
    <TiltCard className="h-full overflow-hidden rounded-2xl border border-border bg-surface">
      <div className="relative aspect-video w-full overflow-hidden bg-black">
        {playing ? (
          <SermonPlayer sermon={sermon} />
        ) : (
          <button
            type="button"
            onClick={() => sermon.source && setPlaying(true)}
            className="group relative block h-full w-full"
            aria-label={`Play ${sermon.title}`}
          >
            <MediaImage
              src={sermon.cover_photo}
              alt={sermon.title}
              className="h-full w-full object-cover"
              fallback={
                <div
                  className="h-full w-full"
                  style={{
                    backgroundImage:
                      "linear-gradient(135deg, color-mix(in srgb, var(--primary) 50%, transparent), color-mix(in srgb, var(--primary) 12%, transparent))",
                  }}
                />
              }
            />
            <div className="absolute inset-0 flex items-center justify-center bg-black/20 transition-colors group-hover:bg-black/30">
              <span className="flex h-12 w-12 items-center justify-center rounded-full bg-white/90 shadow-lg">
                <PlayIcon className="h-5 w-5 translate-x-0.5 text-[var(--primary)]" />
              </span>
            </div>
            <span className="absolute right-3 top-3 rounded-full bg-black/60 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-white">
              {sermon.type}
            </span>
          </button>
        )}
      </div>
      <div className="p-6">
        {hasText(sermon.category) && (
          <p className="text-xs font-semibold uppercase tracking-wide text-[var(--primary)]">
            {sermon.category}
          </p>
        )}
        <h3 className="mt-2 font-heading text-lg font-semibold text-foreground">
          {sermon.title}
        </h3>
      </div>
    </TiltCard>
  );
}

function SermonPlayer({ sermon }: { sermon: Sermon }) {
  if (sermon.stream_source === "youtube") {
    return <iframe src={`https://www.youtube.com/embed/${youtubeId(sermon.source)}?autoplay=1`} title={sermon.title} allow="autoplay; encrypted-media; picture-in-picture" allowFullScreen className="h-full w-full" />;
  }
  if (sermon.stream_source === "facebook") {
    return <iframe src={`https://www.facebook.com/plugins/video.php?href=${encodeURIComponent(sermon.source)}&show_text=false&autoplay=true`} title={sermon.title} allow="autoplay; encrypted-media; picture-in-picture" allowFullScreen className="h-full w-full" />;
  }
  if (sermon.stream_source === "m3u8") return <HlsPlayer src={sermon.source} poster={sermon.cover_photo} />;
  if (sermon.stream_source === "rtmp") return <p className="flex h-full items-center justify-center p-6 text-center text-sm text-white">RTMP streams cannot play directly in a browser.</p>;
  if (sermon.type === "audio") return <audio src={sermon.source} controls autoPlay className="h-full w-full" />;
  return <video src={sermon.source} poster={sermon.cover_photo || undefined} controls autoPlay playsInline className="h-full w-full" />;
}

function youtubeId(source: string) {
  const match = source.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|live\/))([^?&/]+)/);
  return encodeURIComponent(match?.[1] || source);
}

function PlayIcon({ className }: { className?: string }) {
  return (
    <svg viewBox="0 0 24 24" fill="currentColor" className={className} aria-hidden="true">
      <path d="M8 5.5v13l11-6.5-11-6.5Z" />
    </svg>
  );
}
