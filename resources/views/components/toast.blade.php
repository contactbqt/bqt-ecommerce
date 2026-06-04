<div
    x-data="{
        notifications: [],
        add(e) {
            let detail = e.detail;
            // Support both direct properties and Flux nested structure
            let text = detail.text;
            let heading = detail.heading;
            let variant = detail.variant;
            let duration = detail.duration || 5000;

            if (detail.slots) {
                text = detail.slots.text || text;
                heading = detail.slots.heading || heading;
            }
            if (detail.dataset) {
                variant = detail.dataset.variant || variant;
            }

            this.notifications.push({
                id: Date.now(),
                text: text,
                variant: variant || 'info',
                heading: heading,
                duration: duration
            })
        },
        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id)
        }
    }"
    @toast-show.window="add($event)"
    x-init="
        @if (session()->has('message'))
            add({ detail: { text: '{{ session('message') }}', variant: 'success' } });
        @endif
        @if (session()->has('success'))
            add({ detail: { text: '{{ session('success') }}', variant: 'success' } });
        @endif
        @if (session()->has('error'))
            add({ detail: { text: '{{ session('error') }}', variant: 'danger' } });
        @endif
        @if (session()->has('error_message'))
            add({ detail: { text: '{{ session('error_message') }}', variant: 'danger' } });
        @endif
        @if (session()->has('warning'))
            add({ detail: { text: '{{ session('warning') }}', variant: 'warning' } });
        @endif
    "
    class="fixed top-0 right-0 z-[100] p-4 space-y-4 w-full pointer-events-none flex flex-col items-end"
>
    <template x-for="notification in notifications" :key="notification.id">
        <div
            x-data="{ show: false, timeout: null }"
            x-init="
                $nextTick(() => show = true);
                timeout = setTimeout(() => { show = false; setTimeout(() => remove(notification.id), 300) }, 5000);
            "
            x-show="show"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto w-auto max-w-xs rounded-lg border flex items-center justify-between p-3 mb-2 shadow-sm"
            :class="{
                'bg-green-100 border-green-300 text-green-800': notification.variant === 'success',
                'bg-red-100 border-red-300 text-red-800': notification.variant === 'error' || notification.variant === 'danger',
                'bg-yellow-100 border-yellow-300 text-yellow-800': notification.variant === 'warning',
                'bg-blue-100 border-blue-300 text-blue-800': !['success', 'error', 'danger', 'warning'].includes(notification.variant)
            }"
        >
            <div class="flex items-center gap-3">
                <!-- Success Icon -->
                <template x-if="notification.variant === 'success'">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </template>
                <!-- Error Icon -->
                <template x-if="notification.variant === 'error' || notification.variant === 'danger'">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </template>
                <!-- Warning Icon -->
                <template x-if="notification.variant === 'warning'">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </template>
                <!-- Info Icon -->
                <template x-if="!['success', 'error', 'danger', 'warning'].includes(notification.variant)">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>

                <div class="flex flex-col">
                    <template x-if="notification.heading">
                        <span x-text="notification.heading" class="font-bold text-sm"></span>
                    </template>
                    <span x-text="notification.text" class="text-sm"></span>
                </div>
            </div>

            <button
                @click="show = false; setTimeout(() => remove(notification.id), 300)"
                class="ml-4 hover:opacity-75 focus:outline-none"
                :class="{
                    'text-green-600 hover:text-green-900': notification.variant === 'success',
                    'text-red-600 hover:text-red-900': notification.variant === 'error' || notification.variant === 'danger',
                    'text-yellow-600 hover:text-yellow-900': notification.variant === 'warning',
                    'text-blue-600 hover:text-blue-900': !['success', 'error', 'danger', 'warning'].includes(notification.variant)
                }"
            >
                &times;
            </button>
        </div>
    </template>
</div>
