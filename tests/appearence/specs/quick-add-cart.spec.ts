import { test } from '@shopware-ag/acceptance-test-suite';
import { expect, type Locator } from '@playwright/test';

// ATS creates isolated sales channels/products and restores system configuration after each test.
test.beforeEach(async ({ TestDataService }) => {
    await TestDataService.setSystemConfig({
        'QuickAddCart.config.showAdvancedSearchInCart': true,
        'QuickAddCart.config.showAdvancedSearchInOffCanvas': true,
        'QuickAddCart.config.showOnlyIcon': false,
        'QuickAddCart.config.availabilityRule': null,
    });
});

async function search(widget: Locator, term: string) {
    await widget.locator('input[type="search"]').fill(term);
    await expect(widget.locator('.search-suggest-container')).toBeVisible();
}

for (const searchBy of ['name', 'productNumber'] as const) {
    test(`adds a product by ${searchBy} from the cart`, async ({ StorefrontPage, TestDataService }) => {
        const product = await TestDataService.createBasicProduct();
        await StorefrontPage.goto('./checkout/cart');
        const widget = StorefrontPage.locator('#cr-quick-add-search-checkoutCart');
        await search(widget, product[searchBy]!);
        const result = widget.locator('.search-suggest-product').filter({ hasText: product.productNumber });
        await expect(result).toHaveCount(1);
        await result.locator('button.btn-buy').click();
        await expect(StorefrontPage.locator('.line-item-product').filter({ hasText: product.name! })).toBeVisible();
    });
}

test('shows an empty result and can search again', async ({ StorefrontPage, TestDataService }) => {
    const product = await TestDataService.createBasicProduct();
    await StorefrontPage.goto('./checkout/cart');
    const widget = StorefrontPage.locator('#cr-quick-add-search-checkoutCart');
    await search(widget, 'quickadd-does-not-exist-92837465');
    await expect(widget.locator('.search-suggest-no-result')).toBeVisible();
    await search(widget, product.productNumber);
    await expect(widget.locator('.search-suggest-product')).toContainText(product.productNumber);
});

for (const cartEnabled of [true, false]) {
    test(`cart and offcanvas settings work independently (cart=${cartEnabled})`, async ({ StorefrontPage, TestDataService }) => {
        await TestDataService.setSystemConfig({
            'QuickAddCart.config.showAdvancedSearchInCart': cartEnabled,
            'QuickAddCart.config.showAdvancedSearchInOffCanvas': !cartEnabled,
        });
        await StorefrontPage.goto('./checkout/cart');
        await expect(StorefrontPage.locator('#cr-quick-add-search-checkoutCart')).toHaveCount(cartEnabled ? 1 : 0);
        await StorefrontPage.locator('.header-cart-btn').click();
        await expect(StorefrontPage.locator('.offcanvas-cart')).toBeVisible();
        await expect(StorefrontPage.locator('#cr-quick-add-search-offCanvas')).toHaveCount(cartEnabled ? 0 : 1);
    });
}

test('adds a product from offcanvas without leaving the page', async ({ StorefrontPage, TestDataService }) => {
    const product = await TestDataService.createBasicProduct();
    await StorefrontPage.goto('./');
    const originalUrl = StorefrontPage.url();
    await StorefrontPage.locator('.header-cart-btn').click();
    const widget = StorefrontPage.locator('#cr-quick-add-search-offCanvas');
    await search(widget, product.productNumber);
    await widget.locator('button.btn-buy').click();
    await expect(StorefrontPage.locator('.offcanvas .line-item-product').filter({ hasText: product.name! })).toBeVisible();
    await expect(StorefrontPage).toHaveURL(originalUrl);
});

test('icon-only buttons retain an accessible name', async ({ StorefrontPage, TestDataService }) => {
    await TestDataService.setSystemConfig({ 'QuickAddCart.config.showOnlyIcon': true });
    const product = await TestDataService.createBasicProduct();
    await StorefrontPage.goto('./checkout/cart');
    const widget = StorefrontPage.locator('#cr-quick-add-search-checkoutCart');
    await search(widget, product.productNumber);
    const button = widget.locator('button.btn-buy');
    await expect(button).toHaveAccessibleName(/.+/);
    await expect(button.locator('.icon')).toBeVisible();
    await expect(button).toHaveText('');
});

for (const matches of [true, false]) {
    test(`availability rule controls both widgets (matches=${matches})`, async ({ StorefrontPage, TestDataService }) => {
        const rule = await TestDataService.createBasicRule({}, 'cartCartAmount', '>=', matches ? 0 : 1000000);
        await TestDataService.setSystemConfig({ 'QuickAddCart.config.availabilityRule': rule.id });
        await StorefrontPage.goto('./checkout/cart');
        await expect(StorefrontPage.locator('#cr-quick-add-search-checkoutCart')).toHaveCount(matches ? 1 : 0);
        await StorefrontPage.locator('.header-cart-btn').click();
        await expect(StorefrontPage.locator('.offcanvas-cart')).toBeVisible();
        await expect(StorefrontPage.locator('#cr-quick-add-search-offCanvas')).toHaveCount(matches ? 1 : 0);
    });
}

test('opens a variant buy box and adds the selected variant', async ({ StorefrontPage, TestDataService }) => {
    const parent = await TestDataService.createBasicProduct();
    const group = await TestDataService.createTextPropertyGroup();
    const variants = await TestDataService.createVariantProducts(parent, [group]);
    await StorefrontPage.goto('./checkout/cart');
    const widget = StorefrontPage.locator('#cr-quick-add-search-checkoutCart');
    await search(widget, variants[0].productNumber);
    await widget.locator('[data-ajax-modal]').first().click();
    const modal = StorefrontPage.locator('.modal.show');
    await expect(modal.locator('[data-buy-box]')).toBeVisible();
    await modal.locator('button.btn-buy').click();
    await StorefrontPage.goto('./checkout/cart');
    await expect(StorefrontPage.locator('.line-item-product')).toHaveCount(1);
});
