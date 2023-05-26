<template>
    <div class="relative">
        <InputText
            type="text"
            class="input"
            @click="switchOpen"
            :value="displayValue"
            @input="updateModelValue($event.target.value)"
        />
        <div v-if="isOpened" class="popup-content">
            <div class="header">
                <button class="button" @click="previousMonth">
                    &lt;
                </button>
                <div class="title">{{ selectedMonthLabel }}</div>
                <button class="button" @click="nextMonth">
                    &gt;
                </button>
            </div>
            <div class="days-header">
                <div
                    v-for="(day, index) in days"
                    :key="index"
                    class="day-header"
                >
                    {{ day }}
                </div>
            </div>
            <div class="days-grid">
                <div
                    v-for="(day, index) in generateCurrentMonthDays()"
                    :key="index"
                    :class="{
                        'day-cell': true,
                        'current-day': isCurrentDay(day),
                        'selected-day': isSelectedDay(day),
                        'hover-day': !isCurrentDay(day) && !isSelectedDay(day),
                        'hover-selected-day': !isCurrentDay(day) && isSelectedDay(day),
                        active: isSelectedDay(day),
                    }"
                    @click="selectDate(day)"
                >
                    {{ day }}
                </div>
            </div>
            <div v-if="timePickerEnabled" class="time-picker">
                <div class="time-inputs">
                    <input
                        type="text"
                        class="time-input"
                        v-model="selectedTime.hours"
                        @input="updateTimeValue"
                    />
                    <span class="time-separator">:</span>
                    <input
                        type="text"
                        class="time-input"
                        v-model="selectedTime.minutes"
                        @input="updateTimeValue"
                    />
                    <span class="time-separator">:</span>
                    <input
                        type="text"
                        class="time-input"
                        v-model="selectedTime.seconds"
                        @input="updateTimeValue"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import InputText from "primevue/inputtext";
import {ref, computed, watch, onMounted} from 'vue';

export default {
    components: {InputText},
    props: {
        modelValue: {
            type: String,
            required: true,
        },
        timePickerEnabled: {
            type: Boolean,
            default: false,
        },
        timeFormat: {
            type: String,
            default: 'HH:mm:ss',
        },
    },
    emits: ['update:modelValue'],
    setup(props, {emit}) {
        const isOpened = ref(false);
        const currentDate = new Date();
        const selectedDate = ref(currentDate);
        const selectedTime = ref({
            hours: currentDate.getHours(),
            minutes: currentDate.getMinutes(),
            seconds: currentDate.getSeconds(),
        });

        const switchOpen = () => (isOpened.value = !isOpened.value);

        // Розрахунок днів в місяці
        const daysInMonth = computed(() => {
            if (!selectedDate.value) return [];
            const year = selectedDate.value.getFullYear();
            const month = selectedDate.value.getMonth();
            return new Date(year, month + 1, 0).getDate();
        });

        // Назви днів тижня
        const days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Нд'];

        // Розрахунок першого дня поточного місяця
        const firstDayOfMonth = computed(() => {
            if (!selectedDate.value) return 0;
            const year = selectedDate.value.getFullYear();
            const month = selectedDate.value.getMonth();
            const firstDay = new Date(year, month, 1).getDay();
            return firstDay === 0 ? 6 : firstDay - 1; // Понеділок - 0, Вівторок - 1, ..., Неділя - 6
        });


        function generateCurrentMonthDays() {
            const currentMonthDays = [];
            const daysCount = daysInMonth.value;
            const firstDay = firstDayOfMonth.value;

            // Додавання порожніх значень для початкових порожніх днів
            for (let i = 0; i < firstDay; i++) {
                currentMonthDays.push("");
            }

            // Додавання днів місяця
            for (let i = 1; i <= daysCount; i++) {
                currentMonthDays.push(i);
            }

            return currentMonthDays;
        }

        // Форматування вибраної дати в формат YYYY-MM-DD
        function formatSelectedDate(day) {
            if (!selectedDate.value) return '';
            const year = selectedDate.value.getFullYear();
            const month = selectedDate.value.getMonth() + 1;
            const formattedMonth = month.toString().padStart(2, '0');
            const formattedDay = day.toString().padStart(2, '0');
            return `${year}-${formattedMonth}-${formattedDay}`;
        }

        // Перехід до попереднього місяця
        function previousMonth() {
            if (!selectedDate.value) return;
            const year = selectedDate.value.getFullYear();
            const month = selectedDate.value.getMonth();
            selectedDate.value = new Date(year, month - 1, 1);
        }

        // Перехід до наступного місяця
        function nextMonth() {
            if (!selectedDate.value) return;
            const year = selectedDate.value.getFullYear();
            const month = selectedDate.value.getMonth();
            selectedDate.value = new Date(year, month + 1, 1);
        }

        // Перевірка, чи вибрана дата є поточним днем
        function isCurrentDay(day) {
            if (!selectedDate.value) return false;
            const year = selectedDate.value.getFullYear();
            const month = selectedDate.value.getMonth();
            const currentDate = new Date();
            return (
                year === currentDate.getFullYear() &&
                month === currentDate.getMonth() &&
                day === currentDate.getDate()
            );
        }

        // Перевірка, чи вибрана дата є вибраною датою моделі
        function isSelectedDay(day) {
            if (!selectedDate.value) return false;
            const modelDate = new Date(props.modelValue);
            if (isNaN(modelDate.getTime())) return false;
            const year = selectedDate.value.getFullYear();
            const month = selectedDate.value.getMonth();
            return (
                year === modelDate.getFullYear() &&
                month === modelDate.getMonth() &&
                day === modelDate.getDate()
            );
        }

        // Вибір дати
        function selectDate(day) {
            if (!selectedDate.value) return;
            const selectedYear = selectedDate.value.getFullYear();
            const selectedMonth = selectedDate.value.getMonth();
            selectedDate.value = new Date(selectedYear, selectedMonth, day);

            const formattedDate = formatSelectedDate(day);
            if (props.timePickerEnabled) {
                if (!props.modelValue) {
                    setDefaultTime();
                }
                const formattedTime = `${selectedTime.value.hours}:${selectedTime.value.minutes}:${selectedTime.value.seconds}`;
                emit('update:modelValue', `${formattedDate} ${formattedTime}`);
            } else {
                emit('update:modelValue', formattedDate);
            }

            switchOpen();
        }

        // Оновлення значення часу
        function updateTimeValue() {
            const formattedDate = formatSelectedDate(
                selectedDate.value.getDate()
            );
            const formattedTime = `${selectedTime.value.hours}:${selectedTime.value.minutes}:${selectedTime.value.seconds}`;
            emit('update:modelValue', `${formattedDate} ${formattedTime}`);
        }

        // Оновлення значення моделі
        function updateModelValue(value) {
            emit('update:modelValue', value);
        }

        // Поточний місяць та рік
        const selectedMonthLabel = computed(() => {
            if (!selectedDate.value) return '';
            const year = selectedDate.value.getFullYear();
            const month = selectedDate.value.toLocaleString('default', {month: 'long'});
            return `${month} ${year}`;
        });

        // Значення для відображення в полі вводу
        const displayValue = computed(() => {
            if (!props.modelValue) return '';
            const modelDate = new Date(props.modelValue);
            if (isNaN(modelDate.getTime())) return '';
            const year = modelDate.getFullYear();
            const month = (modelDate.getMonth() + 1).toString().padStart(2, '0');
            const day = modelDate.getDate().toString().padStart(2, '0');
            let time = '';
            if (props.timePickerEnabled) {
                const timeFormat = props.timeFormat || 'HH:mm:ss';
                const hours = modelDate.getHours().toString().padStart(2, '0');
                const minutes = modelDate.getMinutes().toString().padStart(2, '0');
                const seconds = modelDate.getSeconds().toString().padStart(2, '0');
                time = ` ${hours}:${minutes}:${seconds}`;
            }
            return `${year}-${month}-${day}${time}`;
        });

        // Встановлення значень за замовчуванням для часу
        function setDefaultTime() {
            const currentDate = new Date();
            selectedTime.value.hours = currentDate.getHours();
            selectedTime.value.minutes = currentDate.getMinutes();
            selectedTime.value.seconds = currentDate.getSeconds();
        }

        // Слідкування за зміною моделі
        watch(
            () => props.modelValue,
            (newValue) => {
                if (newValue) {
                    const modelDate = new Date(newValue);
                    if (!isNaN(modelDate.getTime())) {
                        selectedDate.value = new Date(modelDate.getFullYear(), modelDate.getMonth(), modelDate.getDate());
                        selectedTime.value.hours = modelDate.getHours();
                        selectedTime.value.minutes = modelDate.getMinutes();
                        selectedTime.value.seconds = modelDate.getSeconds();
                    }
                } else {
                    selectedDate.value = null;
                    setDefaultTime();
                }
            }
        );

        // Встановлення значень за замовчуванням при монтуванні компонента
        onMounted(() => {
            if (!props.modelValue) {
                setDefaultTime();
            }
        });

        return {
            isOpened,
            selectedDate,
            selectedTime,
            switchOpen,
            firstDayOfMonth,
            daysInMonth,
            days,
            formatSelectedDate,
            previousMonth,
            nextMonth,
            isCurrentDay,
            isSelectedDay,
            selectDate,
            updateTimeValue,
            updateModelValue,
            selectedMonthLabel,
            displayValue,
            generateCurrentMonthDays,
        };
    },
};
</script>
<style>

:root {
    --background-color: #fff; /* Колір фону */
    --text-color: #000; /* Колір тексту */
    --border-color: #ccc; /* Колір межі */
    --shadow-color: rgba(128, 128, 128, 0.5); /* Колір тіні */
}

@media (prefers-color-scheme: dark) {
    :root {
        --background-color: #18181b; /* Колір фону у темній темі */
        --text-color: #fff; /* Колір тексту у темній темі */
        --border-color: #888; /* Колір межі у темній темі */
    }
}

.relative {
    position: relative;
}

.popup-content {
    background-color: var(--background-color);
    border: 1px solid var(--border-color);
    border-radius: 4px;
    box-shadow: 0 0 10px var(--shadow-color);
    max-width: 300px;
    padding: 8px;
    color: var(--text-color);
    position: absolute;
    z-index: 1100;
}


.header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.button {
    padding: 4px 8px;
    border-radius: 4px;
    background-color: var(--background-color);
    color: var(--text-color);
    cursor: pointer;
    box-shadow: 0 2px 4px var(--shadow-color); /* Додано тінь */
}

.title {
    font-size: 16px;
    font-weight: bold;
    color: var(--text-color);
}

.days-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
}

.day-header {
    text-align: center;
    color: #666;
    padding: 4px;
}

.days-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
}

.day-cell {
    text-align: center;
    padding: 4px;
    border-radius: 4px;
    cursor: pointer;
    color: var(--text-color);
    background-color: var(--background-color);
    border: 1px solid var(--border-color);
    box-shadow: 0 2px 4px var(--shadow-color); /* Додано тінь */
}

.current-day {
    background-color: #ddd;
}

.selected-day {
    background-color: #3f51b5;
    color: #fff;
}

.hover-day:hover {
    background-color: #eee;
}

.hover-selected-day:hover {
    background-color: #3f51b5;
    color: #fff;
}

.active {
    font-weight: bold;
}

.time-picker {
    margin-top: 8px;
}

.time-inputs {
    display: flex;
    align-items: center;
}

.time-input {
    border: 1px solid var(--border-color);
    border-radius: 4px;
    padding: 8px;
    width: 64px;
    margin-right: 4px;
    background-color: var(--background-color);
    color: var(--text-color);
    box-shadow: 0 2px 4px var(--shadow-color); /* Додано тінь */
}

.time-separator {
    margin: 0 4px;
}

</style>
