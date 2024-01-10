import {ref, onMounted, onUnmounted} from 'vue'
import type {Ref} from 'vue'

export function useSelection() {
    const selection: Ref<string[]> = ref([]);

    function toggleSelect(value: string) {
        const existsIndex = selection.value.findIndex((v) => v === value);
        switch (existsIndex) {
            case -1:
                selection.value = selection.value.concat([value])
                break;
            default:
                selection.value = selection.value.filter((v) => v != value)
                break
        }
    }

    return { selection, toggleSelect }
}
