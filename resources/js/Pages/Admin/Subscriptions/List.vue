<script setup>
import { ref } from 'vue';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import SearchForm from '@/Components/SearchForm.vue';
import Modal from '@/Components/Modal.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import Pagination from '@/Components/Pagination.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const  crumbs = [usePage().props.$t.labels.dashboard, usePage().props.$t.subscriptions.list];

const props = defineProps({ users: Object });

const message = usePage().props.$t.subscriptions.tooltip;
const searchTerm = ref('');
const subscriptions = ref([]);
const isOpenModal = ref(false);

const closeModal = () => {
    isOpenModal.value = false;

    form.reset();
};

const subscriptionId = ref('');
const subscriptionName = ref('');
const openModal = (e) => {
    subscriptionId.value = e.target.dataset.id ?? "";
    subscriptionName.value = e.target.dataset.name ?? "";
    isOpenModal.value = true;
};

const form = useForm({});
const deleteSubscription = () => {
    form.delete(route('subscription.delete', subscriptionId.value), {
        forceFormData: true,
        onSuccess: () => closeModal(),
        onFinish: () => loadSubscriptions(),
    });
}
const searchFields = (text) => {
    searchTerm.value = text;

    loadSubscriptions(`${route('api.admin.subscriptions')}/?filter=${text}`);
}
const loadSubscriptions = (url = null) => {
    axios.get(url || route('api.admin.subscriptions')).then((response) => {
        subscriptions.value = response.data.data

    }).catch((error) => {
        console.log(error);
    });
}
loadSubscriptions();
</script>

<template>
    <Head><title>{{ $page.props.$t.subscriptions.title }}</title></Head>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $page.props.$t.subscriptions.list }}</h2>
            <Breadcrumb :crumbs="crumbs"/>
        </template>
        <div class="py-12">
            <div class="p-8 bg-gray-100 min-h-screen">
                <div class="bg-white relative border rounded-lg">
                    <div class="flex items-center justify-between p-4">
                        <div class="flex items-center justify-end text-sm font-semibold">
                            <a v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.CREATE_SUBSCRIPTION)"
                               :href="route('subscription.create')" class="btn btn-link">{{ $page.props.$t.subscriptions.create }}</a>
                        </div>
                        <SearchForm @search="searchFields" :message="message"/>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">{{ $page.props.$t.labels.name }}</th>
                                <th scope="col">{{ $page.props.$t.labels.information }}</th>
                                <th scope="col">{{ $page.props.$t.labels.options }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="subscription in subscriptions.data" :key="subscription.id" class="hover">
                                <td>{{ subscription.name }}</td>
                                <td>
                                    <div tabindex="0" class="collapse bg-base-200">
                                        <div class="collapse-title">{{ $page.props.$t.subscriptions.msj_information }}</div>
                                        <div class="collapse-content">
                                            <p>{{$page.props.$t.labels.description }}: {{ subscription.description.slice(0,100) }}</p>
                                            <p>{{$page.props.$t.labels.currency }}: {{ subscription.currency }}</p>
                                            <p>{{$page.props.$t.subscriptions.amount }}: ${{ subscription.amount }}</p>
                                            <p>{{$page.props.$t.invoices.microsites }}: {{ subscription.microsite }}</p>
                                            <p>{{$page.props.$t.subscriptions.time_expire }}: {{ subscription.time_expire }}</p>
                                            <p>{{$page.props.$t.subscriptions.billing_frequency }}: {{ subscription.billing_frequency }}</p>
                                            <p>{{$page.props.$t.labels.status }}: {{ subscription.status }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.UPDATE_SUBSCRIPTION)"
                                       :href="route('subscription.edit', subscription.id)"
                                       class="btn btn-link">{{ $page.props.$t.labels.edit }}</a>
                                    <a v-if="$page.props.auth.user_permissions.includes($page.props.auth.permissions.DELETE_SUBSCRIPTION)"
                                       :data-id="subscription.id" :data-name="subscription.name" @click="openModal"
                                       class="btn btn-link">{{ $page.props.$t.labels.delete }}</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <Pagination v-if="subscriptions && subscriptions.data?.length > 0" class="mt-6 mb-6" :links="subscriptions.links"
                            :filter="`&filter=${searchTerm}`" :click="loadSubscriptions"/>
            </div>
        </div>
        <Modal :show="isOpenModal" @close="closeModal">
            <template v-slot>
                <div class="p-6">
                    <h2 class="text-lg font-semibold">{{ $page.props.$t.subscriptions.delete }}</h2>
                    <p class="mt-4">{{ $page.props.$t.subscriptions.msj_delete }} {{ subscriptionName }}?</p>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> {{ $page.props.$t.labels.cancel }} </SecondaryButton>
                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="deleteSubscription"
                        >
                            {{ $page.props.$t.labels.delete }}
                        </DangerButton>
                    </div>
                </div>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>



