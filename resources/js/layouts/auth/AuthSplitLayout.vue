<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowLeft, Moon, Sun } from '@lucide/vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { Button } from '@/components/ui/button';
import { useAppearance } from '@/composables/useAppearance';
import { home } from '@/routes';

const { resolvedAppearance, updateAppearance } = useAppearance();

function toggleTheme() {
    updateAppearance(resolvedAppearance.value === 'dark' ? 'light' : 'dark');
}

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="relative flex min-h-svh flex-col lg:flex-row">
        <!-- Form column, mirrors TailAdmin's auth split layout -->
        <div class="flex w-full flex-1 flex-col px-6 py-10 lg:w-1/2 lg:px-12">
            <Link
                :href="home()"
                class="text-muted-foreground hover:text-foreground inline-flex w-fit items-center gap-1.5 text-sm transition-colors"
            >
                <ArrowLeft class="size-4" />
                Back to home
            </Link>

            <div class="flex flex-1 flex-col justify-center">
                <div class="mx-auto w-full max-w-sm">
                    <div class="mb-8 space-y-2">
                        <h1
                            v-if="title"
                            class="text-2xl font-semibold tracking-tight"
                        >
                            {{ title }}
                        </h1>
                        <p
                            v-if="description"
                            class="text-muted-foreground text-sm"
                        >
                            {{ description }}
                        </p>
                    </div>

                    <slot />
                </div>
            </div>
        </div>

        <!-- Brand column -->
        <div
            class="bg-sidebar-primary relative hidden w-1/2 items-center justify-center overflow-hidden lg:flex"
        >
            <PlaceholderPattern class="text-white/10" />
            <div
                class="relative z-10 flex flex-col items-center gap-4 px-10 text-center"
            >
                <div
                    class="flex size-16 items-center justify-center rounded-2xl bg-white/10 backdrop-blur"
                >
                    <AppLogoIcon class="size-9 fill-current text-white" />
                </div>
                <div class="text-sidebar-primary-foreground space-y-2">
                    <p class="text-2xl font-semibold">SmartRetail</p>
                    <p
                        class="text-sidebar-primary-foreground/70 max-w-xs text-sm"
                    >
                        Kelola penjualan, stok, dan pelanggan dalam satu admin
                        panel yang cepat dan rapi.
                    </p>
                </div>
            </div>
        </div>

        <!-- Theme toggle -->
        <div class="fixed right-6 bottom-6 z-50">
            <Button
                size="icon-lg"
                class="rounded-full shadow-lg"
                aria-label="Toggle theme"
                @click="toggleTheme"
            >
                <Sun v-if="resolvedAppearance === 'dark'" class="size-5" />
                <Moon v-else class="size-5" />
            </Button>
        </div>
    </div>
</template>
