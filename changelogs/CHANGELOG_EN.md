# Initial release

## Version 1.0.0

### Features

- Product search by name or product number on the cart page and in the offcanvas cart.
- Search suggestions with product names, product numbers, quantity controls and direct add-to-cart actions.
- Add simple products from the offcanvas without leaving the current storefront page.
- Variant selection in a dialog using the Shopware buy widget.
- Independent configuration of cart-page and offcanvas search visibility.
- Availability controlled by a Shopware Rule Builder rule.
- Optional cart-icon buttons with accessible labels for simple products in search suggestions.
- English and German storefront snippets and configuration labels.
- Support for Shopware 6.7.x and PHP 8.2 through 8.5, subject to the installed Shopware version's requirements.

### Quality assurance and development

- PHP unit tests for availability checks, Twig integration, controller behavior, and service/routing configuration, with enforced 100% line coverage.
- Jest unit tests for plugin registration, event handling, modal cleanup and suggestion initialization, with enforced 100% statement, branch, function and line coverage.
- Playwright end-to-end tests based on the Shopware Acceptance Test Suite for search, cart actions, configuration, availability rules and variants.
- CI pipeline with PHP and JavaScript tests, coverage checks, TypeScript checks and Playwright runs against a freshly installed Shopware instance.
- Shopware extension validation against the lowest and highest supported versions, with coverage and test reports uploaded as CI artifacts.
- Automatic release tags based on the Composer version and Packagist update automation prepared for use after credentials are configured.
- Docker Compose environment for local development.

