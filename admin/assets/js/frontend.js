class PandaTemplates {
    constructor() {
        this.init();
    }

    init() {
        this.setupHeaderFooter();
    }

    setupHeaderFooter() {
        document.addEventListener('DOMContentLoaded', () => {
            this.adjustHeaderPosition();
        });
    }

    adjustHeaderPosition() {
        const header = document.querySelector('.panda-header');
        if (header && header.dataset.position === 'fixed') {
            document.body.style.paddingTop = header.offsetHeight + 'px';
        }
    }
}

new PandaTemplates();
