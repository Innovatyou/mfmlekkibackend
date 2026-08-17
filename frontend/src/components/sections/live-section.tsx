"use client";

import { useState } from "react";
import type { LandingContent, LiveStream } from "@/lib/api";
import { Reveal } from "@/components/ui/reveal";
import { hasText } from "@/lib/utils";
import { HlsPlayer } from "@/components/live/hls-player";

export function LiveSection({
  content,
  live,
}: {
  content: LandingContent;
  live: LiveStream | null;
}) {
  return (
    <section id="live" className="bg-surface-muted py-24">
      <div className="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <Reveal className="mx-auto max-w-2xl text-center">
          <div className="mb-4 flex items-center justify-center gap-2">
            {live && (
              <span className="relative flex h-2.5 w-2.5">
                <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-500 opacity-75" />
                <span className="relative inline-flex h-2.5 w-2.5 rounded-full bg-red-500" />
              </span>
            )}
            <span
              className={
                live
                  ? "text-xs font-bold uppercase tracking-widest text-red-500"
                  : "text-xs font-bold uppercase tracking-widest text-muted-foreground"
              }
            >
              {live ? "Live Now" : "Offline"}
            </span>
          </div>
          <h2 className="font-heading text-3xl font-semibold text-foreground sm:text-4xl">
            {content.live_title}
          </h2>
          {hasText(content.live_subtitle) && (
            <p className="mt-4 text-lg text-muted-foreground">{content.live_subtitle}</p>
          )}
        </Reveal>

        <Reveal delay={0.1} className="mt-12">
          {live ? <LivePlayer live={live} /> : <OfflineState message={content.live_offline_message} />}
        </Reveal>
      </div>
    </section>
  );
}

function LivePlayer({ live }: { live: LiveStream }) {
  return (
    <div className="overflow-hidden rounded-2xl border border-border bg-black shadow-xl">
      <div className="relative aspect-video w-full">
        <StreamEmbed live={live} />
      </div>
      {(hasText(live.title) || hasText(live.description)) && (
        <div className="bg-surface p-6">
          {hasText(live.title) && (
            <h3 className="font-heading text-lg font-semibold text-foreground">{live.title}</h3>
          )}
          {hasText(live.description) && (
            <p className="mt-1.5 text-sm text-muted-foreground">{live.description}</p>
          )}
        </div>
      )}
    </div>
  );
}

function StreamEmbed({ live }: { live: LiveStream }) {
  switch (live.source) {
    case "youtube":
      return (
        <iframe
          src={`https://www.youtube.com/embed/${encodeURIComponent(live.link)}?autoplay=1&mute=1`}
          title={live.title || "Live stream"}
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowFullScreen
          className="h-full w-full"
        />
      );
    case "facebook":
      return (
        <iframe
          src={`https://www.facebook.com/plugins/video.php?href=${encodeURIComponent(
            live.link
          )}&show_text=false&autoplay=true&mute=1`}
          title={live.title || "Live stream"}
          allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
          allowFullScreen
          className="h-full w-full"
        />
      );
    case "m3u8":
      return <HlsPlayer src={live.link} poster={live.cover_photo || undefined} />;
    case "rtmp":
      return <UnsupportedFormat />;
    default:
      return <UnsupportedFormat />;
  }
}

function UnsupportedFormat() {
  const [dismissed, setDismissed] = useState(false);
  if (dismissed) return null;
  return (
    <div className="flex h-full w-full flex-col items-center justify-center gap-3 bg-black/90 p-6 text-center text-white">
      <p className="text-sm font-medium">This stream isn&apos;t viewable in a web browser yet.</p>
      <p className="max-w-sm text-xs text-white/60">
        Watch it live from our mobile app, or check back here once it&apos;s over for the replay.
      </p>
      <button
        type="button"
        onClick={() => setDismissed(true)}
        className="mt-1 rounded-full border border-white/30 px-4 py-1.5 text-xs font-semibold text-white/90 transition-colors hover:bg-white/10"
      >
        Dismiss
      </button>
    </div>
  );
}

function OfflineState({ message }: { message: string }) {
  return (
    <div className="flex flex-col items-center gap-6 rounded-2xl border border-dashed border-border bg-surface px-8 py-16 text-center">
      <span
        className="flex h-16 w-16 items-center justify-center rounded-full"
        style={{
          background: "color-mix(in srgb, var(--primary) 12%, transparent)",
          color: "var(--primary)",
        }}
      >
        <CameraOffIcon className="h-7 w-7" />
      </span>
      <p className="max-w-md text-muted-foreground">{message}</p>
      <a
        href="#service-times"
        className="rounded-full bg-[var(--primary)] px-6 py-2.5 text-sm font-semibold text-[var(--primary-foreground)] shadow-sm transition-transform hover:scale-105"
      >
        View Service Times
      </a>
    </div>
  );
}

function CameraOffIcon({ className }: { className?: string }) {
  return (
    <svg viewBox="0 0 24 24" fill="none" className={className} aria-hidden="true">
      <path
        d="M3 3l18 18M9 7h5l1.5 2H19a2 2 0 012 2v6M15.5 15.5A4 4 0 019 13V9M6 9v6a2 2 0 002 2h5.5"
        stroke="currentColor"
        strokeWidth="1.7"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
}
