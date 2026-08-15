import clsx from "clsx";
import { getInitials, hasText } from "@/lib/utils";
import { MediaImage } from "@/components/ui/media-image";

interface AvatarProps {
  src?: string | null;
  name: string;
  className?: string;
}

/** Circular photo with a graceful initials-on-gradient fallback. */
export function Avatar({ src, name, className }: AvatarProps) {
  if (hasText(src ?? undefined)) {
    return (
      <MediaImage
        src={src ?? ""}
        alt={name}
        className={clsx("rounded-full object-cover", className)}
        fallback={<InitialsAvatar name={name} className={className} />}
      />
    );
  }
  return <InitialsAvatar name={name} className={className} />;
}

function InitialsAvatar({ name, className }: { name: string; className?: string }) {
  return (
    <div
      className={clsx(
        "flex items-center justify-center rounded-full bg-gradient-to-br from-[var(--primary)] to-[color-mix(in_srgb,var(--primary)_55%,#7c2d12)] font-heading font-semibold text-[var(--primary-foreground)]",
        className
      )}
      aria-hidden="true"
    >
      {getInitials(name)}
    </div>
  );
}
