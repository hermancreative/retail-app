<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { MoreHorizontal, Plus, Search } from '@lucide/vue';
import PageHeader from '@/components/PageHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

import { dashboard, orders as ordersRoute } from '@/routes';

const breadcrumbs = [
    { title: 'Ecommerce', href: dashboard() },
    { title: 'Orders', href: ordersRoute() },
];

type Status = 'Delivered' | 'Shipped' | 'Processing' | 'Cancelled';

const orders: {
    id: string;
    customer: string;
    date: string;
    items: number;
    amount: string;
    payment: string;
    status: Status;
}[] = [
    {
        id: 'UB#160924',
        customer: 'Imelda Stanberry',
        date: '08/10/2025',
        items: 3,
        amount: '$1,256',
        payment: 'Visa',
        status: 'Delivered',
    },
    {
        id: 'UB#160923',
        customer: 'Francisca Lobb',
        date: '08/10/2025',
        items: 1,
        amount: '$2,562',
        payment: 'Mastercard',
        status: 'Shipped',
    },
    {
        id: 'UB#160922',
        customer: 'James Wert',
        date: '08/10/2025',
        items: 5,
        amount: '$648',
        payment: 'PayPal',
        status: 'Processing',
    },
    {
        id: 'UB#160921',
        customer: 'Dolores Pooley',
        date: '07/10/2025',
        items: 2,
        amount: '$2,005',
        payment: 'Visa',
        status: 'Cancelled',
    },
    {
        id: 'UB#160920',
        customer: 'Karen McCluskey',
        date: '07/10/2025',
        items: 4,
        amount: '$7,521',
        payment: 'COD',
        status: 'Delivered',
    },
    {
        id: 'UB#160919',
        customer: 'Thomas Lau',
        date: '06/10/2025',
        items: 1,
        amount: '$899',
        payment: 'Visa',
        status: 'Shipped',
    },
];

const statusVariant: Record<
    Status,
    'success' | 'info' | 'warning' | 'destructive'
> = {
    Delivered: 'success',
    Shipped: 'info',
    Processing: 'warning',
    Cancelled: 'destructive',
};
</script>

<template>
    <Head title="Orders" />

    <div class="flex flex-1 flex-col gap-4 p-4 md:gap-6 md:p-6">
        <PageHeader title="Orders" :breadcrumbs="breadcrumbs" />

        <Card class="gap-0 overflow-hidden py-0 shadow-sm">
            <CardHeader class="border-b py-5">
                <div
                    class="flex w-full flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div class="relative w-full sm:max-w-xs">
                        <Search
                            class="text-muted-foreground absolute top-1/2 left-2.5 size-4 -translate-y-1/2"
                        />
                        <Input class="pl-8" placeholder="Search orders..." />
                    </div>
                    <Button class="shrink-0">
                        <Plus class="size-4" />
                        Create Order
                    </Button>
                </div>
            </CardHeader>
            <CardContent class="p-0">
                <Table>
                    <TableHeader>
                        <TableRow class="hover:bg-transparent">
                            <TableHead>Order</TableHead>
                            <TableHead>Customer</TableHead>
                            <TableHead>Date</TableHead>
                            <TableHead>Items</TableHead>
                            <TableHead>Payment</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-right">Amount</TableHead>
                            <TableHead class="w-10" />
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="order in orders" :key="order.id">
                            <TableCell class="font-mono text-xs font-medium">{{
                                order.id
                            }}</TableCell>
                            <TableCell class="font-medium">{{
                                order.customer
                            }}</TableCell>
                            <TableCell>{{ order.date }}</TableCell>
                            <TableCell>{{ order.items }}</TableCell>
                            <TableCell>{{ order.payment }}</TableCell>
                            <TableCell>
                                <Badge :variant="statusVariant[order.status]">
                                    {{ order.status }}
                                </Badge>
                            </TableCell>
                            <TableCell class="text-right font-medium">{{
                                order.amount
                            }}</TableCell>
                            <TableCell>
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button size="icon-sm" variant="ghost">
                                            <MoreHorizontal class="size-4" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <DropdownMenuItem
                                            >View</DropdownMenuItem
                                        >
                                        <DropdownMenuItem
                                            >Invoice</DropdownMenuItem
                                        >
                                        <DropdownMenuItem
                                            >Cancel</DropdownMenuItem
                                        >
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </CardContent>
        </Card>
    </div>
</template>
