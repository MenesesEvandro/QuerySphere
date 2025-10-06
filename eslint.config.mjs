import js from "@eslint/js";
import globals from "globals";
import json from "@eslint/json";
import markdown from "@eslint/markdown";
import css from "@eslint/css";
import prettier from "eslint-plugin-prettier";
import prettierConfig from "eslint-config-prettier";

export default [
  // JavaScript, Module, and CommonJS files
  {
    files: ["**/*.{js,mjs,cjs}"],
    plugins: { prettier },
    ...js.configs.recommended,
    ...prettierConfig,
    languageOptions: {
      globals: globals.browser,
      sourceType: "module", // Default to ES modules for .js, .mjs, .cjs
    },
  },
  // Override for CommonJS files (if any)
  {
    files: ["**/*.cjs"],
    languageOptions: { sourceType: "commonjs" },
  },
  // JSON files
  {
    files: ["**/*.json"],
    plugins: { json },
    language: "json/json",
    ...json.configs.recommended,
    ...prettierConfig,
  },
  // JSONC files
  {
    files: ["**/*.jsonc"],
    plugins: { json },
    language: "json/jsonc",
    ...json.configs.recommended,
    ...prettierConfig,
  },
  // JSON5 files
  {
    files: ["**/*.json5"],
    plugins: { json },
    language: "json/json5",
    ...json.configs.recommended,
    ...prettierConfig,
  },
  // Markdown files
  {
    files: ["**/*.md"],
    plugins: { markdown },
    language: "markdown/gfm",
    ...markdown.configs.recommended,
    ...prettierConfig,
  },
  // CSS files
  {
    files: ["**/*.css"],
    plugins: { css },
    language: "css/css",
    ...css.configs.recommended,
    ...prettierConfig,
  },
];