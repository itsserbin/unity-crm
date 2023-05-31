<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import Dropdown from "primevue/dropdown";
import {onMounted, reactive} from "vue";
import UsersRepository from "@/Repositories/Tenants/UsersRepository.js";

const props = defineProps(['item', 'users']);

const state = reactive({
    isLoadingManagersDropdown: false,
    users: []
});

onMounted(async () => {
    if (props.users) {
        state.users = mapData(props.users);
    } else {
        await getUsers();
    }
    if (props.item.manager_id) {
        props.item.manager_id = {value: props.item.manager_id};
    }
});

const getUsers = async () => {
    try {
        const data = await UsersRepository.list();
        state.users = mapData(data.result);
    } catch (e) {
        console.error(e);
    }
}
const mapData = (data) => {
    return data.map((item) => {
        return {
            label: item.email,
            value: item.id
        }
    });
}
</script>

<template>
    <InputLabel :required="true">Менеджер</InputLabel>
    <Dropdown v-model="item.manager_id"
              :options="state.users"
              optionLabel="label"
              dataKey="value"
              placeholder="Оберіть менеджера"
              class="w-full"
              :loading="state.isLoadingManagersDropdown"
              show-clear
    />
</template>
