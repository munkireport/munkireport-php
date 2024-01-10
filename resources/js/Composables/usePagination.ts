import {ref, onMounted, onUnmounted} from 'vue'
import type {Ref} from 'vue'

export function usePagination(pageCount, currPage = 1) {
    const page = ref(currPage)

    function next() {
        if (page.value == pageCount) return;
        page.value++
    }

    function prev() {
        if (page.value == 1) return;
        page.value--
    }

    return { page, next, prev }
}
