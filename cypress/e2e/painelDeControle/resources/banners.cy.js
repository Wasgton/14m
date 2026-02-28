describe('Painel - Banners (resources)', () => {
  beforeEach(() => {
    cy.login();
    cy.visit('/painel/banners');
  });

  it('should list banners', () => {
    cy.get('[data-cy=banners-list]').should('exist');
  });

  it('should open create banner modal', () => {
    cy.get('[data-cy=create-banner-button]').click();
    cy.get('[data-cy=banner-form]').should('be.visible');
  });

  afterEach(() => {});
});

