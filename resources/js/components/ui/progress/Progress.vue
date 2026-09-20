<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { computed } from 'vue';
import { cn } from '@/lib/utils';

const props = withDefaults(
    defineProps<{
        modelValue?: number;
        class?: HTMLAttributes['class'];
        indicatorClass?: HTMLAttributes['class'];
    }>(),
    {
        modelValue: 0,
    },
);

const percent = computed(() =>
    Math.min(100, Math.max(0, props.modelValue ?? 0)),
);
</script>

<template>
    <div
        data-slot="progress"
        role="progressbar"
        :aria-valuenow="percent"
        aria-valuemin="0"
        aria-valuemax="100"
        :class="
            cn(
                'bg-primary/15 relative h-2 w-full overflow-hidden rounded-full',
                props.class,
            )
        "
    >
        <div
            data-slot="progress-indicator"
            :class="
                cn(
                    'bg-primary h-full rounded-full transition-all',
                    props.indicatorClass,
                )
            "
            :style="{ width: `${percent}%` }"
        />
    </div>
</template>
