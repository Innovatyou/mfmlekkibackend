"use client";

import { useState, type FormEvent } from "react";
import { motion } from "framer-motion";
import { submitContactForm, type ContactPayload } from "@/lib/api";
import { hasText } from "@/lib/utils";

const EMPTY: ContactPayload = { name: "", email: "", phone: "", subject: "", message: "" };

export function ContactForm() {
  const [values, setValues] = useState<ContactPayload>(EMPTY);
  const [errors, setErrors] = useState<Partial<Record<keyof ContactPayload, string>>>({});
  const [submitting, setSubmitting] = useState(false);
  const [submitError, setSubmitError] = useState<string | null>(null);
  const [success, setSuccess] = useState(false);

  function update(key: keyof ContactPayload, value: string) {
    setValues((v) => ({ ...v, [key]: value }));
    setErrors((e) => (e[key] ? { ...e, [key]: undefined } : e));
  }

  function validate(): boolean {
    const next: Partial<Record<keyof ContactPayload, string>> = {};
    if (!hasText(values.name)) next.name = "Please enter your name.";
    if (!hasText(values.email)) next.email = "Please enter your email.";
    if (!hasText(values.message)) next.message = "Please enter a message.";
    setErrors(next);
    return Object.keys(next).length === 0;
  }

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();
    setSubmitError(null);
    if (!validate()) return;

    setSubmitting(true);
    const res = await submitContactForm(values);
    setSubmitting(false);

    if (res.status === "ok") {
      setSuccess(true);
    } else {
      setSubmitError(res.message);
    }
  }

  const baseInputClasses =
    "w-full rounded-xl border bg-surface px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-[var(--primary)]";

  if (success) {
    return (
      <motion.div
        initial={{ opacity: 0, y: 12 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.5 }}
        className="flex flex-col items-center gap-4 rounded-2xl border border-border bg-surface px-8 py-12 text-center"
      >
        <span className="flex h-14 w-14 items-center justify-center rounded-full bg-[color-mix(in_srgb,var(--primary)_16%,transparent)] text-[var(--primary)]">
          <svg viewBox="0 0 24 24" fill="none" className="h-7 w-7" aria-hidden="true">
            <path
              d="M5 12.5l4.5 4.5L19 7"
              stroke="currentColor"
              strokeWidth="2.2"
              strokeLinecap="round"
              strokeLinejoin="round"
            />
          </svg>
        </span>
        <h3 className="font-heading text-xl font-semibold text-foreground">Message Sent</h3>
        <p className="text-muted-foreground">
          Thank you for reaching out — we&apos;ll get back to you soon.
        </p>
        <button
          type="button"
          onClick={() => {
            setValues(EMPTY);
            setSuccess(false);
          }}
          className="mt-1 rounded-full border border-border px-5 py-2 text-sm font-semibold text-foreground transition-colors hover:border-[var(--primary)] hover:text-[var(--primary)]"
        >
          Send Another Message
        </button>
      </motion.div>
    );
  }

  return (
    <form onSubmit={handleSubmit} noValidate className="space-y-4 rounded-2xl border border-border bg-surface p-6 sm:p-7">
      {submitError && (
        <div className="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-300">
          {submitError}
        </div>
      )}

      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label htmlFor="contact-name" className="mb-1.5 block text-sm font-semibold text-foreground">
            Name<span className="ml-1 text-[var(--primary)]">*</span>
          </label>
          <input
            id="contact-name"
            type="text"
            value={values.name}
            onChange={(e) => update("name", e.target.value)}
            className={baseInputClasses}
          />
          {errors.name && <p className="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{errors.name}</p>}
        </div>
        <div>
          <label htmlFor="contact-email" className="mb-1.5 block text-sm font-semibold text-foreground">
            Email<span className="ml-1 text-[var(--primary)]">*</span>
          </label>
          <input
            id="contact-email"
            type="email"
            value={values.email}
            onChange={(e) => update("email", e.target.value)}
            className={baseInputClasses}
          />
          {errors.email && <p className="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{errors.email}</p>}
        </div>
      </div>

      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label htmlFor="contact-phone" className="mb-1.5 block text-sm font-semibold text-foreground">
            Phone <span className="font-normal text-muted-foreground">(optional)</span>
          </label>
          <input
            id="contact-phone"
            type="tel"
            value={values.phone}
            onChange={(e) => update("phone", e.target.value)}
            className={baseInputClasses}
          />
        </div>
        <div>
          <label htmlFor="contact-subject" className="mb-1.5 block text-sm font-semibold text-foreground">
            Subject <span className="font-normal text-muted-foreground">(optional)</span>
          </label>
          <input
            id="contact-subject"
            type="text"
            value={values.subject}
            onChange={(e) => update("subject", e.target.value)}
            className={baseInputClasses}
          />
        </div>
      </div>

      <div>
        <label htmlFor="contact-message" className="mb-1.5 block text-sm font-semibold text-foreground">
          Message<span className="ml-1 text-[var(--primary)]">*</span>
        </label>
        <textarea
          id="contact-message"
          rows={5}
          value={values.message}
          onChange={(e) => update("message", e.target.value)}
          className={baseInputClasses}
        />
        {errors.message && (
          <p className="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{errors.message}</p>
        )}
      </div>

      <button
        type="submit"
        disabled={submitting}
        className="w-full rounded-full bg-[var(--primary)] px-6 py-3.5 text-sm font-semibold text-[var(--primary-foreground)] shadow-lg transition-transform hover:scale-[1.01] disabled:cursor-not-allowed disabled:opacity-60"
      >
        {submitting ? "Sending…" : "Send Message"}
      </button>
    </form>
  );
}
