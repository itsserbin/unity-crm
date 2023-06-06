<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import ModalFooter from '@/Components/Modal/Footer.vue';
import Dropdown from 'primevue/dropdown';
import Button from "primevue/button";
import {ref} from "vue";

const props = defineProps(['item', 'isLoadingModal']);
const emits = defineEmits(['submit', 'close']);

const options = [
    {
        label: 'Monobank',
        value: 'monobank'
    }
];

const allowSubmit = ref(false);
const accounts = ref([]);

const onSubmit = async () => {
    if (allowSubmit.value && props.item.data.api_key && props.item.data.bank && props.item.name && props.item.data.account) {
        console.log(props.item);
        emits('submit');
    } else {
        if (props.item.data.api_key && props.item.data.bank && props.item.name) {
            await getMonoClientInfo();
        }
    }
}

const getMonoClientInfo = async () => {
    const response = await axios.post(route('api.monobank.client-info', props.item.data.api_key));
    const data = response.data;
    props.item.data.clientId = data.result.clientId;
    props.item.data.name = data.result.name;
    accounts.value = data.result.accounts.map((item) => {
        return {
            id: item.id,
            label: item.maskedPan[0],
            iban: item.iban,
        }
    });
    allowSubmit.value = true;
}

const submitLabel = () => {
    if (allowSubmit.value) {
        return 'Зберегти';
    } else {
        return 'Продовжити'
    }
}
</script>

<template>
    <div class="grid gap-4">
        <div class="block">
            <InputLabel :required="true">Назва</InputLabel>
            <InputText type="text"
                       v-model="item.name"
                       class="w-full"
                       placeholder="Вкажіть назву рахунку"
            />
        </div>

        <div class="block" v-if="!item.id">
            <InputLabel :required="true">Банк</InputLabel>
            <Dropdown v-model="item.data.bank"
                      :options="options"
                      optionLabel="label"
                      dataKey="value"
                      class="w-full"
                      placeholder="Оберіть банк"
            />
        </div>

        <div class="block" v-if="item.data.bank && item.data.bank.value === 'monobank'">
            <InputLabel :required="true">
                API ключ (<a href="https://api.monobank.ua/" target="_blank">Отримати</a>)
            </InputLabel>
            <InputText type="text"
                       v-model="item.data.api_key"
                       class="w-full"
                       placeholder="Вкажіть API ключ"/>
        </div>

        <div class="block" v-if="accounts.length">
            <InputLabel :required="true">Рахунок</InputLabel>
            <Dropdown v-model="item.data.account"
                      :options="accounts"
                      optionLabel="label"
                      dataKey="id"
                      class="w-full"
                      placeholder="Оберіть рахунок"
            >
                <template #option="slotProps">
                    <div class="text-sm">{{ slotProps.option.label }}</div>
                    <div class="text-base">{{ slotProps.option.iban }}</div>
                </template>
            </Dropdown>
        </div>
    </div>
    <ModalFooter>
        <Button label="Скасувати"
                icon="pi pi-times"
                @click="emits('close')"
                text
        />
        <Button :label="submitLabel()"
                icon="pi pi-check"
                @click="onSubmit()"
                autofocus
                :loading="isLoadingModal"
        />
    </ModalFooter>
</template>
