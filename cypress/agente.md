# task

Create a specs in Cypress for painel de controle of the website of 14M.

# context

- The painel de controle is the section of the website where the producer can manage the content of the website, such as adding new events, photos, videos, partners, sponsors and artists.
- The painel de controle must be easy to use and intuitive, so that the producer can manage the content of the website without any technical knowledge.
- The painel de controle must be secure, so that the admins can access it and manage the content of the website without any risk of unauthorized access.
- The painel de controle have permissions, so that the superadmin can give different levels of access to different users, such as admins, editors and viewers.
- The painel de controle must have a dashboard, where the producer can see the statistics of the website, such as the number of visitors, the number of events, the number of photos and videos, the number of partners, sponsors and artists.
- The painel de controle must have a section to manage the events, where the admin can add new events, edit existing events and delete events.

# requirements

- It must use Cypress for testing
- It must be fast
- It must be accessible
- It must be maintainable
- It must be documented

# instructions

- Create a new folder in the cypress/e2e folder, with the name painelDeControle.
- Create a new folder in the painelDeControle folder with name resources, for each section of the painel de controle, such as events.spec.js, banners.spec.js, partners.spec.js, artists.spec.js.
- Create a new folder in the painelDeControle folder with name administrative, for each administration section of the painel de controle create news files, such as users.spec.js, roles.spec.js, permissions.spec.js
- Create on each file a structure for the tests, such as describe, it, beforeEach and afterEach. And each describe block should have a clear and concise name, that describes the functionality being tested.
- Create on each it block a clear and concise name, that describes the expected behavior of the functionality being tested.

 

# example

- /home/dekiwill/IdeaProjects/14m/cypress/e2e/2-advanced-examples
