export default class Popout {
    $el;
    $menu;
    position;
    offsetX;
    offsetY;
    clickOutside;

    constructor($el) {
        this.$el = $el;
        this.$menu = this.$el.querySelector('.js-popoutMenu');
        this.clickOutside = this.onClickOutside.bind(this);
        this.position = $el.dataset.popoutPosition ?? 'tc';

        // Use data attributes for offset, CSS variable values, or default to 0
        const computedStyle = getComputedStyle(this.$el);
        const offsetX = computedStyle.getPropertyValue('--offset-x');
        const offsetY = computedStyle.getPropertyValue('--offset-y');
        this.offsetX = $el.dataset.popoutOffsetX ?? (offsetX !== '' ? parseInt(offsetX) : 0);
        this.offsetY = $el.dataset.popoutOffsetY ?? (offsetY !== '' ? parseInt(offsetY) : 0);

        // Identify closest clipping container
        this.clippingContainer = null;
        let currentElement = this.$el;

        while (currentElement && currentElement !== document.body) {
            const overflow = window.getComputedStyle(currentElement).overflow;

            if (overflow === 'hidden' || overflow === 'auto' || overflow === 'scroll') {
                this.clippingContainer = currentElement;
                break;
            }

            currentElement = currentElement.parentElement;
        }

        this.initListeners();
    }

    align() {
        const { width: menuWidth, height: menuHeight } = this.$menu.getBoundingClientRect();
        const { top: y, left: x, width, height } = this.$el.getBoundingClientRect();
        const extendsX = menuWidth - width;

        this.$menu.classList.remove('is-bottom', 'is-center', 'is-left', 'is-top', 'is-right');

        // Set Y position
        const spaceTop = y - (menuHeight + this.offsetY);
        const spaceBottom = window.innerHeight - (y + height + (menuHeight + this.offsetY));

        if (this.position.startsWith('b') && spaceBottom > 0) {
            this.$menu.classList.add('is-bottom');
        } else if (this.position.startsWith('t') && spaceTop > 0) {
            this.$menu.classList.add('is-top');
        } else {
            this.$menu.classList.add(spaceBottom > spaceTop ? 'is-bottom' : 'is-top');
        }

        // Set X position
        const clippingContainerX = this.clippingContainer ? this.clippingContainer.getBoundingClientRect().left : 0;
        const isFitCenter =
            x + width + (extendsX * 0.5 + this.offsetX) <= window.innerWidth &&
            x - (extendsX * 0.5 + this.offsetX) > clippingContainerX;
        const spaceLeft = x - (extendsX + this.offsetX);
        const spaceRight = window.innerWidth - (x + menuWidth + this.offsetX);

        if (this.position.endsWith('l') && spaceLeft > clippingContainerX) {
            this.$menu.classList.add('is-left');
        } else if (this.position.endsWith('r') && spaceRight > 0) {
            this.$menu.classList.add('is-right');
        } else if (this.position.endsWith('c') && isFitCenter) {
            this.$menu.classList.add('is-center');
        } else {
            this.$menu.classList.add(spaceLeft > spaceRight ? 'is-left' : 'is-right');
        }
    }

    initListeners() {
        this.$el.addEventListener('click', this.toggleExpanded.bind(this));

        this.$menu.addEventListener('animationend', (e) => {
            if (e.animationName.startsWith('popout-out-')) {
                this.$el.classList.remove('is-hiding');
                this.isExpanded = false;
            }
        });
    }

    onClickOutside(e) {
        if (!this.$el.contains(e.target) && this.isExpanded) {
            document.removeEventListener('click', this.clickOutside);
            this.$el.classList.add('is-hiding');
        }
    }

    toggleExpanded() {
        if (this.isExpanded) {
            this.$el.classList.add('is-hiding');
        } else {
            document.addEventListener('click', this.clickOutside);
            this.isExpanded = true;
            this.align();
        }
    }

    get isExpanded() {
        return this.$el.getAttribute('aria-expanded') === 'true';
    }

    set isExpanded(value) {
        this.$el.setAttribute('aria-expanded', value);
    }
}
