import Sortable from 'sortablejs';

export default class FormBuilder {
    $draggingElement;
    $el;
    $fields;
    $form;
    $$sortable;
    sortables;

    constructor($el) {
        this.$el = $el;

        this.$draggingElement = null;
        this.$fields = this.$el.querySelector('.js-formBuilderFields');
        this.$form = this.$el.querySelector('.js-formBuilderForm');
        this.sortables = [];

        this.initListeners();
        this.init();
    }

    createSortables() {
        const $$sortable = this.$el.querySelectorAll('.js-formBuilderSortable');

        $$sortable.forEach(($sortable, i) => {
            this.sortables.push(
                new Sortable($sortable, {
                    animation: 150,
                    fallbackOnBody: true,
                    group: {
                        name: 'form',
                        pull: true,
                        put: (to, from) => {
                            if (this.$draggingElement.dataset.fieldGroupId) {
                                return to.el.closest('[data-field-group-id]') === null;
                            }

                            return true;
                        },
                    },
                    onAdd: this.update.bind(this),
                    onStart: (e) => {
                        this.$draggingElement = e.item;
                    },
                    onUpdate: this.update.bind(this),
                    swapThreshold: 0.65,
                })
            );
        });

        this.sortables.push(
            new Sortable(this.$fields, {
                animation: 150,
                group: {
                    name: 'form',
                    put: false,
                    pull: true,
                },
                onStart: (e) => {
                    this.$draggingElement = e.item;
                },
                sort: false,
            })
        );
    }

    destroy() {
        this.sortables.forEach((sortable) => {
            if (sortable && typeof sortable.destroy === 'function') {
                sortable.destroy();
            }
        });

        this.sortables = [];
    }

    init() {
        this.destroy();
        this.createSortables();
    }

    initListeners() {
        Livewire.hook('morphed', ({ el, component }) => {
            console.log('Morphed...');
            this.init();
        });
    }

    update() {
        const $$item = this.$form.querySelectorAll('[data-sortable-item]');
        let i = 0;
        const positions = [];

        for (const $item of $$item) {
            const $fieldGroup = $item.dataset.fieldGroupId ? $item : $item.closest('[data-field-group-id]');
            const fieldId = $item.dataset.fieldId ? +$item.dataset.fieldId : null;
            const formCommentId = $item.dataset.formCommentId ? +$item.dataset.formCommentId : null;
            const pivotId = $item.dataset.pivotId ? +$item.dataset.pivotId : null;

            if ($fieldGroup) {
                $item.dataset.fieldGroupId = $fieldGroup.dataset.fieldGroupId;
                const fieldGroupId = $fieldGroup.dataset.fieldGroupId ? +$fieldGroup.dataset.fieldGroupId : null;
                const fieldGroup = positions.find((item) => item.fieldGroupId === fieldGroupId);

                if (fieldGroup) {
                    // Add item to existing group object
                    fieldGroup.items.push(fieldId ? { fieldId, pivotId } : { formCommentId });
                } else {
                    // Create new group object and add item
                    positions.push({ fieldGroupId, items: [] });
                }
            } else {
                if (formCommentId) {
                    positions.push({ formCommentId });
                } else {
                    positions.push({ fieldId, pivotId });
                }
            }

            i++;
        }

        if (positions.length) {
            console.log(positions);

            this.$form.dispatchEvent(
                new CustomEvent('form-updated', {
                    bubbles: true,
                    detail: {
                        positions,
                    },
                })
            );
        }
    }
}
