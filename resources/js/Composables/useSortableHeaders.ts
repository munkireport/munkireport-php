import {ref, reactive, onMounted, onUnmounted} from 'vue'
import type {Ref} from 'vue'

export interface Sortable {
    key: string;
    sortable?: boolean;
    sort?: "ASC" | "DESC" | null;
}

export function useSortableHeaders(columns: Sortable[], multiple: boolean) {
    const headers = ref(columns)

    function toggleSort(key: string) {
        console.log(`Toggle sort ${key}`)

        const headerIndex = headers.value.findIndex((value) => value.key === key)
        switch (headers.value[headerIndex].sort) {
            case 'ASC':
                headers.value[headerIndex].sort = 'DESC'
                break
            case 'DESC':
                headers.value[headerIndex].sort = null
                break
            default:
                headers.value[headerIndex].sort = 'ASC'
        }

        console.log(headers.value[headerIndex].sort);
    }

    return { headers, toggleSort }
}
