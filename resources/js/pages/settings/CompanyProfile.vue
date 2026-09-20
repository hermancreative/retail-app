<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import CompanyProfileController from '@/actions/App/Http/Controllers/Settings/CompanyProfileController';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { edit } from '@/routes/company-profile';

type CompanyProfile = {
    id: number;
    name: string;
    tax_id: string | null;
    email: string | null;
    phone: string | null;
    address: string | null;
};

defineProps<{
    company: CompanyProfile;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Company profile',
                href: edit(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Company profile" />

    <h1 class="sr-only">Company profile</h1>

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            title="Company Profile"
            description="Information shown on invoices, receipts and reports"
        />

        <Form
            v-bind="CompanyProfileController.update.form()"
            class="space-y-6"
            v-slot="{ errors, processing, recentlySuccessful }"
        >
            <div class="grid gap-2">
                <Label for="name">Company Name</Label>
                <Input
                    id="name"
                    name="name"
                    :default-value="company.name"
                    required
                />
                <p v-if="errors.name" class="text-destructive text-sm">
                    {{ errors.name }}
                </p>
            </div>

            <div class="grid gap-2">
                <Label for="tax_id">Tax ID</Label>
                <Input
                    id="tax_id"
                    name="tax_id"
                    :default-value="company.tax_id ?? ''"
                />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        :default-value="company.email ?? ''"
                    />
                    <p v-if="errors.email" class="text-destructive text-sm">
                        {{ errors.email }}
                    </p>
                </div>
                <div class="grid gap-2">
                    <Label for="phone">Phone</Label>
                    <Input
                        id="phone"
                        name="phone"
                        :default-value="company.phone ?? ''"
                    />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="address">Address</Label>
                <textarea
                    id="address"
                    name="address"
                    :default-value="company.address ?? ''"
                    rows="3"
                    class="border-input focus-visible:border-ring focus-visible:ring-ring/50 flex w-full rounded-md border bg-transparent px-3 py-2 text-sm shadow-xs outline-none focus-visible:ring-[3px]"
                />
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="processing">Save</Button>
                <p
                    v-if="recentlySuccessful"
                    class="text-muted-foreground text-sm"
                >
                    Saved.
                </p>
            </div>
        </Form>
    </div>
</template>
