<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import Dropdown from "primevue/dropdown";
import {onMounted, reactive} from "vue";
import UsersRepository from "@/Repositories/Tenants/Options/UsersRepository.js";

const props = defineProps(['item', 'users']);

const state = reactive({
    isLoadingManagersDropdown: false,
    users: []
});

onMounted(async () => {
    if (props.users) {
        state.users = props.users;
    } else {
        await getUsers();
    }

    if (props.item.manager_id && !state.users.some(item => item.id === props.item.manager_id)) {
        state.users.push(props.item.manager);
    }
});

const getUsers = async (params) => {
    try {
        const data = await UsersRepository.list(params);
        state.users = data.result;
    } catch (e) {
        console.error(e);
    }
}

let searchTimeout = null;

const onSearch = async (query) => {
    clearTimeout(searchTimeout);
    if (typeof props.item.manager_id !== 'object') {
        state.isLoadingManagersDropdown = true;
        searchTimeout = setTimeout(async () => {
            await getUsers({
                query: query.value,
            });
            state.isLoadingManagersDropdown = false;
        }, 700);
    }
}
</script>

<template>
    <InputLabel>Менеджер</InputLabel>
    <Dropdown v-model="item.manager_id"
              :filter="false"
              :options="state.users"
              optionLabel="name"
              dataKey="id"
              placeholder="Оберіть менеджера"
              class="w-full"
              :loading="state.isLoadingManagersDropdown"
              show-clear
              @change="onSearch"
              editable
    >
        <template #option="slotProps">
            <div class="flex gap-x-2 items-center" v-if="slotProps.option.name">
                <i class="pi pi-user"></i>
                <div class="text-xl">{{ slotProps.option.name }}</div>
            </div>
            <div class="flex gap-x-2 items-center">
                <i class="pi pi-phone"></i>
                <div class="text-sm text-zinc-800 dark:text-zinc-200">
                    {{ slotProps.option.phone }}
                </div>
            </div>
            <div class="flex items-center gap-x-2">
                <i class="pi pi-inbox"></i>
                <div class="text-sm text-zinc-800 dark:text-zinc-200">
                    {{ slotProps.option.email }}
                </div>
            </div>
        </template>
    </Dropdown>
</template>
