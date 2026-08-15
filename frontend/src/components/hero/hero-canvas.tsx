"use client";

import { Suspense } from "react";
import { Canvas } from "@react-three/fiber";
import { useTheme } from "next-themes";
import { HeroScene } from "@/components/hero/hero-scene";
import { useClientValue } from "@/lib/use-client-value";

export default function HeroCanvas({ primaryColor }: { primaryColor: string }) {
  const { resolvedTheme } = useTheme();
  const isTouch = useClientValue(() => window.matchMedia("(pointer: coarse)").matches, false);

  const isDark = resolvedTheme === "dark";

  return (
    <Canvas
      dpr={[1, 1.75]}
      camera={{ position: [0, 0, 6.4], fov: 45 }}
      gl={{ antialias: true, powerPreference: "high-performance" }}
    >
      <Suspense fallback={null}>
        <HeroScene primaryColor={primaryColor} isDark={isDark} parallaxEnabled={!isTouch} />
      </Suspense>
    </Canvas>
  );
}
