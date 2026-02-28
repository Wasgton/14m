describe('Painel - Partners (resources)', () => {
  beforeEach(() => {
    cy.login();
    cy.visit('/painel/partners');
  });

  it('should list partners', () => {
    cy.get('[data-cy=partners-list]').should('exist');
  });

  it('should open create partner form', () => {
    cy.get('[data-cy=create-partner-button]').click();
    cy.get('[data-cy=partner-form]').should('be.visible');
  });

  afterEach(() => {});
});

