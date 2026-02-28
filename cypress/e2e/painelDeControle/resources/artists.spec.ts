describe('Painel - Artists (resources)', () => {
  beforeEach(() => {
    cy.login();
    cy.visit('/painel/artists');
  });

  it('should list artists', () => {
    cy.get('[data-cy=artists-list]').should('exist');
  });

  it('should open create artist form', () => {
    cy.get('[data-cy=create-artist-button]').click();
    cy.get('[data-cy=artist-form]').should('be.visible');
  });

  afterEach(() => {});
});

