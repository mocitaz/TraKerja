// ============================================================
// renderFormattedText.js — TraKerja CV Formatter v4.0 (Premium)
// ============================================================

import React from "react";

/**
 * STEP 1: preprocessLines
 * - Merapikan spasi dan memastikan header memiliki jarak yang cukup
 */
function preprocessLines(text) {
  const rawLines = text.split("\n");
  const result = [];

  for (const line of rawLines) {
    const trimmed = line.trim();
    const boldMatches = [...trimmed.matchAll(/\*\*[^*]+\*\*/g)];

    if (boldMatches.length > 0 && !trimmed.startsWith("**")) {
      const firstMatch = boldMatches[0];
      const beforeBold = trimmed.slice(0, firstMatch.index).trim();
      const boldAndAfter = trimmed.slice(firstMatch.index).trim();

      if (beforeBold.length > 0) {
        result.push(beforeBold);
        result.push("");
        result.push(boldAndAfter);
        continue;
      }
    }

    const isPureHeader = /^\*\*[^*]+\*\*$/.test(trimmed);
    if (isPureHeader && result.length > 0 && result[result.length - 1].trim() !== "") {
      result.push("");
    }

    result.push(line);
  }
  return result;
}

/**
 * STEP 2: parseInline
 * Parse **bold** dan *[estimasi]* menjadi React Nodes
 */
function parseInline(text) {
  const regex = /\*\*([^*]+)\*\*|\*\[estimasi\]\*/g;
  const nodes = [];
  let lastIndex = 0;
  let match;

  while ((match = regex.exec(text)) !== null) {
    if (match.index > lastIndex) {
      nodes.push(text.slice(lastIndex, match.index));
    }

    if (match[1] !== undefined) {
      nodes.push(
        <strong key={match.index} style={{ fontWeight: 800, color: "#0f172a" }}>
          {match[1]}
        </strong>
      );
    } else {
      nodes.push(
        <span
          key={match.index}
          style={{
            display: "inline-flex",
            alignItems: "center",
            padding: "1px 6px",
            backgroundColor: "#fff1f2",
            color: "#e11d48",
            borderRadius: "4px",
            fontSize: "0.75em",
            fontWeight: 700,
            marginLeft: "4px",
            textTransform: "uppercase",
            letterSpacing: "0.025em"
          }}
        >
          Estimasi
        </span>
      );
    }
    lastIndex = regex.lastIndex;
  }

  if (lastIndex < text.length) {
    nodes.push(text.slice(lastIndex));
  }
  return nodes.length > 0 ? nodes : [text];
}

/**
 * STEP 3: classifyLine
 */
function classifyLine(line) {
  const trimmed = line.trim();
  if (trimmed === "") return "spacer";
  if (/^\*\*[^*]+\*\*$/.test(trimmed)) return "header";
  if (trimmed.startsWith("•") || trimmed.startsWith("-") || trimmed.startsWith("* ")) return "bullet";
  return "text";
}

/**
 * React Component Main Export
 */
export default function FormattedText({ text, className = "" }) {
  if (!text) return null;

  const lines = preprocessLines(text);

  return (
    <div className={className} style={{ wordBreak: "break-word", fontFamily: "'Plus Jakarta Sans', sans-serif" }}>
      {lines.map((line, index) => {
        const type = classifyLine(line);

        if (type === "spacer") {
          return <div key={index} style={{ height: "14px" }} />;
        }

        if (type === "header") {
          return (
            <p
              key={index}
              style={{
                fontWeight: 800,
                color: "#0f172a",
                margin: "12px 0 4px 0",
                fontSize: "1em",
                lineHeight: "1.4",
                letterSpacing: "-0.01em"
              }}
            >
              {parseInline(line.trim())}
            </p>
          );
        }

        if (type === "bullet") {
          return (
            <div
              key={index}
              style={{
                display: "flex",
                alignItems: "flex-start",
                gap: "10px",
                marginBottom: "6px",
              }}
            >
              <span style={{ 
                marginTop: "8px", 
                flexShrink: 0, 
                width: "5px", 
                height: "5px", 
                backgroundColor: "#3b82f6", 
                borderRadius: "50%" 
              }}></span>
              <p style={{ margin: 0, color: "#334155", lineHeight: "1.6", fontSize: "0.95em" }}>
                {parseInline(line.trim().replace(/^[•\-\*]\s*/, ""))}
              </p>
            </div>
          );
        }

        return (
          <p
            key={index}
            style={{
              color: "#475569",
              lineHeight: "1.6",
              fontSize: "0.95em",
              margin: "0 0 8px 0",
            }}
          >
            {parseInline(line.trim())}
          </p>
        );
      })}
    </div>
  );
}

/**
 * HTML String — untuk Laravel Blade / Backend Rendering
 */
export function renderFormattedTextHTML(text) {
  if (!text) return "";

  const lines = preprocessLines(text);

  return lines
    .map((line) => {
      const type = classifyLine(line);

      if (type === "spacer") {
        return `<div style="height:14px;"></div>`;
      }

      const esc = (s) => s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
      const escaped = esc(line.trim());

      const withEst = escaped.replace(
        /\*\[estimasi\]\*/g,
        ` <span style="display:inline-flex;padding:1px 6px;background-color:#fff1f2;color:#e11d48;border-radius:4px;font-size:0.75em;font-weight:700;margin-left:4px;text-transform:uppercase;">Estimasi</span>`
      );

      const withBold = withEst.replace(
        /\*\*([^*]+)\*\*/g,
        `<strong style="font-weight:800;color:#0f172a;">$1</strong>`
      );

      if (type === "header") {
        return `<p style="font-weight:800;color:#0f172a;margin:12px 0 4px 0;font-size:1em;line-height:1.4;letter-spacing:-0.01em;">${withBold}</p>`;
      }

      if (type === "bullet") {
        const content = withBold.replace(/^[•\-\*]\s*/, "");
        return `<div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:6px;">
          <span style="margin-top:8px;flex-shrink:0;width:5px;height:5px;background-color:#3b82f6;border-radius:50%;"></span>
          <p style="margin:0;color:#334155;line-height:1.6;font-size:0.95em;">${content}</p>
        </div>`;
      }

      return `<p style="color:#475569;line-height:1.6;font-size:0.95em;margin:0 0 8px 0;">${withBold}</p>`;
    })
    .join("");
}