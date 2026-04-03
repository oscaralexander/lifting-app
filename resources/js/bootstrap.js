import axios from 'axios';
import '@vendor/wire-elements/modal/resources/js/modal';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Livewire interceptors for displaying toast-style notifications
 */
document.addEventListener('livewire:init', () => {
    axios.interceptors.response.use((response) => {
        if (response.data['livewire.dispatch'] ?? null) {
            Livewire.dispatch(response.data['livewire.dispatch']);
        }

        if (response.data.toast?.message ?? null) {
            document.dispatchEvent(
                new CustomEvent('toast', {
                    detail: {
                        message: response.data.notification.message,
                        type: response.data?.notification?.type ?? null,
                    },
                })
            );
        }

        return response;
    });
});
