import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  // Live SSR via a Node.js process (cPanel's Node.js Selector / Passenger),
  // not a static export — so landing-page content saved in the admin
  // dashboard shows up on next page load with no manual rebuild/redeploy.
  // Mounted at a sub-path of the existing PHP-backed domain, so cPanel's
  // Passenger proxy forwards requests with the /site prefix intact —
  // Next.js needs basePath set to match, or routing/assets break.
  basePath: "/site",
  // The build server is a memory-capped shared-hosting account (LVE) that
  // reports its full host CPU count regardless of that cap — Next.js's
  // default worker-per-CPU page-generation pool (8 workers here) gets
  // OOM-killed. Force a single worker; slower build, but it completes.
  experimental: {
    cpus: 1,
  },
};

export default nextConfig;
