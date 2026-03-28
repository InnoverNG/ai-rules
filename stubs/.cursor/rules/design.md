# Design System Style Guide

> A temperature-driven, token-based UI system. No pure neutrals. Every surface carries the brand.

> **Bootstrap compatibility:** All component classes use the `ui-` prefix (e.g. `.ui-card`, `.ui-btn`, `.ui-badge`) to avoid collisions with Bootstrap's own classes. CSS custom properties (`--color-*`, `--shadow-*`) use a separate namespace and do not conflict with Bootstrap's `--bs-*` tokens. This system is designed to coexist with Bootstrap — use Bootstrap's grid and utilities, use this system's tokens and components.

---

## Table of Contents

1. [Core Philosophy](#1-core-philosophy)
2. [Color Tokens](#2-color-tokens)
3. [Theme Reference](#3-theme-reference)
4. [Typography](#4-typography)
5. [Shadows](#5-shadows)
6. [Spacing & Radius](#6-spacing--radius)
7. [Breakpoints](#7-breakpoints)
8. [Z-Index Scale](#8-z-index-scale)
9. [Component Classes](#9-component-classes)
10. [Animation](#10-animation)
11. [Focus & Accessibility](#11-focus--accessibility)
12. [Dark Mode Rules](#12-dark-mode-rules)
13. [The Greyscale Test](#13-the-greyscale-test)
14. [Anti-Patterns](#14-anti-patterns)

---

## 1. Core Philosophy

Three decisions make a UI look expensive:

| Decision               | Rule                                                               |
| ---------------------- | ------------------------------------------------------------------ |
| **Color temperature**  | Every neutral carries a trace of the brand hue — never a pure grey |
| **Shadow temperature** | Shadows match the brand's color family — never pure black          |
| **Text hierarchy**     | All three text tiers lean the same hue direction                   |

The principle is coherence. The brand should feel like an intensification of the surface it sits on, not a foreign element dropped on top.

---

## 2. Color Tokens

All colors are defined as CSS custom properties on the `html` element via `data-theme`. Never hardcode hex values in components — always reference tokens.

```css
/* Usage */
background: var(--color-bg);
color: var(--color-text);
border-color: var(--color-border);
box-shadow: var(--shadow-md);
```

### Token Definitions

| Token                   | Purpose                                    | Notes                                |
| ----------------------- | ------------------------------------------ | ------------------------------------ |
| `--color-bg`            | Page background                            | Lightest surface, carries brand tint |
| `--color-surface`       | Cards, panels, modals                      | Slightly lighter than bg             |
| `--color-surface-2`     | Table headers, browser bars, nested panels | Deepest light surface                |
| `--color-border`        | Dividers, outlines, card edges             | Always tinted, never #e5e5e5         |
| `--color-border-strong` | Focused inputs, emphasized dividers        | Stronger tint version                |
| `--color-text`          | Primary headings and text                  | Near-black with brand undertone      |
| `--color-text-2`        | Body copy, descriptions                    | Mid-tone, same hue family            |
| `--color-text-muted`    | Labels, captions, metadata                 | Lightest readable, still tinted      |
| `--color-brand`         | CTAs, links, accents, active states        | The full brand color                 |
| `--color-brand-hover`   | Brand element hover state                  | Slightly darker/brighter than brand  |
| `--color-brand-subtle`  | Badge backgrounds, highlight fills         | Very diluted brand tint              |
| `--color-success`       | Positive states, confirmations             | Warm green, tinted toward brand hue  |
| `--color-success-subtle`| Success badge backgrounds                  | Very diluted success tint            |
| `--color-warning`       | Alerts, degraded states                    | Amber, never pure yellow             |
| `--color-warning-subtle`| Warning badge backgrounds                  | Very diluted warning tint            |
| `--color-error`         | Destructive actions, validation errors     | Red, tinted toward brand temperature |
| `--color-error-subtle`  | Error badge backgrounds, input error fills | Very diluted error tint              |
| `--shadow-sm`           | Subtle card lift                           | Use on small/nested cards            |
| `--shadow-md`           | Default card shadow                        | Use on primary content cards         |
| `--shadow-lg`           | Modals, popovers, feature cards            | Use on elevated elements             |

---

## 3. Theme Reference

Switch themes via `data-theme` on the `<html>` element:

```html
<html data-theme="warm"><!-- amber / rust --></html>
<html data-theme="cool"><!-- blue / slate --></html>
<html data-theme="dark"><!-- navy dark, glows --></html>
```

### Warm Theme

> Editorial, premium SaaS, content-first products

```css
[data-theme="warm"] {
  --color-bg: #f7f3ee;
  --color-surface: #fdfaf7;
  --color-surface-2: #f2ede6;
  --color-border: #e8ddd2;
  --color-border-strong: #c8b8a8;
  --color-text: #1a1410;
  --color-text-2: #4a3e34;
  --color-text-muted: #9a8878;
  --color-brand: #c4502a;
  --color-brand-hover: #a83e20;
  --color-brand-subtle: #f5eae4;
  --color-success: #2d7a4a;
  --color-success-subtle: #eaf4ee;
  --color-warning: #b06b10;
  --color-warning-subtle: #fdf2e3;
  --color-error: #c0392b;
  --color-error-subtle: #fdecea;
  --shadow-sm:
    0 1px 3px rgba(100, 60, 30, 0.08), 0 0 0 1px rgba(100, 60, 30, 0.04);
  --shadow-md:
    0 4px 16px rgba(100, 60, 30, 0.1), 0 1px 4px rgba(100, 60, 30, 0.06);
  --shadow-lg:
    0 12px 40px rgba(100, 60, 30, 0.12), 0 2px 8px rgba(100, 60, 30, 0.06);
}
```

### Cool Theme

> Developer tools, B2B tech, data products

```css
[data-theme="cool"] {
  --color-bg: #f4f6fa;
  --color-surface: #fafbfd;
  --color-surface-2: #edf0f7;
  --color-border: #dde3ee;
  --color-border-strong: #bcc6da;
  --color-text: #0f1520;
  --color-text-2: #3a4560;
  --color-text-muted: #7a8ba0;
  --color-brand: #2563eb;
  --color-brand-hover: #1a4fbe;
  --color-brand-subtle: #eef3ff;
  --color-success: #16803c;
  --color-success-subtle: #e8f7ee;
  --color-warning: #b45309;
  --color-warning-subtle: #fef3e2;
  --color-error: #dc2626;
  --color-error-subtle: #fef2f2;
  --shadow-sm:
    0 1px 3px rgba(30, 50, 100, 0.08), 0 0 0 1px rgba(30, 50, 100, 0.04);
  --shadow-md:
    0 4px 16px rgba(30, 50, 100, 0.1), 0 1px 4px rgba(30, 50, 100, 0.06);
  --shadow-lg:
    0 12px 40px rgba(30, 50, 100, 0.12), 0 2px 8px rgba(30, 50, 100, 0.06);
}
```

### Dark Theme

> Night mode, dev tools, immersive experiences

```css
[data-theme="dark"] {
  --color-bg: #0f1117;
  --color-surface: #161b27;
  --color-surface-2: #1a2035;
  --color-border: #1e2738;
  --color-border-strong: #2a3650;
  --color-text: #e8edf5;
  --color-text-2: #8a9ab5;
  --color-text-muted: #4a5870;
  --color-brand: #5b8def;
  --color-brand-hover: #7aa3f5;
  --color-brand-subtle: #1a2540;
  --color-success: #34d27a;
  --color-success-subtle: #0d2a1a;
  --color-warning: #f59e0b;
  --color-warning-subtle: #2a1e08;
  --color-error: #f87171;
  --color-error-subtle: #2a0f0f;
  /* Dark uses glows, not shadows */
  --shadow-sm: 0 0 0 1px rgba(100, 140, 255, 0.08);
  --shadow-md:
    0 4px 20px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(100, 140, 255, 0.06);
  --shadow-lg:
    0 16px 48px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(100, 140, 255, 0.08);
}
```

---

## 4. Typography

### Font Stack

```css
@import url("https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;1,9..144,100;1,9..144,200;1,9..144,300&family=Chivo+Mono:wght@300;400&family=Instrument+Sans:wght@300;400;500&display=swap");
```

| Role                | Font            | Weight  | Style             | Use for                         |
| ------------------- | --------------- | ------- | ----------------- | ------------------------------- |
| **Display / Hero**  | Fraunces        | 200–300 | Italic            | Hero titles, section headings   |
| **Heading**         | Fraunces        | 300     | Normal or italic  | H2, H3                          |
| **Body**            | Instrument Sans | 300–500 | Normal            | Paragraphs, descriptions        |
| **Label / Eyebrow** | Chivo Mono      | 300–400 | Normal, uppercase | Section numbers, tags, metadata |
| **Code / Token**    | Chivo Mono      | 300–400 | Normal            | Code snippets, token names      |

### Type Scale

```css
/* Hero */
.ui-hero h1 {
  font-family: "Fraunces", serif;
  font-weight: 200;
  font-style: italic;
  font-size: clamp(36px, 5.2vw, 62px);
  line-height: 1.06;
  letter-spacing: -0.02em;
  color: var(--color-text);
}

/* Section heading */
.ui-section h2 {
  font-family: "Fraunces", serif;
  font-weight: 300;
  font-size: clamp(20px, 2.6vw, 30px);
  line-height: 1.2;
  letter-spacing: -0.01em;
  color: var(--color-text);
}

/* Eyebrow / section label */
.ui-eyebrow {
  font-family: "Chivo Mono", monospace;
  font-size: 10.5px;
  font-weight: 300;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--color-brand);
}

/* Section number */
.ui-sec-num {
  font-family: "Chivo Mono", monospace;
  font-size: 10px;
  font-weight: 300;
  letter-spacing: 0.12em;
  color: var(--color-brand);
  opacity: 0.65;
}

/* Body / intro */
.ui-section-intro {
  font-family: "Instrument Sans", sans-serif;
  font-size: 14px;
  font-weight: 300;
  color: var(--color-text-2);
  line-height: 1.75;
}
```

### Text Hierarchy Rule

Every text tier must lean the same hue direction. Never mix a warm heading with a pure grey subtitle.

```
Primary text   →  Near-black WITH brand undertone    e.g. #1a1410 (warm)
Secondary text →  Mid-tone, same hue family          e.g. #4a3e34 (warm)
Muted text     →  Lighter, still tinted              e.g. #9a8878 (warm)
```

---

## 5. Shadows

### The Rule

> Shadow color = brand RGBA. Never `rgba(0,0,0,x)` on a tinted surface.

```
Warm brand  →  rgba(100, 60, 30, x)
Cool brand  →  rgba(30, 50, 100, x)
Dark mode   →  rgba(100, 140, 255, x)  as glow, not shadow
```

### Shadow Scale

```css
/* Small — nested cards, badges with depth */
--shadow-sm: 0 1px 3px rgba(BRAND, 0.08), 0 0 0 1px rgba(BRAND, 0.04);

/* Medium — primary content cards */
--shadow-md: 0 4px 16px rgba(BRAND, 0.1), 0 1px 4px rgba(BRAND, 0.06);

/* Large — modals, hero cards, popovers */
--shadow-lg: 0 12px 40px rgba(BRAND, 0.12), 0 2px 8px rgba(BRAND, 0.06);

/* Dark mode glow (replaces all above) */
--shadow-md: 0 4px 20px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(100, 140, 255, 0.06);
```

---

## 6. Spacing & Radius

### Border Radius

| Context                           | Value           |
| --------------------------------- | --------------- |
| Cards, panels, modals             | `12px` – `14px` |
| Buttons, inputs, small containers | `6px`           |
| Badges, tags, pills               | `4px`           |
| Dots, avatars                     | `50%`           |

### Spacing Scale

```css
--space-1:  4px;   /* gap between inline elements (dots, icons) */
--space-2:  8px;   /* tight internal padding (badges, tags) */
--space-3: 14px;   /* grid gap between cards */
--space-4: 18px;   /* card internal padding (compact) */
--space-5: 22px;   /* card internal padding (default) */
--space-6: 26px;   /* card internal padding (generous) */
--space-7: 28px;   /* section intro margin */
--space-8: 88px;   /* section bottom margin */
```

---

## 7. Breakpoints

Use Bootstrap's breakpoints as the layout grid — this system does not redefine them. Apply type scale and spacing adjustments at these points:

| Name | Min-width | Typical target          |
| ---- | --------- | ----------------------- |
| `sm` | `576px`   | Large phones, landscape |
| `md` | `768px`   | Tablets                 |
| `lg` | `992px`   | Small desktops          |
| `xl` | `1200px`  | Standard desktop        |
| `xxl`| `1400px`  | Wide desktop            |

### Responsive rules for this system

- Hero `font-size` uses `clamp()` — no breakpoint override needed
- Card padding steps down: `26px` → `18px` at `md` and below
- Section bottom margin steps down: `88px` → `48px` at `md` and below
- Stack horizontal card grids to single column at `sm`

```css
@media (max-width: 768px) {
  .ui-card { padding: var(--space-4); }
  .ui-section { margin-bottom: 48px; }
}
```

---

## 8. Z-Index Scale

Never use arbitrary z-index values. All stacking must reference these tokens:

```css
--z-base:    0;    /* Normal document flow */
--z-raised:  10;   /* Sticky headers, floating labels */
--z-dropdown: 100; /* Dropdowns, select menus */
--z-sticky:  200;  /* Sticky nav bars */
--z-overlay: 300;  /* Drawer backdrops, dimmer layers */
--z-modal:   400;  /* Modals, dialogs */
--z-toast:   500;  /* Toast notifications, snackbars */
--z-tooltip: 600;  /* Tooltips (always on top) */
```

Define these on `:root` alongside the color tokens. Never write `z-index: 9999` — it signals an unknown stacking context.

---

## 9. Component Classes

### Card

```css
/* Use .ui-card — not .card (conflicts with Bootstrap) */
.ui-card {
  background: var(--color-surface);
  border: 1px solid var(--color-border);
  border-radius: 14px;
  padding: 26px 22px;
  box-shadow: var(--shadow-md);
  transition:
    box-shadow 0.25s,
    transform 0.2s;
}
.ui-card:hover {
  box-shadow: var(--shadow-lg);
  transform: translateY(-2px);
}
```

### Badge / Tag

```css
/* Use .ui-badge — not .badge (conflicts with Bootstrap) */
/* Always brand-subtle bg + brand text. Never grey. */
.ui-badge {
  display: inline-block;
  background: var(--color-brand-subtle);
  color: var(--color-brand);
  font-family: "Chivo Mono", monospace;
  font-size: 9.5px;
  font-weight: 400;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  padding: 4px 10px;
  border-radius: 4px;
}
```

### Eyebrow (section label above heading)

```css
.ui-eyebrow {
  display: flex;
  align-items: center;
  gap: 12px;
  font-family: "Chivo Mono", monospace;
  font-size: 10.5px;
  font-weight: 300;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--color-brand);
  margin-bottom: 20px;
}
/* Optional leading rule line */
.ui-eyebrow::before {
  content: "";
  display: block;
  width: 22px;
  height: 1px;
  background: var(--color-brand);
  opacity: 0.6;
}
```

### Callout / Blockquote

```css
.ui-callout {
  background: var(--color-brand-subtle);
  border-left: 3px solid var(--color-brand);
  border-radius: 0 6px 6px 0;
  padding: 14px 18px;
  font-size: 13.5px;
  font-style: italic;
  color: var(--color-text-2);
  line-height: 1.7;
}
```

### Button

```css
/* Use .ui-btn — not .btn or .btn-primary (conflicts with Bootstrap) */
.ui-btn {
  font-family: "Chivo Mono", monospace;
  font-size: 10px;
  font-weight: 400;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  padding: 8px 18px;
  border-radius: 6px;
  border: 1px solid var(--color-border);
  background: var(--color-surface);
  color: var(--color-text-muted);
  cursor: pointer;
  transition: all 0.2s;
}
.ui-btn:hover {
  border-color: var(--color-border-strong);
  color: var(--color-text);
}
.ui-btn--primary {
  background: var(--color-brand);
  border-color: var(--color-brand);
  color: #fff;
}
.ui-btn--primary:hover {
  background: var(--color-brand);
  filter: brightness(1.08);
}
.ui-btn:focus-visible {
  outline: 2px solid var(--color-brand);
  outline-offset: 3px;
}
```

### Table

```css
.ui-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 12.5px;
  border-radius: 8px;
  overflow: hidden;
  border: 1px solid var(--color-border);
}
.ui-table th {
  font-family: "Chivo Mono", monospace;
  font-size: 9.5px;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--color-text-muted);
  background: var(--color-surface-2);
  padding: 10px 14px;
  text-align: left;
  border-bottom: 1px solid var(--color-border);
}
.ui-table td {
  padding: 10px 14px;
  color: var(--color-text-2);
  border-bottom: 1px solid var(--color-border);
}
.ui-table tr:last-child td {
  border-bottom: none;
}
.ui-table tr:hover td {
  background: var(--color-surface-2);
}
```

### Form Input

```css
.ui-input {
  width: 100%;
  font-family: "Instrument Sans", sans-serif;
  font-size: 13.5px;
  font-weight: 300;
  color: var(--color-text);
  background: var(--color-surface);
  border: 1px solid var(--color-border);
  border-radius: 6px;
  padding: 9px 12px;
  outline: none;
  transition:
    border-color 0.2s,
    box-shadow 0.2s;
}
.ui-input::placeholder {
  color: var(--color-text-muted);
}
.ui-input:hover {
  border-color: var(--color-border-strong);
}
.ui-input:focus {
  border-color: var(--color-brand);
  box-shadow: 0 0 0 3px var(--color-brand-subtle);
}

/* Validation states */
.ui-input--error {
  border-color: var(--color-error);
}
.ui-input--error:focus {
  box-shadow: 0 0 0 3px var(--color-error-subtle);
}
.ui-input--success {
  border-color: var(--color-success);
}

/* Label */
.ui-label {
  display: block;
  font-family: "Chivo Mono", monospace;
  font-size: 10px;
  font-weight: 400;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--color-text-muted);
  margin-bottom: 6px;
}

/* Helper / error text below input */
.ui-field-hint {
  font-family: "Instrument Sans", sans-serif;
  font-size: 11.5px;
  color: var(--color-text-muted);
  margin-top: 5px;
}
.ui-field-hint--error {
  color: var(--color-error);
}
```

### Alert / Status Banner

```css
/* Base — always use a state token, never a generic grey */
.ui-alert {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  padding: 12px 16px;
  border-radius: 6px;
  border-left: 3px solid;
  font-size: 13px;
  line-height: 1.6;
}
.ui-alert--success {
  background: var(--color-success-subtle);
  border-color: var(--color-success);
  color: var(--color-success);
}
.ui-alert--warning {
  background: var(--color-warning-subtle);
  border-color: var(--color-warning);
  color: var(--color-warning);
}
.ui-alert--error {
  background: var(--color-error-subtle);
  border-color: var(--color-error);
  color: var(--color-error);
}
```

### Browser / App Chrome Frame

```css
.ui-browser {
  border-radius: 14px;
  background: var(--color-surface);
  border: 1px solid var(--color-border);
  box-shadow: var(--shadow-lg);
  overflow: hidden;
}
.ui-browser-bar {
  background: var(--color-surface-2);
  border-bottom: 1px solid var(--color-border);
  padding: 11px 18px;
  display: flex;
  align-items: center;
  gap: 7px;
}
/* Traffic light dots — use brand color at decreasing opacity */
.ui-dot-1 {
  background: var(--color-brand);
  opacity: 0.5;
}
.ui-dot-2 {
  background: var(--color-brand);
  opacity: 0.28;
}
.ui-dot-3 {
  background: var(--color-brand);
  opacity: 0.12;
}
```

---

## 10. Animation

### Rise (standard entry animation)

All sections and cards animate in on load. Opacity 0 → 1, translateY 14px → 0.

```css
@keyframes rise {
  from {
    opacity: 0;
    transform: translateY(14px);
  }
  to {
    opacity: 1;
    transform: none;
  }
}

/* Apply with staggered delays */
.ui-section {
  opacity: 0;
  animation: rise 0.6s forwards;
}
.ui-section:nth-child(1) {
  animation-delay: 0.06s;
}
.ui-section:nth-child(2) {
  animation-delay: 0.12s;
}
.ui-section:nth-child(3) {
  animation-delay: 0.18s;
}
/* +0.06s per subsequent child */
```

### Hover Transitions

```css
/* Cards */
transition:
  box-shadow 0.25s,
  transform 0.2s;

/* Color / background shifts */
transition:
  background 0.35s,
  border-color 0.35s,
  color 0.3s;

/* Theme switch (applied to body) */
transition:
  background 0.4s,
  color 0.3s;
```

### Reduced Motion

Always wrap animations in a motion query. Users with vestibular disorders rely on this.

```css
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
```

---

## 11. Focus & Accessibility

Focus states must always be visible. Never `outline: none` without a replacement. Use `:focus-visible` (keyboard only) not `:focus` (fires on mouse click too).

### Focus Ring Token

```css
/* Applied to all interactive elements */
:focus-visible {
  outline: 2px solid var(--color-brand);
  outline-offset: 3px;
}

/* For elements with their own border (inputs, cards) */
:focus-visible {
  outline: none;
  box-shadow: 0 0 0 3px var(--color-brand-subtle), 0 0 0 5px var(--color-brand);
}
```

### Rules

| Rule | Requirement |
| ---- | ----------- |
| Focus ring color | Always `--color-brand` — never grey or invisible |
| Focus ring on dark | Increase opacity; add a light halo using `--color-brand-subtle` |
| Buttons | Use `:focus-visible` with `outline-offset: 3px` |
| Inputs | Use `box-shadow` focus ring (border is already occupied by validation state) |
| Interactive cards | Add `tabindex="0"` and a `:focus-visible` ring |
| Minimum touch target | `44px × 44px` per WCAG 2.5.5 |
| Color contrast | Body text on bg must meet WCAG AA (4.5:1). All three themes are pre-validated. |
| Never remove focus | `outline: none` with no replacement is a WCAG failure |

---

## 12. Dark Mode Rules

Dark mode is **not an inverted light mode**. It follows different rules:

| Rule       | Light                     | Dark                                        |
| ---------- | ------------------------- | ------------------------------------------- |
| Background | Tinted off-white          | Navy-dark (`#0f1117`), not zinc-900         |
| Shadows    | Brand-tinted drop shadows | Brand-colored glows (`rgba(100,140,255,x)`) |
| Borders    | Tinted light grey         | Dark navy (`#1e2738`)                       |
| Surface    | Slightly lighter than bg  | Slightly lighter navy (`#161b27`)           |
| Text       | Near-black with tint      | Off-white with cool tint (`#e8edf5`)        |

**Never use zinc-900 (`#18181b`) as a dark background** — it has no temperature and reads as a browser default.

---

## 13. The Greyscale Test

Before shipping, desaturate the UI. If:

- ✅ **Hierarchy still reads** — the tints are doing cosmetic work only. Pass.
- ❌ **Layout collapses** — the tints were doing structural work. Fail. Fix contrast values.

Color temperature is a layer on top of a sound greyscale system, not a replacement for it.

---

## 14. Anti-Patterns

Avoid these in every project:

| Anti-Pattern                             | Why It Fails                                   | Fix                                                         |
| ---------------------------------------- | ---------------------------------------------- | ----------------------------------------------------------- |
| `background: #ffffff` on any surface     | Belongs to no brand                            | Use `var(--color-surface)`                                  |
| `color: #666666` for secondary text      | Pure grey, no temperature                      | Use `var(--color-text-2)`                                   |
| `box-shadow: 0 4px 12px rgba(0,0,0,0.1)` | Black shadow on tinted surface looks pasted on | Match shadow RGBA to brand color                            |
| Badge with `background: #e5e5e5`         | Generic, brandless                             | Use `var(--color-brand-subtle)` + `var(--color-brand)` text |
| Hover state with grey overlay            | Breaks temperature coherence                   | Use `var(--color-surface-2)` or `var(--color-brand-subtle)` |
| Dark bg `#18181b` (zinc-900)             | No hue, no brand, looks like browser default   | Use navy-dark `#0f1117`                                     |
| Hardcoded hex in components              | Breaks theme switching                         | Always use CSS token variables                              |
| Using `.card`, `.badge`, `.btn`          | Conflicts with Bootstrap's own components      | Always use `ui-` prefixed classes from this system          |
| `outline: none` with no replacement      | Keyboard users lose focus indicator (WCAG fail)| Use `:focus-visible` with brand outline                     |
| `z-index: 9999`                          | Signals unknown stacking context               | Use `--z-*` tokens from the z-index scale                   |
| Hardcoded success/error hex colors       | Breaks dark mode and temperature coherence     | Use `--color-success`, `--color-error`, `--color-warning`   |
| No `prefers-reduced-motion` block        | Causes motion sickness for affected users      | Always include the reduced-motion reset                     |

---
