describe('Painel - Users (administrative)', () => {
  beforeEach(() => {
    cy.login();
    cy.visit('/painel/users');
  });

  it('should list users', () => {
    cy.get('[data-cy=users-list]').should('exist');
  });

  it('should open create user form', () => {
    cy.get('[data-cy=create-user-button]').click();
    cy.get('[data-cy=user-form]').should('be.visible');
  });

  afterEach(() => {});
});

