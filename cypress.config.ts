import { defineConfig } from "cypress";

export default defineConfig({
  allowCypressEnv: false,

  e2e: {
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
    // Only match `.cy.*` spec files (JS/TS/JSX/TSX). This avoids running `.spec.ts` files.
    specPattern: 'cypress/e2e/**/*.cy.{js,ts,jsx,tsx}',
  },
});
