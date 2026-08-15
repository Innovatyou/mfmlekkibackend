"use client";

import { useRef, useState, type CSSProperties, type PointerEvent, type ReactNode } from "react";
import clsx from "clsx";

interface TiltCardProps {
  children: ReactNode;
  className?: string;
  maxTilt?: number;
}

const RESTING_STYLE: CSSProperties = {
  transform: "perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)",
  boxShadow: "0 12px 24px -14px rgba(0,0,0,0.25)",
};

/**
 * Lightweight, dependency-free 3D tilt effect driven by pointer position.
 * Pure CSS transforms — no react-three-fiber needed for this. Disabled for
 * touch input so it never feels janky on mobile.
 */
export function TiltCard({ children, className, maxTilt = 10 }: TiltCardProps) {
  const ref = useRef<HTMLDivElement>(null);
  const [style, setStyle] = useState<CSSProperties>(RESTING_STYLE);

  function handleMove(e: PointerEvent<HTMLDivElement>) {
    if (e.pointerType === "touch") return;
    const el = ref.current;
    if (!el) return;
    const rect = el.getBoundingClientRect();
    const px = (e.clientX - rect.left) / rect.width;
    const py = (e.clientY - rect.top) / rect.height;
    const rotateY = (px - 0.5) * maxTilt * 2;
    const rotateX = (0.5 - py) * maxTilt * 2;

    setStyle({
      transform: `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`,
      boxShadow: `${(-rotateY * 1.4).toFixed(2)}px ${(rotateX * 1.4 + 16).toFixed(2)}px 34px -14px rgba(0,0,0,0.4)`,
    });
  }

  function handleLeave() {
    setStyle(RESTING_STYLE);
  }

  return (
    <div
      ref={ref}
      onPointerMove={handleMove}
      onPointerLeave={handleLeave}
      onPointerCancel={handleLeave}
      style={style}
      className={clsx("transition-transform duration-200 ease-out will-change-transform", className)}
    >
      {children}
    </div>
  );
}
