<div class="toast js-toast"></div>

@php
    $toastTypes = [
        'success' => App\Constants\SessionKey::TOAST_SUCCESS,
        'info' => App\Constants\SessionKey::TOAST_INFO,
        'error' => App\Constants\SessionKey::TOAST_ERROR,
    ];
@endphp

@foreach ($toastTypes as $type => $toast)
    @if (session()->has($toast))
        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                setTimeout(() => {
                    document.dispatchEvent(
                        new CustomEvent('toast', {
                            detail: {
                                message: '{{ session($toast) }}',
                                type: '{{ $type }}',
                            },
                        })
                    );
                }, 150);
            });
        </script>
    @endif
@endforeach