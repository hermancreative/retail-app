<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    ArrowDown,
    ArrowUp,
    Globe,
    MoreVertical,
    Store,
    Users,
    Wallet,
    ShoppingBasket,
} from '@lucide/vue';
import PageHeader from '@/components/PageHeader.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { dashboard } from '@/routes';

const breadcrumbs = [
    { title: 'Ecommerce', href: dashboard() },
    { title: 'Dashboard', href: dashboard() },
];

// KPI widgets — mirrors UBold's widget-rounded-circle cards
// (icon in a soft-colored circle on the left, value + label on the right)
const kpis = [
    {
        title: 'Total Revenue',
        value: '$58,947',
        icon: Wallet,
        accent: 'bg-violet-500/10 text-violet-600 dark:text-violet-400',
    },
    {
        title: 'Orders',
        value: '1,845',
        icon: ShoppingBasket,
        accent: 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
    },
    {
        title: 'Stores',
        value: '825',
        icon: Store,
        accent: 'bg-sky-500/10 text-sky-600 dark:text-sky-400',
    },
    {
        title: 'Sellers',
        value: '2,430',
        icon: Users,
        accent: 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
    },
];

// Sales Analytics — current week / previous week / targets stat row
const weekStats = [
    { label: 'Current Week', value: '$58,254', dot: 'bg-violet-500' },
    { label: 'Previous Week', value: '$69,524', dot: 'bg-emerald-500' },
    { label: 'Targets', value: '$95,025', dot: 'bg-emerald-500' },
];

const chartBars = [42, 58, 35, 72, 48, 65, 80, 55, 70, 62, 78, 90];
const chartLabels = [
    'Jan',
    'Feb',
    'Mar',
    'Apr',
    'May',
    'Jun',
    'Jul',
    'Aug',
    'Sep',
    'Oct',
    'Nov',
    'Dec',
];

const periods = ['Today', 'Weekly', 'Monthly'] as const;
const activePeriod = 'Monthly';

// Total Revenue widget stats
const revenueBreakdown = [
    { label: 'Target', value: '$7.8k', trend: 'down' as const },
    { label: 'Last week', value: '$1.4k', trend: 'up' as const },
    { label: 'Last Month', value: '$15k', trend: 'down' as const },
];

// Transaction History
const transactions = [
    {
        name: 'Imelda J. Stanberry',
        card: '**** 3256',
        date: '27.03.2026',
        amount: '$345.98',
        status: 'Failed' as const,
    },
    {
        name: 'Francisca S. Lobb',
        card: '**** 8451',
        date: '28.03.2026',
        amount: '$1,250',
        status: 'Paid' as const,
    },
    {
        name: 'James A. Wert',
        card: '**** 2258',
        date: '28.03.2026',
        amount: '$145',
        status: 'Paid' as const,
    },
    {
        name: 'Dolores J. Pooley',
        card: '**** 6950',
        date: '29.03.2026',
        amount: '$2,005.89',
        status: 'Failed' as const,
    },
    {
        name: 'Karen I. McCluskey',
        card: '**** 0021',
        date: '31.03.2026',
        amount: '$24.95',
        status: 'Paid' as const,
    },
];

// Recent Products
const recentProducts = [
    {
        name: 'Adirondack Chair',
        category: 'Dining Chairs',
        date: '27.03.2026',
        amount: '$345.98',
        status: 'Active' as const,
    },
    {
        name: 'Biblio Plastic Armchair',
        category: 'Baby Chairs',
        date: '28.03.2026',
        amount: '$1,250',
        status: 'Active' as const,
    },
    {
        name: 'Amazing Modern Chair',
        category: 'Plastic Armchair',
        date: '28.03.2026',
        amount: '$145',
        status: 'Deactive' as const,
    },
    {
        name: 'Designer Awesome Chair',
        category: 'Wing Chairs',
        date: '29.03.2026',
        amount: '$2,005.89',
        status: 'Active' as const,
    },
    {
        name: 'The butterfly chair',
        category: 'Plastic Armchair',
        date: '31.03.2026',
        amount: '$24.95',
        status: 'Active' as const,
    },
];

function initials(name: string) {
    return name
        .split(' ')
        .map((part) => part[0])
        .slice(0, 2)
        .join('')
        .toUpperCase();
}
</script>

<template>
    <Head title="Ecommerce Dashboard" />

    <div class="flex flex-1 flex-col gap-4 p-4 md:gap-6 md:p-6">
        <PageHeader title="Dashboard" :breadcrumbs="breadcrumbs" />

        <!-- KPI widgets -->
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <Card v-for="kpi in kpis" :key="kpi.title" class="py-5 shadow-sm">
                <CardContent class="flex items-center gap-4">
                    <div
                        class="flex size-14 shrink-0 items-center justify-center rounded-lg"
                        :class="kpi.accent"
                    >
                        <component :is="kpi.icon" class="size-6" />
                    </div>
                    <div class="min-w-0 text-right">
                        <p
                            class="truncate text-2xl font-semibold tracking-tight"
                        >
                            {{ kpi.value }}
                        </p>
                        <p class="text-muted-foreground truncate text-sm">
                            {{ kpi.title }}
                        </p>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Sales Analytics + Total Revenue -->
        <div class="grid gap-4 xl:grid-cols-3">
            <Card class="gap-4 py-5 shadow-sm xl:col-span-2">
                <CardContent class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="font-semibold">Sales Analytics</h4>
                        <div class="flex gap-1">
                            <Button
                                v-for="period in periods"
                                :key="period"
                                size="sm"
                                :variant="
                                    period === activePeriod
                                        ? 'secondary'
                                        : 'ghost'
                                "
                            >
                                {{ period }}
                            </Button>
                        </div>
                    </div>

                    <div
                        class="grid gap-4 border-b pb-5 text-center sm:grid-cols-3"
                    >
                        <div v-for="stat in weekStats" :key="stat.label">
                            <p class="text-muted-foreground mb-1 text-sm">
                                {{ stat.label }}
                            </p>
                            <p
                                class="flex items-center justify-center gap-1.5 text-2xl font-normal"
                            >
                                <span
                                    class="size-2.5 rounded-full"
                                    :class="stat.dot"
                                />
                                {{ stat.value }}
                            </p>
                        </div>
                    </div>

                    <div class="flex h-52 items-end gap-2 sm:gap-3">
                        <div
                            v-for="(bar, index) in chartBars"
                            :key="chartLabels[index]"
                            class="flex flex-1 flex-col items-center gap-2"
                        >
                            <div
                                class="bg-primary/15 hover:bg-primary/80 relative w-full rounded-t-md transition-colors"
                                :style="{ height: `${bar}%` }"
                            >
                                <div
                                    class="bg-primary absolute inset-x-0 bottom-0 rounded-t-md"
                                    :style="{
                                        height: `${Math.max(35, bar - 15)}%`,
                                    }"
                                />
                            </div>
                            <span
                                class="text-muted-foreground text-[10px] sm:text-xs"
                            >
                                {{ chartLabels[index] }}
                            </span>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card class="gap-4 py-5 shadow-sm">
                <CardContent class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="font-semibold">Total Revenue</h4>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button size="icon-sm" variant="ghost">
                                    <MoreVertical class="size-4" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end">
                                <DropdownMenuItem
                                    >Sales Report</DropdownMenuItem
                                >
                                <DropdownMenuItem
                                    >Export Report</DropdownMenuItem
                                >
                                <DropdownMenuItem>Profit</DropdownMenuItem>
                                <DropdownMenuItem>Action</DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>

                    <div
                        class="bg-muted/40 flex h-56 items-center justify-center rounded-lg"
                    >
                        <Globe class="text-muted-foreground/40 size-16" />
                    </div>

                    <div class="space-y-1 text-center">
                        <p class="text-muted-foreground text-sm">
                            Total sales made today
                        </p>
                        <p class="text-3xl font-semibold tracking-tight">
                            $178
                        </p>
                        <p class="text-muted-foreground text-sm">
                            Traditional heading elements are designed to work
                            best in the meat of your page content.
                        </p>
                    </div>

                    <div
                        class="grid grid-cols-3 gap-2 border-t pt-4 text-center"
                    >
                        <div v-for="item in revenueBreakdown" :key="item.label">
                            <p
                                class="text-muted-foreground mb-1 truncate text-xs"
                            >
                                {{ item.label }}
                            </p>
                            <p
                                class="flex items-center justify-center gap-1 text-sm font-semibold"
                                :class="
                                    item.trend === 'up'
                                        ? 'text-emerald-600 dark:text-emerald-400'
                                        : 'text-destructive'
                                "
                            >
                                <ArrowUp
                                    v-if="item.trend === 'up'"
                                    class="size-3.5"
                                />
                                <ArrowDown v-else class="size-3.5" />
                                {{ item.value }}
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Transaction History + Recent Products -->
        <div class="grid gap-4 xl:grid-cols-2">
            <Card class="gap-4 py-5 shadow-sm">
                <CardContent>
                    <h4 class="mb-3 font-semibold">Transaction History</h4>
                    <Table>
                        <TableHeader>
                            <TableRow class="hover:bg-transparent">
                                <TableHead>Name</TableHead>
                                <TableHead>Card</TableHead>
                                <TableHead>Date</TableHead>
                                <TableHead>Amount</TableHead>
                                <TableHead>Status</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="tx in transactions" :key="tx.name">
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Avatar class="size-8">
                                            <AvatarFallback class="text-xs">
                                                {{ initials(tx.name) }}
                                            </AvatarFallback>
                                        </Avatar>
                                        <span class="text-sm">{{
                                            tx.name
                                        }}</span>
                                    </div>
                                </TableCell>
                                <TableCell class="font-mono text-xs">
                                    {{ tx.card }}
                                </TableCell>
                                <TableCell class="text-sm">{{
                                    tx.date
                                }}</TableCell>
                                <TableCell class="text-sm font-medium">{{
                                    tx.amount
                                }}</TableCell>
                                <TableCell>
                                    <Badge
                                        :variant="
                                            tx.status === 'Paid'
                                                ? 'success'
                                                : 'destructive'
                                        "
                                    >
                                        {{ tx.status }}
                                    </Badge>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>

            <Card class="gap-4 py-5 shadow-sm">
                <CardContent>
                    <h4 class="mb-3 font-semibold">Recent Products</h4>
                    <Table>
                        <TableHeader>
                            <TableRow class="hover:bg-transparent">
                                <TableHead>Product</TableHead>
                                <TableHead>Category</TableHead>
                                <TableHead>Added Date</TableHead>
                                <TableHead>Amount</TableHead>
                                <TableHead>Status</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="product in recentProducts"
                                :key="product.name"
                            >
                                <TableCell class="text-sm font-medium">{{
                                    product.name
                                }}</TableCell>
                                <TableCell class="text-sm">{{
                                    product.category
                                }}</TableCell>
                                <TableCell class="text-sm">{{
                                    product.date
                                }}</TableCell>
                                <TableCell class="text-sm font-medium">{{
                                    product.amount
                                }}</TableCell>
                                <TableCell>
                                    <Badge
                                        :variant="
                                            product.status === 'Active'
                                                ? 'success'
                                                : 'destructive'
                                        "
                                    >
                                        {{ product.status }}
                                    </Badge>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
