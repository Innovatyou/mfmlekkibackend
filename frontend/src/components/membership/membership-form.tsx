"use client";

import { useEffect, useState, type FormEvent } from "react";
import Link from "next/link";
import { motion } from "framer-motion";
import clsx from "clsx";
import { getMembershipForm, submitMembershipForm, type MembershipField } from "@/lib/api";
import { hasText } from "@/lib/utils";

type LoadState = "loading" | "error" | "ready";
type FormValues = Record<string, string | string[]>;

function initialValueFor(field: MembershipField): string | string[] {
  return field.field_type === "checkbox" ? [] : "";
}

export function MembershipForm() {
  const [loadState, setLoadState] = useState<LoadState>("loading");
  const [loadError, setLoadError] = useState<string | null>(null);
  const [fields, setFields] = useState<MembershipField[]>([]);
  const [values, setValues] = useState<FormValues>({});
  const [fieldErrors, setFieldErrors] = useState<Record<string, string>>({});
  const [submitting, setSubmitting] = useState(false);
  const [submitError, setSubmitError] = useState<string | null>(null);
  const [success, setSuccess] = useState(false);
  const [reloadToken, setReloadToken] = useState(0);

  useEffect(() => {
    let cancelled = false;
    // Resetting to "loading" here is what makes the retry button (bumping
    // reloadToken) work — this effect synchronizes component state with an
    // external system (the network request below), which is exactly the
    // documented, sanctioned use of setState-in-effect.
    setLoadState("loading");
    setLoadError(null);

    getMembershipForm()
      .then((res) => {
        if (cancelled) return;
        setFields(res.fields);
        setValues(Object.fromEntries(res.fields.map((f) => [f.field_key, initialValueFor(f)])));
        setLoadState("ready");
      })
      .catch((err) => {
        if (cancelled) return;
        setLoadError(err instanceof Error ? err.message : "Unable to load the membership form.");
        setLoadState("error");
      });

    return () => {
      cancelled = true;
    };
  }, [reloadToken]);

  function updateValue(key: string, value: string | string[]) {
    setValues((v) => ({ ...v, [key]: value }));
    setFieldErrors((e) => {
      if (!e[key]) return e;
      const next = { ...e };
      delete next[key];
      return next;
    });
  }

  function toggleCheckbox(key: string, option: string, checked: boolean) {
    setValues((v) => {
      const current = Array.isArray(v[key]) ? (v[key] as string[]) : [];
      const next = checked ? [...current, option] : current.filter((o) => o !== option);
      return { ...v, [key]: next };
    });
    setFieldErrors((e) => {
      if (!e[key]) return e;
      const next = { ...e };
      delete next[key];
      return next;
    });
  }

  function validate(): boolean {
    const errors: Record<string, string> = {};
    for (const field of fields) {
      if (!field.required) continue;
      const value = values[field.field_key];
      const empty = Array.isArray(value) ? value.length === 0 : !hasText(value);
      if (empty) errors[field.field_key] = `${field.label} is required.`;
    }
    setFieldErrors(errors);
    return Object.keys(errors).length === 0;
  }

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();
    setSubmitError(null);
    if (!validate()) return;

    setSubmitting(true);
    const res = await submitMembershipForm(values);
    setSubmitting(false);

    if (res.status === "ok") {
      setSuccess(true);
    } else {
      setSubmitError(res.message);
    }
  }

  if (success) return <SuccessState />;
  if (loadState === "loading") return <LoadingState />;
  if (loadState === "error") {
    return <ErrorState message={loadError} onRetry={() => setReloadToken((t) => t + 1)} />;
  }

  return (
    <form onSubmit={handleSubmit} noValidate className="mx-auto w-full max-w-2xl space-y-6">
      {submitError && (
        <div className="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-300">
          {submitError}
        </div>
      )}

      {fields.length === 0 ? (
        <p className="text-center text-muted-foreground">
          The membership form hasn&apos;t been configured yet. Please check back soon.
        </p>
      ) : (
        <>
          {fields.map((field) => (
            <FieldRenderer
              key={field.field_key}
              field={field}
              value={values[field.field_key]}
              error={fieldErrors[field.field_key]}
              onChange={(value) => updateValue(field.field_key, value)}
              onToggleCheckbox={(option, checked) => toggleCheckbox(field.field_key, option, checked)}
            />
          ))}

          <button
            type="submit"
            disabled={submitting}
            className="w-full rounded-full bg-[var(--primary)] px-6 py-3.5 text-sm font-semibold text-[var(--primary-foreground)] shadow-lg transition-transform hover:scale-[1.01] disabled:cursor-not-allowed disabled:opacity-60"
          >
            {submitting ? "Submitting…" : "Submit Application"}
          </button>
        </>
      )}
    </form>
  );
}

function FieldRenderer({
  field,
  value,
  error,
  onChange,
  onToggleCheckbox,
}: {
  field: MembershipField;
  value: string | string[] | undefined;
  error?: string;
  onChange: (value: string | string[]) => void;
  onToggleCheckbox: (option: string, checked: boolean) => void;
}) {
  const inputId = `field-${field.field_key}`;
  const baseInputClasses =
    "w-full rounded-xl border bg-surface px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-[var(--primary)]";
  const borderClass = error ? "border-red-400 dark:border-red-800" : "border-border";

  return (
    <div>
      <label htmlFor={inputId} className="mb-2 block text-sm font-semibold text-foreground">
        {field.label}
        {field.required && <span className="ml-1 text-[var(--primary)]">*</span>}
      </label>

      {field.field_type === "textarea" && (
        <textarea
          id={inputId}
          rows={4}
          value={(value as string) ?? ""}
          placeholder={field.placeholder ?? undefined}
          onChange={(e) => onChange(e.target.value)}
          className={clsx(baseInputClasses, borderClass)}
        />
      )}

      {(["text", "email", "tel", "date"] as const).includes(
        field.field_type as "text" | "email" | "tel" | "date"
      ) && (
        <input
          id={inputId}
          type={field.field_type}
          value={(value as string) ?? ""}
          placeholder={field.placeholder ?? undefined}
          onChange={(e) => onChange(e.target.value)}
          className={clsx(baseInputClasses, borderClass)}
        />
      )}

      {field.field_type === "select" && (
        <select
          id={inputId}
          value={(value as string) ?? ""}
          onChange={(e) => onChange(e.target.value)}
          className={clsx(baseInputClasses, borderClass)}
        >
          <option value="" disabled>
            {field.placeholder || "Select an option"}
          </option>
          {(field.options ?? []).map((option) => (
            <option key={option} value={option}>
              {option}
            </option>
          ))}
        </select>
      )}

      {field.field_type === "radio" && (
        <div className="space-y-2">
          {(field.options ?? []).map((option) => (
            <label key={option} className="flex items-center gap-2.5 text-sm text-foreground">
              <input
                type="radio"
                name={inputId}
                value={option}
                checked={value === option}
                onChange={() => onChange(option)}
                className="h-4 w-4 accent-[var(--primary)]"
              />
              {option}
            </label>
          ))}
        </div>
      )}

      {field.field_type === "checkbox" && (
        <div className="space-y-2">
          {(field.options ?? []).map((option) => {
            const checked = Array.isArray(value) && value.includes(option);
            return (
              <label key={option} className="flex items-center gap-2.5 text-sm text-foreground">
                <input
                  type="checkbox"
                  value={option}
                  checked={checked}
                  onChange={(e) => onToggleCheckbox(option, e.target.checked)}
                  className="h-4 w-4 accent-[var(--primary)]"
                />
                {option}
              </label>
            );
          })}
        </div>
      )}

      {field.help_text && <p className="mt-1.5 text-xs text-muted-foreground">{field.help_text}</p>}
      {error && <p className="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{error}</p>}
    </div>
  );
}

function LoadingState() {
  return (
    <div className="mx-auto flex max-w-2xl flex-col items-center gap-4 py-12 text-center">
      <span className="h-10 w-10 animate-spin rounded-full border-2 border-border border-t-[var(--primary)]" />
      <p className="text-muted-foreground">Loading the membership form…</p>
    </div>
  );
}

function ErrorState({ message, onRetry }: { message: string | null; onRetry: () => void }) {
  return (
    <div className="mx-auto flex max-w-md flex-col items-center gap-4 rounded-2xl border border-border bg-surface px-8 py-12 text-center">
      <span className="flex h-14 w-14 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-950/50 dark:text-red-400">
        <svg viewBox="0 0 24 24" fill="none" className="h-7 w-7" aria-hidden="true">
          <path d="M12 8.5v5M12 16.5h.01" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
          <circle cx="12" cy="12" r="9" stroke="currentColor" strokeWidth="1.6" />
        </svg>
      </span>
      <h2 className="font-heading text-xl font-semibold text-foreground">Couldn&apos;t load the form</h2>
      <p className="text-sm text-muted-foreground">
        {message || "We couldn't reach the church server. Please try again."}
      </p>
      <button
        type="button"
        onClick={onRetry}
        className="rounded-full bg-[var(--primary)] px-6 py-2.5 text-sm font-semibold text-[var(--primary-foreground)]"
      >
        Try Again
      </button>
    </div>
  );
}

function SuccessState() {
  return (
    <motion.div
      initial={{ opacity: 0, y: 12 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="mx-auto flex max-w-md flex-col items-center gap-4 rounded-2xl border border-border bg-surface px-8 py-14 text-center"
    >
      <span className="flex h-16 w-16 items-center justify-center rounded-full bg-[color-mix(in_srgb,var(--primary)_16%,transparent)] text-[var(--primary)]">
        <svg viewBox="0 0 24 24" fill="none" className="h-8 w-8" aria-hidden="true">
          <path
            d="M5 12.5l4.5 4.5L19 7"
            stroke="currentColor"
            strokeWidth="2.2"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
        </svg>
      </span>
      <h2 className="font-heading text-2xl font-semibold text-foreground">Thank You!</h2>
      <p className="text-muted-foreground">
        Your membership application has been received and is pending review. We&apos;ll be in touch
        soon — we&apos;re so glad you reached out.
      </p>
      <Link
        href="/"
        className="mt-2 rounded-full border border-border px-6 py-2.5 text-sm font-semibold text-foreground transition-colors hover:border-[var(--primary)] hover:text-[var(--primary)]"
      >
        Back to Home
      </Link>
    </motion.div>
  );
}
