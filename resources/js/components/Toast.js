export default class Toast {
    $el;

    constructor($el) {
        this.$el = $el;

        this.initListeners();
    }

    add(message, type = null) {
        const $toast = document.createElement('div');
        $toast.className = 'toast__message is-enter';
        $toast.innerHTML = `<div>${message}</div>`;
        $toast.addEventListener('animationend', this.onMessageAnimationEnd.bind(this), false);
        $toast.addEventListener('click', this.onMessageClick.bind(this), false);

        if (type) {
            $toast.classList.add(`toast__message--${type}`);
        }

        this.$el.appendChild($toast);
    }

    initListeners() {
        document.addEventListener(
            'toast',
            (e) => {
                this.add(e.detail.message, e.detail.type ?? null);
            },
            false
        );
    }

    onMessageAnimationEnd(e) {
        const $toast = e.currentTarget;

        if (e.animationName === 'toast-enter') {
            $toast.classList.remove('is-enter');
        }

        if (e.animationName === 'toast-timer') {
            $toast.classList.add('is-leave');
        }

        if (e.animationName === 'toast-leave') {
            $toast.remove();
        }
    }

    onMessageClick(e) {
        const $toast = e.currentTarget;
        $toast.classList.remove('is-enter');
        $toast.classList.add('is-leave');
    }
}
