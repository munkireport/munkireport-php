import {writable} from 'svelte/store';

export interface ToastProps {
    header?: string;
    color?: string;
    body: string;

}

export function createToastStore() {
    const { subscribe, set, update } = writable([])

    return {
        subscribe,
        push: (toast: ToastProps) => update((n: ToastProps[]): ToastProps[] => n.concat(toast)),
        reset: () => set([]),
    }
}

export const toasts = createToastStore();
