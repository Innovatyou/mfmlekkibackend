"use client";

import { useEffect, useRef, useState } from "react";

/**
 * Plays an HLS (.m3u8) stream. Safari/iOS support HLS natively via
 * `<video>`, everywhere else needs hls.js (loaded lazily — most visitors
 * will never hit this source type, so it shouldn't bloat the shared bundle).
 */
export function HlsPlayer({ src, poster }: { src: string; poster?: string }) {
  const videoRef = useRef<HTMLVideoElement>(null);
  const [errored, setErrored] = useState(false);

  useEffect(() => {
    const video = videoRef.current;
    if (!video || !src) return;

    setErrored(false);

    if (video.canPlayType("application/vnd.apple.mpegurl")) {
      video.src = src;
      return;
    }

    let hls: import("hls.js").default | undefined;
    let cancelled = false;

    import("hls.js").then(({ default: Hls }) => {
      if (cancelled) return;
      if (!Hls.isSupported()) {
        setErrored(true);
        return;
      }
      hls = new Hls();
      hls.loadSource(src);
      hls.attachMedia(video);
      hls.on(Hls.Events.ERROR, (_event, data) => {
        if (data.fatal) setErrored(true);
      });
    });

    return () => {
      cancelled = true;
      hls?.destroy();
    };
  }, [src]);

  if (errored) {
    return (
      <div className="flex h-full w-full flex-col items-center justify-center gap-2 bg-black/80 p-6 text-center text-white">
        <p className="text-sm font-medium">This stream couldn&apos;t be loaded.</p>
        <p className="text-xs text-white/60">Your browser may not support this stream format.</p>
      </div>
    );
  }

  return (
    <video
      ref={videoRef}
      poster={poster || undefined}
      controls
      autoPlay
      muted
      playsInline
      className="h-full w-full bg-black"
    />
  );
}
