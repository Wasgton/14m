/// <reference types="cypress" />
// ***********************************************
// This example commands.ts shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })
//
// declare global {
//   namespace Cypress {
//     interface Chainable {
//       login(email: string, password: string): Chainable<void>
//       drag(subject: string, options?: Partial<TypeOptions>): Chainable<Element>
//       dismiss(subject: string, options?: Partial<TypeOptions>): Chainable<Element>
//       visit(originalFn: CommandOriginalFn, url: string, options: Partial<VisitOptions>): Chainable<Element>
//     }
//   }
// }

// Add a simple placeholder login command for painel de controle tests.
// This should be replaced with a project-specific programmatic login (e.g., API auth, token set, or visiting the login page and submitting credentials).
Cypress.Commands.add('login', (email = 'super@ktorzem.com', password = 'password123') => {
  // Attempt a programmatic login; adjust endpoint and payload to match the app under test.
  // If your app uses session cookies or Laravel Sanctum, you may need to call the API that creates the session
  // and then visit the painel route to ensure the authenticated UI is loaded.
  cy.request({
    method: 'POST',
    url: '/login', // TODO: change to real login endpoint if different
    body: { email, password },
    failOnStatusCode: false,
  }).then((resp) => {
    // If login via API returns success, visit the painel root. Otherwise fallback to the login page.
    if (resp.status === 200 || resp.status === 204) {
      cy.visit('/painel');
    } else {
      // fallback: open login page and let manual UI login be implemented later
      cy.visit('/login');
    }
  });
});
