import SearchWidgetPlugin from 'src/plugin/header/search-widget.plugin';

export default class QuickAddSearchPlugin extends SearchWidgetPlugin {
    _registerEvents() {
        super._registerEvents();
        this.$emitter.subscribe('afterSuggest', this.initSuggestions.bind(this));
        this.removeEmptyModalWrapper = this.removeEmptyModalWrapper.bind(this);
        this.el.addEventListener('click', this.removeEmptyModalWrapper, true);
    }

    destroy() {
        this.$emitter.unsubscribe('afterSuggest');
        this.el.removeEventListener('click', this.removeEmptyModalWrapper, true);
        super.destroy();
    }

    removeEmptyModalWrapper(event) {
        if (!event.target.closest('[data-ajax-modal][data-url]')) {
            return;
        }

        // Offcanvas marks the old wrapper inert along with the rest of the page.
        const wrapper = document.querySelector('.js-pseudo-modal[inert]');
        if (wrapper?.childElementCount === 0) {
            wrapper.remove();
        }
    }

    initSuggestions() {
        const suggestions = this.el.querySelector('.search-suggest');
        if (!suggestions) {
            return;
        }

        const pluginManager = window.PluginManager;
        if (typeof pluginManager.initializePluginsInParentElement === 'function') {
            return pluginManager.initializePluginsInParentElement(suggestions);
        }

        return pluginManager.initializePlugins();
    }
}
