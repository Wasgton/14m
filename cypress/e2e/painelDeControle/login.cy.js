describe('Painel - Login', () => {
  beforeEach(() => {
    // Ensure we start from the login page
    cy.visit('/login');
  });

  it('should login successfully and redirect to painel or set token', () => {
    // Intercept the login API and respond with a fake token
    cy.intercept('POST', '/login', {
      statusCode: 200,
      body: { token: 'fake-token' },
    }).as('loginRequest');

    // Fill the form (replace selectors with your app's real selectors)
    cy.get('[data-cy=email]').clear().type('admin@example.com');
    cy.get('[data-cy=password]').clear().type('password');
    cy.get('[data-cy=login-button]').click();

    // Wait for the intercepted request and then assert a visible sign of success.
    cy.wait('@loginRequest');

    // Two common signs of success: app stores token in localStorage OR navigates to /painel.
    // We assert at least one of them holds.
    cy.window().then((win) => {
      const token = win.localStorage.getItem('token');
      if (token) {
        expect(token).to.equal('fake-token');
      } else {
        // if token not present, check URL includes /painel
        cy.url().should('include', '/painel');
      }
    });
  });

  it('should dismiss login (close/cancel) and not be authenticated', () => {
    // Try to click a dismiss/cancel button if it exists; otherwise navigate back.
    cy.document().then((doc) => {
      const btn = doc.querySelector('[data-cy=login-cancel], [data-cy=close-login], .close-login, .btn-cancel');
      if (btn) {
        cy.wrap(btn).click();
        cy.get('[data-cy=login-form]', { timeout: 1000 }).should('not.exist');
      } else {
        // fallback: go back to previous page
        cy.go('back');
        // ensure the login form is not present
        cy.get('[data-cy=login-form]', { timeout: 1000 }).should('not.exist');
      }
    });

    // Ensure there's no auth token stored.
    cy.window().then((win) => {
      const token = win.localStorage.getItem('token');
      expect(token, 'no auth token after dismiss').to.be.oneOf([null, undefined]);
    });
  });

  afterEach(() => {
    // cleanup: remove fake token if any
    cy.window().then((win) => {
      try {
        win.localStorage.removeItem('token');
      } catch (e) {
        // ignore
      }
    });
  });
});

