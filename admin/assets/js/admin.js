class PandaConditions {
    constructor() {
        console.log("Something")
        this.init();
        this.bindEvents();
    }

    init() {

        this.conditionGroups = document.querySelector('.panda-display-conditions select');
        this.specificSearch = document.querySelector('.panda-specific-search');
        this.templateType = document.querySelector('select[name="template_type"]');
        this.shortcodeDependency = document.querySelector('.wrap-shortcode-template-dependency');
        this.shortcodeDisplay = document.querySelector('.panda-shortcode-display');
        this.templateDisplayCondition = document.querySelector('.panda-display-locations');
    }

    bindEvents() {
        // Existing condition groups event listener
        this.conditionGroups?.addEventListener('change', (e) => {
            if (e.target.value === 'specific') {
                this.specificSearch.style.display = 'block';
            }
        });

        // New template type change event listener
        this.templateType?.addEventListener('change', (e) => {
            this.toggleShortcodeDependency(e.target.value);
            this.toggleShortcodeAndTemplateDisplay(e.target.value);
        });

        // Initial state check on page load
        this.toggleShortcodeDependency(this.templateType?.value);
        this.toggleShortcodeAndTemplateDisplay(this.templateType?.value);

        this.initSpecificSearch();
        this.initShortcodeCopy();
    }

    toggleShortcodeDependency(templateType) {
        if (this.shortcodeDependency) {
            this.shortcodeDependency.style.display = templateType === 'shortcode' ? 'block' : 'none';
        }
    }

    toggleShortcodeAndTemplateDisplay(templateType) {
        if (this.shortcodeDisplay) {
            this.shortcodeDisplay.style.display = templateType === 'shortcode' ? 'block' : 'none';
        }

        if (this.templateDisplayCondition) {
            this.templateDisplayCondition.style.display = 
                (templateType === 'hook') ? 'block' : 'none';
        }
    }

    initSpecificSearch() {
        const searchInput = this.specificSearch?.querySelector('input');
        let searchTimer;

        searchInput?.addEventListener('input', (e) => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                this.performSearch(e.target.value);
            }, 500);
        });
    }

    initShortcodeCopy() {
        const shortcodeElement = document.querySelector('#shortcode');
        const copiedNotice = document.querySelector('.copied');

        if (shortcodeElement && copiedNotice) {
            shortcodeElement.addEventListener('click', () => {
                navigator.clipboard.writeText(shortcodeElement.textContent);
                copiedNotice.style.display = 'inline';
                setTimeout(() => {
                    copiedNotice.style.display = 'none';
                }, 2000);
            });
        }
    }
}

// Initialize the class when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new PandaConditions();
});