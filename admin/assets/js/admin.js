class PandaConditions {
    constructor() {
        this.init();
        this.bindEvents();
    }

    init() {
        this.conditionGroups = document.querySelector('.panda-condition-groups');
        this.specificSearch = document.querySelector('.panda-specific-search');
    }

    bindEvents() {
        this.conditionGroups.addEventListener('change', (e) => {
            if (e.target.value === 'specific') {
                this.specificSearch.style.display = 'block';
            }
        });

        this.initSpecificSearch();
    }

    initSpecificSearch() {
        const searchInput = this.specificSearch.querySelector('input');
        let searchTimer;

        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                this.performSearch(e.target.value);
            }, 500);
        });
    }
}
