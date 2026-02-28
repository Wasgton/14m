# Cypress specs for Painel de Controle

This folder contains Cypress end-to-end spec skeletons for the "painel de controle" (admin control panel) of the 14M site.

Structure

- resources/: specs for content resources (events, banners, partners, artists)
- administrative/: specs for admin features (users, roles, permissions)

Usage

Run all painel specs:

```bash
npx cypress run --spec "cypress/e2e/painelDeControle/**/*.spec.ts"
```

Open Cypress interactive runner:

```bash
npx cypress open
```

Notes

- These are skeleton files meant to be extended with real selectors and API endpoints.
- They call `cy.login()` in `beforeEach`. There is a placeholder implementation at `cypress/support/commands.ts`. Replace it with a proper programmatic login matching your app (token, session, or visiting a login page).
- Add fixtures under `cypress/fixtures` if needed and replace example assertions with app-specific checks.

