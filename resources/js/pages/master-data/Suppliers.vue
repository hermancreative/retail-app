<script setup lang="ts">
import { Form, Head, router } from '@inertiajs/vue3';
import { Pencil, Plus, Search, Trash2 } from '@lucide/vue';
import { computed, ref } from 'vue';
import SupplierController from '@/actions/App/Http/Controllers/MasterData/SupplierController';
import PageHeader from '@/components/PageHeader.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { index as itemsIndex } from '@/routes/items';
import { index as suppliersIndex } from '@/routes/suppliers';

type Supplier = {
    id: number;
    name: string;
    contact_person: string | null;
    email: string | null;
    phone: string | null;
    address: string | null;
    is_active: boolean;
};

const props = defineProps<{
    suppliers: Supplier[];
    filters: { search?: string };
}>();

const breadcrumbs = [
    { title: 'Master Data', href: itemsIndex() },
    { title: 'Suppliers', href: suppliersIndex() },
];

const search = ref(props.filters.search ?? '');

function onSearch() {
    router.get(
        suppliersIndex().url,
        { search: search.value || undefined },
        { preserveState: true, replace: true },
    );
}

const dialogOpen = ref(false);
const editing = ref<Supplier | null>(null);

function openCreate() {
    editing.value = null;
    dialogOpen.value = true;
}

function openEdit(supplier: Supplier) {
    editing.value = supplier;
    dialogOpen.value = true;
}

const formBinding = computed(() =>
    editing.value
        ? SupplierController.update.form(editing.value.id)
        : SupplierController.store.form(),
);

function onSaved() {
    dialogOpen.value = false;
    editing.value = null;
}

function destroySupplier(supplier: Supplier) {
    if (!confirm(`Delete supplier "${supplier.name}"?`)) {
        return;
    }

    router.delete(SupplierController.destroy.url(supplier.id));
}
</script>

<template>
    <Head title="Suppliers" />

    <div class="flex flex-1 flex-col gap-4 p-4 md:gap-6 md:p-6">
        <PageHeader title="Suppliers" :breadcrumbs="breadcrumbs" />

        <Card class="gap-0 overflow-hidden py-0 shadow-sm">
            <CardHeader class="border-b py-5">
                <div
                    class="flex w-full flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
                >
                    <form
                        class="relative w-full sm:max-w-xs"
                        @submit.prevent="onSearch"
                    >
                        <Search
                            class="text-muted-foreground absolute top-1/2 left-2.5 size-4 -translate-y-1/2"
                        />
                        <Input
                            v-model="search"
                            class="pl-8"
                            placeholder="Search suppliers..."
                            @change="onSearch"
                        />
                    </form>
                    <Button class="shrink-0" @click="openCreate">
                        <Plus class="size-4" />
                        Add Supplier
                    </Button>
                </div>
            </CardHeader>
            <CardContent class="p-0">
                <Table>
                    <TableHeader>
                        <TableRow class="hover:bg-transparent">
                            <TableHead>Supplier</TableHead>
                            <TableHead>Contact</TableHead>
                            <TableHead>Phone</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="w-24" />
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="supplier in suppliers"
                            :key="supplier.id"
                        >
                            <TableCell>
                                <div>
                                    <p class="font-medium">
                                        {{ supplier.name }}
                                    </p>
                                    <p
                                        v-if="supplier.email"
                                        class="text-muted-foreground text-xs"
                                    >
                                        {{ supplier.email }}
                                    </p>
                                </div>
                            </TableCell>
                            <TableCell>{{
                                supplier.contact_person ?? '—'
                            }}</TableCell>
                            <TableCell>{{ supplier.phone ?? '—' }}</TableCell>
                            <TableCell>
                                <Badge
                                    :variant="
                                        supplier.is_active
                                            ? 'success'
                                            : 'secondary'
                                    "
                                >
                                    {{
                                        supplier.is_active
                                            ? 'Active'
                                            : 'Inactive'
                                    }}
                                </Badge>
                            </TableCell>
                            <TableCell>
                                <div class="flex justify-end gap-1">
                                    <Button
                                        size="icon-sm"
                                        variant="ghost"
                                        @click="openEdit(supplier)"
                                    >
                                        <Pencil class="size-4" />
                                    </Button>
                                    <Button
                                        size="icon-sm"
                                        variant="ghost"
                                        @click="destroySupplier(supplier)"
                                    >
                                        <Trash2
                                            class="text-destructive size-4"
                                        />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!suppliers.length">
                            <TableCell
                                colspan="5"
                                class="text-muted-foreground py-8 text-center"
                            >
                                No suppliers found.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </CardContent>
        </Card>

        <Dialog v-model:open="dialogOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{{
                        editing ? 'Edit Supplier' : 'Add Supplier'
                    }}</DialogTitle>
                </DialogHeader>

                <Form
                    v-bind="formBinding"
                    class="space-y-4"
                    v-slot="{ errors, processing }"
                    @success="onSaved"
                >
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            name="name"
                            :default-value="editing?.name"
                            required
                        />
                        <p v-if="errors.name" class="text-destructive text-sm">
                            {{ errors.name }}
                        </p>
                    </div>

                    <div class="grid gap-2">
                        <Label for="contact_person">Contact Person</Label>
                        <Input
                            id="contact_person"
                            name="contact_person"
                            :default-value="editing?.contact_person ?? ''"
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <Label for="email">Email</Label>
                            <Input
                                id="email"
                                type="email"
                                name="email"
                                :default-value="editing?.email ?? ''"
                            />
                            <p
                                v-if="errors.email"
                                class="text-destructive text-sm"
                            >
                                {{ errors.email }}
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <Label for="phone">Phone</Label>
                            <Input
                                id="phone"
                                name="phone"
                                :default-value="editing?.phone ?? ''"
                            />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="address">Address</Label>
                        <textarea
                            id="address"
                            name="address"
                            :default-value="editing?.address ?? ''"
                            rows="2"
                            class="border-input focus-visible:border-ring focus-visible:ring-ring/50 flex w-full rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                        />
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            :checked="editing ? editing.is_active : true"
                            class="border-input size-4 rounded"
                        />
                        Active
                    </label>

                    <DialogFooter>
                        <Button type="submit" :disabled="processing">
                            {{ editing ? 'Save Changes' : 'Create Supplier' }}
                        </Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>
    </div>
</template>
