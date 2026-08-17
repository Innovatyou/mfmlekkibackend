"use client";

import { useEffect, useRef, useState, type ReactNode } from "react";
import { hasText } from "@/lib/utils";

interface MediaImageProps {
  src: string | null | undefined;
  alt: string;
  className?: string;
  fallback: ReactNode;
  loading?: "lazy" | "eager";
}

/**
 * A plain <img> (not next/image — the church's backend host is only known
 * at runtime via NEXT_PUBLIC_API_URL, so a static remotePatterns allowlist
 * isn't workable) that renders `fallback` whenever the src is empty/missing
 * or fails to load at runtime, so the UI never shows a broken image icon.
 *
 * Two failure modes are handled:
 *  - A normal load/error event (404, CORS refusal, etc).
 *  - A "silent" failure some browsers' network-security blocks produce
 *    (observed with Chrome's Opaque Response Blocking when a backend URL
 *    bug serves an HTML error page in place of image bytes): neither
 *    `load` nor `error` ever fires on the element. A short watchdog
 *    timeout catches that case so the UI never gets stuck showing a
 *    permanently broken image icon.
 */
export function MediaImage({ src, alt, className, fallback, loading = "lazy" }: MediaImageProps) {
  const [failed, setFailed] = useState(false);
  const timerRef = useRef<number | null>(null);

  useEffect(() => {
    // Resets watchdog state whenever `src` changes, synchronizing with the
    // external timer system below — the documented, sanctioned use of
    // setState-in-effect (this is not derivable from render: it must reset
    // exactly when a *new* image starts loading).
    // eslint-disable-next-line react-hooks/set-state-in-effect
    setFailed(false);
    if (timerRef.current) window.clearTimeout(timerRef.current);
    if (!hasText(src)) return;

    timerRef.current = window.setTimeout(() => setFailed(true), 5000);
    return () => {
      if (timerRef.current) window.clearTimeout(timerRef.current);
    };
  }, [src]);

  function settle(isFailed: boolean) {
    if (timerRef.current) {
      window.clearTimeout(timerRef.current);
      timerRef.current = null;
    }
    setFailed(isFailed);
  }

  if (!hasText(src) || failed) {
    return <>{fallback}</>;
  }

  return (
    // eslint-disable-next-line @next/next/no-img-element
    <img
      src={src}
      alt={alt}
      loading={loading}
      className={className}
      onLoad={(e) => settle(e.currentTarget.naturalWidth === 0)}
      onError={() => settle(true)}
    />
  );
}
