<script setup>
import { computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close']);

watch(() => props.show, () => {
    if (props.show) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = null;
    }
});

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = null;
});

const maxWidthClass = computed(() => {
  return {
    'sm': 'w-auto',
    'md': 'w-auto',
    'lg': 'w-auto',
    'xl': 'w-auto',
    '2xl': 'w-auto',
  }[props.maxWidth];
});
</script>

<template>
  <teleport to="body">
    <transition leave-active-class="duration-200">
      <div v-show="show" class="position-fixed overflow-auto px-4 py-6 px-sm-0" style="z-index: 50; inset: 0">
        <div v-show="show" class="position-fixed" style="inset: 0" @click="close">
          <div class="position-absolute inset-0 opacity-75" style="inset: 0">
<!--              <button type="button" class="close" aria-label="Close">-->
<!--                <span aria-hidden="true">&times;</span>-->
<!--              </button>-->
          </div>
        </div>

        <div v-show="show" class="mb-6 bg-white rounded shadow-lg overflow-hidden mx-auto" style="max-width: 80%">
          <slot v-if="show" />
        </div>
      </div>
    </transition>
  </teleport>
</template>

