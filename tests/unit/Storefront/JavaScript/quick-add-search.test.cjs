const Base = require('./search-widget.stub.cjs');
const QuickAddSearch = require('../../../../src/Resources/app/storefront/src/plugin/quick-add-search.plugin.js').default;

function createPlugin() {
    const plugin = new QuickAddSearch();
    plugin.el = document.createElement('div');
    plugin.$emitter = { subscribe: jest.fn(), unsubscribe: jest.fn() };
    return plugin;
}

beforeEach(() => { document.body.innerHTML = ''; jest.restoreAllMocks(); });

test('registers events and removes the same bound click listener on destroy', () => {
    const register = jest.spyOn(Base.prototype, '_registerEvents');
    const destroy = jest.spyOn(Base.prototype, 'destroy');
    const plugin = createPlugin();
    const add = jest.spyOn(plugin.el, 'addEventListener');
    const remove = jest.spyOn(plugin.el, 'removeEventListener');
    plugin._registerEvents();
    expect(register).toHaveBeenCalledTimes(1);
    expect(plugin.$emitter.subscribe).toHaveBeenCalledWith('afterSuggest', expect.any(Function));
    expect(add).toHaveBeenCalledWith('click', plugin.removeEmptyModalWrapper, true);
    plugin.destroy();
    expect(plugin.$emitter.unsubscribe).toHaveBeenCalledWith('afterSuggest');
    expect(remove).toHaveBeenCalledWith('click', plugin.removeEmptyModalWrapper, true);
    expect(destroy).toHaveBeenCalledTimes(1);
});

test.each([
    ['unrelated click', '<div class="js-pseudo-modal" inert></div><button></button>', 'button', 1],
    ['empty inert wrapper', '<div class="js-pseudo-modal" inert></div><a data-ajax-modal data-url="/modal"><span></span></a>', 'span', 0],
    ['populated wrapper', '<div class="js-pseudo-modal" inert><p>keep</p></div><a data-ajax-modal data-url="/modal"></a>', 'a', 1],
    ['active wrapper', '<div class="js-pseudo-modal"></div><a data-ajax-modal data-url="/modal"></a>', 'a', 1],
    ['no wrapper', '<a data-ajax-modal data-url="/modal"></a>', 'a', 0],
])('%s', (_, html, target, count) => {
    document.body.innerHTML = html;
    createPlugin().removeEmptyModalWrapper({ target: document.querySelector(target) });
    expect(document.querySelectorAll('.js-pseudo-modal')).toHaveLength(count);
});

test('does not initialize plugins without suggestions', () => {
    window.PluginManager = { initializePlugins: jest.fn() };
    expect(createPlugin().initSuggestions()).toBeUndefined();
    expect(window.PluginManager.initializePlugins).not.toHaveBeenCalled();
});

test('initializes only newly inserted suggestions when supported', () => {
    const plugin = createPlugin();
    plugin.el.innerHTML = '<div class="search-suggest"></div>';
    const result = Promise.resolve();
    window.PluginManager = { initializePluginsInParentElement: jest.fn().mockReturnValue(result) };
    expect(plugin.initSuggestions()).toBe(result);
    expect(window.PluginManager.initializePluginsInParentElement).toHaveBeenCalledWith(plugin.el.firstChild);
});

test('falls back to the Shopware global initializer', () => {
    const plugin = createPlugin();
    plugin.el.innerHTML = '<div class="search-suggest"></div>';
    const result = Promise.resolve();
    window.PluginManager = { initializePlugins: jest.fn().mockReturnValue(result) };
    expect(plugin.initSuggestions()).toBe(result);
    expect(window.PluginManager.initializePlugins).toHaveBeenCalledTimes(1);
});

test('registers QuickAddSearch on the plugin selector', () => {
    window.PluginManager = { register: jest.fn() };
    require('../../../../src/Resources/app/storefront/src/main.js');
    expect(window.PluginManager.register).toHaveBeenCalledWith('QuickAddSearch', QuickAddSearch, '[data-quick-add-search]');
});
