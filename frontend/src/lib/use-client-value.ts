import { useSyncExternalStore } from "react";

const noopSubscribe = () => () => {};

/**
 * Reads a client-only value (something that can't be computed identically
 * during SSR, like a browser feature check or a "has this mounted on the
 * client yet" flag) in a hydration-safe way.
 *
 * This is the React-blessed alternative to the classic
 * `useState + useEffect(() => setX(...), [])` "mounted" pattern: it avoids
 * calling a state setter synchronously inside an effect (flagged by the
 * `react-hooks/set-state-in-effect` rule) while still rendering
 * `serverValue` during SSR/hydration and swapping to the real client value
 * right after.
 */
export function useClientValue<T>(getClientValue: () => T, serverValue: T): T {
  return useSyncExternalStore(noopSubscribe, getClientValue, () => serverValue);
}
