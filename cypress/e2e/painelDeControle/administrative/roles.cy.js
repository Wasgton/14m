describe('Painel - Roles (administrative)', () => {
  beforeEach(() => {
    cy.login();
    cy.visit('/painel/roles');
  });

  it('should list roles', () => {
    cy.get('[data-cy=roles-list]').should('exist');
  });

  it('should open create role form', () => {
    cy.get('[data-cy=create-role-button]').click();
    cy.get('[data-cy=role-form]').should('be.visible');
  });

  afterEach(() => {});
});

