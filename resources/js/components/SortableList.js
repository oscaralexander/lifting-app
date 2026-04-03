import Sortable from 'sortablejs';

export default class SortableList {
    $el;
    sortable;

    constructor($el) {
        this.$el = $el;
        this.sortable = new Sortable(this.$el, {
            animation: 150,
            direction: 'vertical',
            draggable: '.sortable-item',
            handle: '.sortable-handle',
            onSort(e) {
                const $$item = e.target.querySelectorAll('[data-sortable-id]');
                const event = e.target.dataset.sortableListEvent ?? 'sorted';
                const positions = [...$$item].map(($item) => {
                    return $item.dataset.sortableId;
                });

                if (positions.length) {
                    e.target.dispatchEvent(
                        new CustomEvent(event, {
                            bubbles: true,
                            detail: {
                                positions,
                            },
                        })
                    );
                }
            },
        });
    }
}
