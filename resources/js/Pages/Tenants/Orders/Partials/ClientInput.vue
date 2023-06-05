<script setup>
import Button from "primevue/button";
import Loader from "@/Components/Loader.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Dropdown from "primevue/dropdown";
import Card from "primevue/card";

import {defineAsyncComponent, onMounted, reactive} from "vue";
import ClientsRepository from "@/Repositories/Tenants/ClientsRepository.js";
import {toast} from "vue3-toastify";

const ClientModal = defineAsyncComponent(() => import('@/Pages/Tenants/Clients/Modal.vue'))
const AddressesModal = defineAsyncComponent(() => import('@/Pages/Tenants/Clients/Partials/AddressesModal.vue'))

const props = defineProps(['item', 'clients']);

const state = reactive({
    client: null,
    isLoadingClient: false,
    isLoadingClientsDropdown: false,
});

const clientModal = reactive({
    isShow: false,
    item: {
        phones: [{number: null}],
        emails: [{address: null}],
        full_name: null,
        comment: null,
        addresses: [],
        orders: []
    }
});

const addressModal = reactive({
    isShow: false,
    item: {
        address: null,
        city: null,
        region: null,
        zip_code: null,
    }
});

onMounted(async () => {
    if (!props.item.client_id) {
        state.clients = mapData(props.clients);
    } else {
        state.client = props.item.client;
        props.item.client_id = {value: props.item.client_id};
    }
});

const switchClientModal = (val) => val ? clientModal.isShow = val : clientModal.isShow = !clientModal.isShow;
const switchAddressModal = (val) => val ? addressModal.isShow = val : addressModal.isShow = !addressModal.isShow;

const getClients = async () => {
    state.isLoadingClientsDropdown = true;
    try {
        const data = await ClientsRepository.list();
        state.clients = mapData(data.result);
    } catch (e) {
        console.error(e);
        toast.error("Failed to get statuses");
    }
    state.isLoadingClientsDropdown = false;
}

const mapData = (data) => {
    return data.map((item) => {
        return {
            label: item.full_name + ' ' + item.phones.map(function (phone) {
                return phone.number;
            }).join(','),
            name: item.full_name,
            phones: item.phones,
            emails: item.emails,
            value: item.id
        }
    });
}

const onChangeClient = async (e) => {
    try {
        await getClient(e.value.value);
    } catch (e) {
        console.error(e);
    }
}

const getClient = async (id) => {
    state.isLoadingClient = true;
    try {
        const data = await ClientsRepository.edit(id);
        state.client = data.result;
    } catch (e) {
        console.error(e);
    }
    state.isLoadingClient = false;
}

const removeClient = async () => {
    state.client = null;
    props.item.client = null;
    props.item.client_id = null;
    props.item.delivery_address = null;
    await getClients();
}
const removeDeliveryAddress = () => {
    props.item.delivery_address = null;
}

const onCreateClient = async () => {
    try {
        await ClientsRepository.create(clientModal.item);
        clientModal.item = {
            phones: [{number: null}],
            emails: [{address: null}],
            full_name: null,
            comment: null,
            addresses: [],
            orders: []
        };
        await getClients();
        switchClientModal(false);
    } catch (e) {
        console.error(e);
        toast.error("Failed to create client");
    }
}

const onChangeDeliveryAddress = (e) => {
    const paramsToInclude = ['city', 'region', 'address'];
    props.item.delivery_address = paramsToInclude
        .filter(function (param) {
            return e.value[param];
        })
        .map(function (param) {
            return e.value[param];
        })
        .join(', ');
}

const onCreateAddress = async () => {
    state.client.addresses.push(addressModal.item);
    await ClientsRepository.createClientAddress(state.client.id, addressModal.item);
    // await getClient(state.client.id);
    switchAddressModal();
    const e = {
        value: addressModal.item
    }
    onChangeDeliveryAddress(e);
    addressModal.item = {
        address: null,
        city: null,
        region: null,
        zip_code: null,
    };
}
</script>

<template>
    <div class="flex justify-center w-full" v-if="state.isLoadingClient">
        <div class="w-16 h-16">
            <Loader/>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4" v-if="!state.isLoadingClient">
        <Card class="md:col-span-2">
            <template #content>
                <InputLabel class="w-full">Клієнт</InputLabel>
                <div class="p-inputgroup" v-if="!item.client_id">
                    <Dropdown v-model="item.client_id"
                              :options="state.clients"
                              optionLabel="label"
                              dataKey="value"
                              placeholder="Оберіть клієнта"
                              class="w-full"
                              :loading="state.isLoadingClientsDropdown"
                              filter
                              @change="onChangeClient"
                    >
                        <template #option="slotProps">
                            <div class="text-base">{{ slotProps.option.name }}</div>
                            <div class="text-sm text-zinc-800 dark:text-zinc-200"
                                 v-for="phone in slotProps.option.phones">
                                {{ phone.number }}
                            </div>
                            <div class="text-sm text-zinc-800 dark:text-zinc-200"
                                 v-for="email in slotProps.option.emails"
                                 v-if="slotProps.option.emails.length">
                                {{ email.address }}
                            </div>
                        </template>
                    </Dropdown>
                    <Button @click="switchClientModal" type="button" icon="pi pi-plus"></Button>
                </div>
                <div v-if="state.client && item.client_id"

                     class="flex p-2 text-zinc-800 dark:text-zinc-200 hover:bg-zinc-300 dark:hover:bg-zinc-700 rounded transition-all">
                    <div class="flex flex-col w-full">
                        <div class="text-lg">
                            <i class="pi pi-user"></i>
                            {{ state.client.full_name }}
                        </div>
                        <div class="text-sm" v-for="phone in state.client.phones">
                            <div v-if="phone.number">
                                <i class="pi pi-phone"></i>
                                {{ phone.number }}
                            </div>
                        </div>
                        <div class="text-sm" v-for="email in state.client.emails">
                            <div v-if="email.address">
                                <i class="pi pi-at"></i>
                                {{ email.address }}
                            </div>
                        </div>
                    </div>
                    <Button type="button"
                            text
                            icon="pi pi-times"
                            @click="removeClient"
                    />
                </div>
            </template>
        </Card>
        <Card v-if="state.client && item.client_id" class="md:col-span-4">
            <template #content>
                <InputLabel>Адрес доставки</InputLabel>
                <div class="p-inputgroup" v-if="!item.delivery_address">
                    <Dropdown v-model="item.delivery_address"
                              :options="state.client.addresses"
                              optionLabel="address"
                              dataKey="id"
                              placeholder="Оберіть адресу"
                              class="w-full"
                              @change="onChangeDeliveryAddress"
                    />
                    <Button @click="switchAddressModal" type="button" icon="pi pi-plus"></Button>
                </div>
                <div v-else
                     class="flex text-zinc-800 dark:text-zinc-200 hover:bg-zinc-300 dark:hover:bg-zinc-700 p-2 rounded transition-all w-full">
                    <div class="w-full flex items-center">
                        {{ item.delivery_address }}
                    </div>
                    <Button type="button"
                            text
                            icon="pi pi-times"
                            @click="removeDeliveryAddress"
                    />
                </div>
            </template>
        </Card>
    </div>
    <ClientModal v-if="clientModal.isShow"
                 :show="clientModal.isShow" :item="clientModal.item"
                 @close="switchClientModal" @submit="onCreateClient"/>

    <AddressesModal v-if="addressModal.isShow"
                    :show="addressModal.isShow" :item="addressModal.item"
                    @close="switchAddressModal" @submit="onCreateAddress"/>
</template>
