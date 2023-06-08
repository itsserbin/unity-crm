<script setup>
import Button from "primevue/button";
import Toolbar from "primevue/toolbar";
import {computed} from "vue";

const props = defineProps(['banks']);
const emits = defineEmits(['clickButton']);

const totalAccountsSum = computed(() => {
    return props.banks.reduce((accumulator, currentBank) => {
        return accumulator + currentBank.balance;
    }, 0);
})
</script>

<template>
    <Toolbar class="mb-4">
        <template #start>
            <div class="flex flex-wrap justify-center gap-4">
                <Button type="button"
                        size="small"
                        text raised
                        class="text-lg"
                        @click="emits('clickButton')"
                >
                    <template #default>
                        <div class="flex flex-col gap-2 w-full">
                            <div class="text-2xl">Всі рахунки</div>
                            <div>{{ $filters.formatMoney(totalAccountsSum) }} грн.</div>
                        </div>
                    </template>
                </Button>
                <Button v-for="item in banks"
                        type="button"
                        size="small"
                        text raised
                        @click="emits('clickButton',item.id)"
                >
                    <template #default>
                        <div class="flex flex-col gap-2 w-full">
                            <div class="text-2xl">{{ item.name }}</div>
                            <div>{{ $filters.formatMoney(item.balance) }} грн.</div>
                        </div>
                    </template>
                </Button>
            </div>
        </template>
    </Toolbar>
</template>
