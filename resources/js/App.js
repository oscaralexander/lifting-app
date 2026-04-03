export default class App {
    bindings = [];

    constructor(bindings = []) {
        this.bindings = bindings;
        this.init();
    }

    bind($root, binding) {
        const selector = binding.selector ?? `.js-${binding.name}`;

        $root.querySelectorAll(selector).forEach(($el) => {
            $el.__APP__ ||= {};

            if ($el.__APP__[binding.name] === undefined) {
                $el.__APP__[binding.name] = new binding.component($el, binding.options ?? {});
            }
        });
    }

    bindAll($root) {
        this.bindings.forEach((binding) => {
            this.bind($root, binding);
        });
    }

    init() {
        const mutationObserver = new MutationObserver((mutations) => {
            let targets = [];

            mutations.forEach((mutationRecord) => {
                if (mutationRecord.target instanceof HTMLElement) {
                    if (!targets.includes(mutationRecord.target)) {
                        this.bindAll(mutationRecord.target);
                        targets.push(mutationRecord.target);
                    }
                }
            });

            document.body.dispatchEvent(
                new CustomEvent('domchanged', {
                    detail: {
                        targets,
                    },
                })
            );
        });

        mutationObserver.observe(document.documentElement, {
            childList: true,
            subtree: true,
        });

        this.bindAll(document);
    }
}
