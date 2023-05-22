<script lang="ts">
    import { Toast, ToastBody, ToastHeader } from 'sveltestrap';
    const colors = [
        'primary',
        'secondary',
        'success',
        'danger',
        'warning',
        'info',
        'light',
        'dark'
    ];
    import { toasts } from "../stores";
    let t = null;

    let isOpen = true;
    function dismiss() {
        toasts.reset()
    }
</script>

<div>
    {#each $toasts as toast}
        <Toast class="mr-1" color={toast.color}>
            <ToastHeader toggle={dismiss}>{#if toast.header}{toast.header}{:else}Notification{/if}</ToastHeader>
            <ToastBody>{toast.body}</ToastBody>
        </Toast>
    {/each}
</div>
