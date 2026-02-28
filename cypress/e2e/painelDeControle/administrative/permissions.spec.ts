describe('Painel - Permissions (administrative)', () => {
  beforeEach(() => {
    cy.login();
    cy.visit('/painel/permissions');
  });

  it('should list permissions', () => {
    cy.get('[data-cy=permissions-list]').should('exist');
  });

  it('should open create permission form', () => {
    cy.get('[data-cy=create-permission-button]').click();
    cy.get('[data-cy=permission-form]').should('be.visible');
  });

  afterEach(() => {});
});

