import '@js/bootstrap';

import App from '@js/App';
import FormBuilder from '@js/components/FormBuilder';
import SortableList from '@js/components/SortableList';
import Toast from '@js/components/Toast';

// Alpine components
import Popout from '@js/components/alpine/Popout';
import Tabs from '@js/components/alpine/Tabs';

window.addEventListener('DOMContentLoaded', () => {
    window.__APP__ = new App([
        {
            component: FormBuilder,
            name: 'formBuilder',
        },
        {
            component: SortableList,
            name: 'sortableList',
        },
        {
            component: Toast,
            name: 'toast',
        },
    ]);
});

document.addEventListener('alpine:init', () => {
    Alpine.data('popout', Popout);
    Alpine.data('tabs', Tabs);
    console.log('Alpine componentsinitialized 🚀');
});
