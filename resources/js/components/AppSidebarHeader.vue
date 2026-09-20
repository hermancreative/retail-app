<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Bell, Building2, ChevronDown, Maximize } from '@lucide/vue';
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarTrigger } from '@/components/ui/sidebar';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { useInitials } from '@/composables/useInitials';

const page = usePage();
const user = computed(() => page.props.auth.user);
const { getInitials } = useInitials();
const showAvatar = computed(
    () => user.value.avatar && user.value.avatar !== '',
);

function toggleFullscreen() {
    if (document.fullscreenElement) {
        void document.exitFullscreen();
    } else {
        void document.documentElement.requestFullscreen();
    }
}
</script>

<template>
    <header
        class="sticky top-0 z-20 flex h-16 shrink-0 items-center justify-between gap-2 border-b border-slate-800 bg-slate-900 px-6 text-slate-100 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex items-center gap-3">
            <SidebarTrigger
                class="-ml-1 text-slate-300 hover:bg-white/10 hover:text-white"
            />
            <div
                class="hidden items-center gap-1.5 text-sm font-medium text-slate-300 sm:flex"
            >
                <Building2 class="size-4" />
                <span>PT. Manjadda Wajada</span>
            </div>
        </div>

        <div class="flex items-center gap-1">
            <Button
                size="icon-sm"
                variant="ghost"
                class="text-slate-300 hover:bg-white/10 hover:text-white"
                aria-label="Toggle fullscreen"
                @click="toggleFullscreen"
            >
                <Maximize class="size-4" />
            </Button>
            <Button
                size="icon-sm"
                variant="ghost"
                class="relative text-slate-300 hover:bg-white/10 hover:text-white"
                aria-label="Notifications"
            >
                <Bell class="size-4" />
                <span
                    class="bg-destructive absolute top-1 right-1 size-2 rounded-full ring-2 ring-slate-900"
                />
            </Button>

            <!-- User box, mirrors UBold's topbar user menu -->
            <DropdownMenu>
                <DropdownMenuTrigger
                    class="ml-1 flex items-center gap-2 rounded-md py-1 pr-2 pl-1 outline-none hover:bg-white/10"
                >
                    <Avatar class="size-8">
                        <AvatarImage
                            v-if="showAvatar"
                            :src="user.avatar!"
                            :alt="user.name"
                        />
                        <AvatarFallback
                            class="bg-slate-700 text-xs font-medium text-slate-100"
                        >
                            {{ getInitials(user.name) }}
                        </AvatarFallback>
                    </Avatar>
                    <span class="hidden text-left leading-tight md:block">
                        <span class="block text-sm font-medium text-white">{{
                            user.name
                        }}</span>
                        <span class="block text-xs text-slate-400"
                            >Administrator</span
                        >
                    </span>
                    <ChevronDown
                        class="hidden size-4 text-slate-400 md:block"
                    />
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" class="w-56">
                    <UserMenuContent :user="user" />
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
    </header>
</template>
