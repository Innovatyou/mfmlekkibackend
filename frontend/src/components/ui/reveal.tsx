"use client";

import { motion, type Variants } from "framer-motion";
import type { ReactNode } from "react";

const variants: Variants = {
  hidden: { opacity: 0, y: 28 },
  visible: { opacity: 1, y: 0 },
};

interface RevealProps {
  children: ReactNode;
  className?: string;
  delay?: number;
  as?: "div" | "section";
}

/**
 * Shared scroll-reveal wrapper used across all homepage sections.
 *
 * `amount: 0` (any pixel visible, rather than framer-motion's default
 * 20%+) matters here: sections wrap lazy-loading images (see MediaImage)
 * whose containers can measure near-zero height until the image finishes
 * loading. With a higher threshold, the IntersectionObserver's initial
 * check can miss the "20% visible" bar entirely and never fire again once
 * the image loads and the layout shifts - the section then sits at
 * opacity 0 forever, i.e. content that looks like it "disappeared after
 * the page finished loading" even though it was never actually shown.
 */
export function Reveal({ children, className, delay = 0, as = "div" }: RevealProps) {
  const MotionTag = as === "section" ? motion.section : motion.div;
  return (
    <MotionTag
      className={className}
      initial="hidden"
      whileInView="visible"
      viewport={{ once: true, amount: 0 }}
      variants={variants}
      transition={{ duration: 0.6, delay, ease: [0.22, 1, 0.36, 1] }}
    >
      {children}
    </MotionTag>
  );
}
