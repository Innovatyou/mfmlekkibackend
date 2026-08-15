"use client";

import { useRef, useState } from "react";
import { useFrame } from "@react-three/fiber";
import { Icosahedron, MeshDistortMaterial, Points, PointMaterial } from "@react-three/drei";
import * as THREE from "three";

interface SceneProps {
  primaryColor: string;
  isDark: boolean;
  parallaxEnabled: boolean;
}

function ParticleField({ color, count = 380 }: { color: string; count?: number }) {
  // Random particle placement only needs to happen once per mount, not on
  // every render — a useState lazy initializer (guaranteed by React to run
  // exactly once) is the correct tool here, rather than useMemo, whose
  // recomputation guarantees are weaker and which isn't meant to wrap
  // impure calls like Math.random().
  const [positions] = useState(() => {
    const arr = new Float32Array(count * 3);
    for (let i = 0; i < count; i++) {
      const radius = 3.4 + Math.random() * 6.2;
      const theta = Math.random() * Math.PI * 2;
      const phi = Math.acos(Math.random() * 2 - 1);
      arr[i * 3] = radius * Math.sin(phi) * Math.cos(theta);
      arr[i * 3 + 1] = radius * Math.sin(phi) * Math.sin(theta);
      arr[i * 3 + 2] = radius * Math.cos(phi);
    }
    return arr;
  });

  const ref = useRef<THREE.Points>(null);

  useFrame((_, delta) => {
    if (!ref.current) return;
    ref.current.rotation.y += delta * 0.025;
    ref.current.rotation.x += delta * 0.006;
  });

  return (
    <Points ref={ref} positions={positions} stride={3} frustumCulled>
      <PointMaterial
        transparent
        color={color}
        size={0.045}
        sizeAttenuation
        depthWrite={false}
        opacity={0.55}
      />
    </Points>
  );
}

function Centerpiece({ primaryColor, isDark }: { primaryColor: string; isDark: boolean }) {
  const meshRef = useRef<THREE.Mesh>(null);

  useFrame((_, delta) => {
    if (!meshRef.current) return;
    meshRef.current.rotation.x += delta * 0.09;
    meshRef.current.rotation.y += delta * 0.13;
  });

  return (
    <Icosahedron ref={meshRef} args={[1.55, 3]}>
      <MeshDistortMaterial
        color={primaryColor}
        emissive={primaryColor}
        emissiveIntensity={isDark ? 0.55 : 0.18}
        distort={0.4}
        speed={1.4}
        roughness={0.15}
        metalness={0.15}
        transparent
        opacity={isDark ? 0.88 : 0.92}
      />
    </Icosahedron>
  );
}

function DriftingLights({ primaryColor, isDark }: { primaryColor: string; isDark: boolean }) {
  const lightA = useRef<THREE.PointLight>(null);
  const lightB = useRef<THREE.PointLight>(null);

  useFrame(({ clock }) => {
    const t = clock.getElapsedTime();
    if (lightA.current) {
      lightA.current.intensity = (isDark ? 2.2 : 1.3) + Math.sin(t * 0.5) * 0.45;
    }
    if (lightB.current) {
      lightB.current.intensity = (isDark ? 1.6 : 1) + Math.cos(t * 0.35) * 0.35;
    }
  });

  return (
    <>
      <ambientLight intensity={isDark ? 0.22 : 0.6} />
      <pointLight ref={lightA} position={[4, 3, 4]} color={primaryColor} intensity={1.3} />
      <pointLight
        ref={lightB}
        position={[-4.5, -2, -2.5]}
        color={isDark ? "#7dd3fc" : "#fbcfe8"}
        intensity={1}
      />
      <directionalLight position={[0, 6, 4]} intensity={isDark ? 0.25 : 0.5} color="#ffffff" />
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
    const targetX = enabled ? state.pointer.y * 0.14 : 0;
    const targetY = enabled ? state.pointer.x * 0.22 : 0;
    group.current.rotation.x += (targetX - group.current.rotation.x) * 0.05;
    group.current.rotation.y += (targetY - group.current.rotation.y) * 0.05;
  });

  return <group ref={group}>{children}</group>;
}

export function HeroScene({ primaryColor, isDark, parallaxEnabled }: SceneProps) {
  const bg = isDark ? "#07070b" : "#fdf8f1";

  return (
    <>
      <color attach="background" args={[bg]} />
      <fog attach="fog" args={[bg, 6.5, 15]} />
      <DriftingLights primaryColor={primaryColor} isDark={isDark} />
      <ParallaxGroup enabled={parallaxEnabled}>
        <Centerpiece primaryColor={primaryColor} isDark={isDark} />
        <ParticleField color={primaryColor} />
      </ParallaxGroup>
    </>
  );
}
