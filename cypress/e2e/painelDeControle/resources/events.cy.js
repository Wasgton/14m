describe('Painel - Events (resources)', () => {
  beforeEach(() => {
    // Use the shared login command; ensure it's implemented in cypress/support/commands.ts
    cy.login();
    // Visit the events management page in painel
    cy.visit('/painel/events');
  });

  it('should list events', () => {
    // TODO: replace selector with real table/list selector
    cy.get('[data-cy=events-list]').should('exist');
  });

  it('should show create event form', () => {
    cy.get('[data-cy=create-event-button]').click();
    cy.get('[data-cy=event-form]').should('be.visible');
  });

  afterEach(() => {
    // cleanup if required
  });
});

