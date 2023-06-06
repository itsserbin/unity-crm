<script setup>
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import ModalFooter from '@/Components/Modal/Footer.vue';
import Dropdown from 'primevue/dropdown';
import Button from "primevue/button";
import {ref} from "vue";
import SelectButton from 'primevue/selectbutton';

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

const types = [
    {
        label: 'Витрата',
        value: 0
    }
];
</script>

<template>
    <div class="grid gap-4">
        <div class="block">
            <InputLabel :required="true">Тип</InputLabel>
            <SelectButton v-model="value" :options="options" aria-labelledby="basic" />

            <InputText type="text"
                       v-model="item.name"
                       class="w-full"
                       placeholder="Вкажіть назву рахунку"
            />
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
