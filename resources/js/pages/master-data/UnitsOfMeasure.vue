<script setup lang="ts">
import { Form, Head, router } from '@inertiajs/vue3';
import { Pencil, Plus, Search, Trash2 } from '@lucide/vue';
import { computed, ref } from 'vue';
import UnitOfMeasureController from '@/actions/App/Http/Controllers/MasterData/UnitOfMeasureController';
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
import { index as unitsIndex } from '@/routes/units-of-measure';

type UnitOfMeasure = {
    id: number;
    name: string;
    symbol: string;
    is_active: boolean;
    items_count: number;
};

const props = defineProps<{
    units: UnitOfMeasure[];
    filters: { search?: string };
}>();

const breadcrumbs = [
    { title: 'Master Data', href: itemsIndex() },
    { title: 'Units of Measure', href: unitsIndex() },
];

const search = ref(props.filters.search ?? '');

function onSearch() {
    router.get(
        unitsIndex().url,
        { search: search.value || undefined },
        { preserveState: true, replace: true },
    );
}

const dialogOpen = ref(false);
const editing = ref<UnitOfMeasure | null>(null);

function openCreate() {
    editing.value = null;
    dialogOpen.value = true;
}

function openEdit(unit: UnitOfMeasure) {
    editing.value = unit;
    dialogOpen.value = true;
}

const formBinding = computed(() =>
    editing.value
        ? UnitOfMeasureController.update.form(editing.value.id)
        : UnitOfMeasureController.store.form(),
);

function onSaved() {
    dialogOpen.value = false;
    editing.value = null;
}

function destroyUnit(unit: UnitOfMeasure) {
    if (!confirm(`Delete unit "${unit.name}"?`)) {
        return;
    }

    router.delete(UnitOfMeasureController.destroy.url(unit.id));
}
</script>

<template>
    <Head title="Units of Measure" />

    <div class="flex flex-1 flex-col gap-4 p-4 md:gap-6 md:p-6">
        <PageHeader title="Units of Measure" :breadcrumbs="breadcrumbs" />

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
                            placeholder="Search units..."
                            @change="onSearch"
                        />
                    </form>
                    <Button class="shrink-0" @click="openCreate">
                        <Plus class="size-4" />
                        Add Unit
                    </Button>
                </div>
            </CardHeader>
            <CardContent class="p-0">
                <Table>
                    <TableHeader>
                        <TableRow class="hover:bg-transparent">
                            <TableHead>Name</TableHead>
                            <TableHead>Symbol</TableHead>
                            <TableHead>Items</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="w-24" />
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="unit in units" :key="unit.id">
                            <TableCell class="font-medium">{{
                                unit.name
                            }}</TableCell>
                            <TableCell class="font-mono text-xs">{{
                                unit.symbol
                            }}</TableCell>
                            <TableCell>{{ unit.items_count }}</TableCell>
                            <TableCell>
                                <Badge
                                    :variant="
                                        unit.is_active ? 'success' : 'secondary'
                                    "
                                >
                                    {{ unit.is_active ? 'Active' : 'Inactive' }}
                                </Badge>
                            </TableCell>
                            <TableCell>
                                <div class="flex justify-end gap-1">
                                    <Button
                                        size="icon-sm"
                                        variant="ghost"
                                        @click="openEdit(unit)"
                                    >
                                        <Pencil class="size-4" />
                                    </Button>
                                    <Button
                                        size="icon-sm"
                                        variant="ghost"
                                        @click="destroyUnit(unit)"
                                    >
                                        <Trash2
                                            class="text-destructive size-4"
                                        />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!units.length">
                            <TableCell
                                colspan="5"
                                class="text-muted-foreground py-8 text-center"
                            >
                                No units found.
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
                        editing ? 'Edit Unit' : 'Add Unit'
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
                        <Label for="symbol">Symbol</Label>
                        <Input
                            id="symbol"
                            name="symbol"
                            :default-value="editing?.symbol"
                            placeholder="pcs, kg, box"
                            required
                        />
                        <p
                            v-if="errors.symbol"
                            class="text-destructive text-sm"
                        >
                            {{ errors.symbol }}
                        </p>
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
                            {{ editing ? 'Save Changes' : 'Create Unit' }}
                        </Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>
    </div>
</template>
