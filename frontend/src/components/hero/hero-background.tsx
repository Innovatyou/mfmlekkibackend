"use client";

import { Component, type ReactNode } from "react";
import dynamic from "next/dynamic";
import { useClientValue } from "@/lib/use-client-value";

const HeroCanvas = dynamic(() => import("@/components/hero/hero-canvas"), {
  ssr: false,
});

function supportsWebGL(): boolean {
  try {
    const canvas = document.createElement("canvas");
    return !!(
      window.WebGLRenderingContext &&
      (canvas.getContext("webgl") || canvas.getContext("experimental-webgl"))
    );
  } catch {
    return false;
  }
}

class SceneErrorBoundary extends Component<
  { children: ReactNode; fallback: ReactNode },
  { hasError: boolean }
> {
  state = { hasError: false };

  static getDerivedStateFromError() {
    return { hasError: true };
  }

  componentDidCatch(error: unknown) {
    console.warn("3D hero scene failed to render, using fallback background.", error);
  }

  render() {
    return this.state.hasError ? this.props.fallback : this.props.children;
  }
}

function GradientFallback() {
  return (
    <div
      className="absolute inset-0 bg-background"
      style={{
        backgroundImage:
          "radial-gradient(circle at 25% 20%, color-mix(in srgb, var(--primary) 32%, transparent), transparent 60%), radial-gradient(circle at 80% 75%, color-mix(in srgb, var(--primary) 18%, transparent), transparent 55%)",
      }}
    />
  );
}

/**
 * Immersive 3D hero background. Feature-detects WebGL and wraps the R3F
 * tree in an error boundary so a driver/browser failure never breaks the
 * page — it just quietly falls back to a themed gradient.
 */
export function HeroBackground({
  primaryColor,
  imageUrl,
}: {
  primaryColor: string;
  imageUrl?: string;
}) {
  const webglOk = useClientValue(supportsWebGL, false);

  if (imageUrl?.trim()) {
    return (
      <div className="absolute inset-0 bg-background">
        <div
          className="absolute inset-0 bg-cover bg-center bg-no-repeat"
          style={{ backgroundImage: `url(${JSON.stringify(imageUrl)})` }}
        />
        <div className="absolute inset-0 bg-background/35" />
      </div>
    );
  }

  if (!webglOk) {
    return <GradientFallback />;
  }

  return (
    <SceneErrorBoundary fallback={<GradientFallback />}>
      <HeroCanvas primaryColor={primaryColor} />
    </SceneErrorBoundary>
  );
}
