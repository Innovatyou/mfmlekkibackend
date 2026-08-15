"use client";

import { useRef, useState } from "react";
import { useFrame } from "@react-three/fiber";
import { Points, PointMaterial } from "@react-three/drei";
import * as THREE from "three";

interface SceneProps {
  primaryColor: string;
  isDark: boolean;
  parallaxEnabled: boolean;
}

function ParticleField({ color, count = 260 }: { color: string; count?: number }) {
  // Random particle placement only needs to happen once per mount, not on
  // every render — a useState lazy initializer (guaranteed by React to run
  // exactly once) is the correct tool here, rather than useMemo, whose
  // recomputation guarantees are weaker and which isn't meant to wrap
  // impure calls like Math.random().
  const [positions] = useState(() => {
    const arr = new Float32Array(count * 3);
    for (let i = 0; i < count; i++) {
      const radius = 2.6 + Math.random() * 5.4;
      const theta = Math.random() * Math.PI * 2;
      const phi = Math.acos(Math.random() * 2 - 1);
      arr[i * 3] = radius * Math.sin(phi) * Math.cos(theta);
      arr[i * 3 + 1] = radius * Math.sin(phi) * Math.sin(theta) * 0.6;
      arr[i * 3 + 2] = radius * Math.cos(phi);
    }
    return arr;
  });

  const ref = useRef<THREE.Points>(null);

  useFrame((_, delta) => {
    if (!ref.current) return;
    ref.current.rotation.y += delta * 0.018;
  });

  return (
    <Points ref={ref} positions={positions} stride={3} frustumCulled>
      <PointMaterial
        transparent
        color={color}
        size={0.05}
        sizeAttenuation
        depthWrite={false}
        opacity={0.5}
      />
    </Points>
  );
}

/** Nested translucent shells around the core, additively blended, giving a
 * soft glow falloff instead of a hard silhouette edge. */
function GlowShell({ color, scale, opacity }: { color: string; scale: number; opacity: number }) {
  return (
    <mesh scale={scale}>
      <sphereGeometry args={[1, 32, 32]} />
      <meshBasicMaterial
        color={color}
        transparent
        opacity={opacity}
        side={THREE.BackSide}
        blending={THREE.AdditiveBlending}
        depthWrite={false}
        toneMapped={false}
      />
    </mesh>
  );
}

/** A slowly rotating wireframe shell — a "sacred geometry" accent that reads
 * as crisp and intentional at any angle, unlike faceted lit surfaces which
 * need very particular lighting to avoid looking flat or muddy. */
function WireframeShell({ color, radius, detail, speed }: { color: string; radius: number; detail: number; speed: number }) {
  const ref = useRef<THREE.Mesh>(null);
  useFrame((_, delta) => {
    if (!ref.current) return;
    ref.current.rotation.y += delta * speed;
    ref.current.rotation.x += delta * speed * 0.4;
  });
  return (
    <mesh ref={ref}>
      <icosahedronGeometry args={[radius, detail]} />
      <meshBasicMaterial color={color} wireframe transparent opacity={0.5} toneMapped={false} />
    </mesh>
  );
}

function Centerpiece({ primaryColor, isDark }: { primaryColor: string; isDark: boolean }) {
  const coreRef = useRef<THREE.Mesh>(null);

  useFrame(({ clock }) => {
    const t = clock.getElapsedTime();
    if (coreRef.current) {
      const pulse = 1 + Math.sin(t * 0.6) * 0.05;
      coreRef.current.scale.setScalar(pulse);
    }
  });

  const glowColor = isDark ? "#fde68a" : primaryColor;
  // The core reads as a bright "light" against a light background, but the
  // same brightness washes out text legibility against a dark background —
  // dial it back and lean on the primary color glow instead in dark mode.
  const coreScale = isDark ? 0.5 : 0.85;
  const coreColor = isDark ? primaryColor : "#fff8ee";

  return (
    <group>
      {/* Bright core — the "light" at the centre of the piece */}
      <mesh ref={coreRef} scale={coreScale}>
        <sphereGeometry args={[1, 32, 32]} />
        <meshBasicMaterial color={coreColor} toneMapped={false} />
      </mesh>

      <GlowShell color={primaryColor} scale={1.05} opacity={isDark ? 0.32 : 0.55} />
      <GlowShell color={glowColor} scale={1.3} opacity={isDark ? 0.16 : 0.3} />
      <GlowShell color={glowColor} scale={1.7} opacity={isDark ? 0.07 : 0.14} />

      <WireframeShell color={primaryColor} radius={1.85} detail={1} speed={0.09} />
    </group>
  );
}

function DriftingLights({ primaryColor, isDark }: { primaryColor: string; isDark: boolean }) {
  const lightA = useRef<THREE.PointLight>(null);
  const lightB = useRef<THREE.PointLight>(null);

  useFrame(({ clock }) => {
    const t = clock.getElapsedTime();
    if (lightA.current) {
      lightA.current.intensity = (isDark ? 2.4 : 1.5) + Math.sin(t * 0.5) * 0.4;
    }
    if (lightB.current) {
      lightB.current.intensity = (isDark ? 1.6 : 1) + Math.cos(t * 0.35) * 0.3;
    }
  });

  return (
    <>
      <ambientLight intensity={isDark ? 0.3 : 0.7} />
      <pointLight ref={lightA} position={[4, 3, 4]} color={primaryColor} intensity={1.5} />
      <pointLight
        ref={lightB}
        position={[-4.5, -1.5, -2.5]}
        color={isDark ? "#fde68a" : "#fff7ed"}
        intensity={1}
      />
    </>
  );
}

function ParallaxGroup({
  enabled,
  children,
}: {
  enabled: boolean;
  children: React.ReactNode;
}) {
  const group = useRef<THREE.Group>(null);

  useFrame((state) => {
    if (!group.current) return;
    const targetX = enabled ? state.pointer.y * 0.12 : 0;
    const targetY = enabled ? state.pointer.x * 0.2 : 0;
    group.current.rotation.x += (targetX - group.current.rotation.x) * 0.05;
    group.current.rotation.y += (targetY - group.current.rotation.y) * 0.05;
  });

  return <group ref={group}>{children}</group>;
}

export function HeroScene({ primaryColor, isDark, parallaxEnabled }: SceneProps) {
  const bg = isDark ? "#0a0808" : "#fdf8f1";

  return (
    <>
      <color attach="background" args={[bg]} />
      <fog attach="fog" args={[bg, 7, 15]} />
      <DriftingLights primaryColor={primaryColor} isDark={isDark} />
      <ParallaxGroup enabled={parallaxEnabled}>
        <Centerpiece primaryColor={primaryColor} isDark={isDark} />
        <ParticleField color={isDark ? "#fde68a" : primaryColor} />
      </ParallaxGroup>
    </>
  );
}
