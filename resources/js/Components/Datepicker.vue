<template>
    <div class="relative" id="picker">
        <InputText
            type="text"
            class="input"
            @click="switchOpen"
            :value="displayValue"
            @input="updateModelValue($event.target.value)"
            :placeholder="placeholder"
        />
        <div v-if="isOpened" class="popup-content">
            <div class="header">
                <button class="button" @click="previousMonth">
                    &lt;
                </button>
                <div class="title" @click="switchMonthsOpen">
                    {{ selectedMonthLabel }}
                </div>
                <div class="title" @click="switchYearsOpen">
                    {{ selectedYearLabel }}
                </div>
                <button class="button" @click="nextMonth">
                    &gt;
                </button>
            </div>
            <div v-if="isMonthsOpen" class="months-list">
                <div
                    v-for="(month, index) in months"
                    :key="index"
                    class="month-item"
                    @click="selectMonth(index)"
                >
                    {{ month }}
                </div>
            </div>
            <div v-if="isYearsOpen" class="years-list">
                <div
                    v-for="year in generateYearList()"
                    :key="year"
                    class="year-item"
                    @click="selectYear(year)"
                >
                    {{ year }}
                </div>
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
                        'within-range': isWithinSelectedRange(day),
                         'selected-start-day': isSelectedStartDay(day),
                         'selected-end-day': isSelectedEndDay(day),
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

<script setup>
import InputText from "primevue/inputtext";
import {ref, computed, watch, onMounted} from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Array],
        required: true,
    },
    placeholder: {
        type: String,
        required: false,
    },
    timePickerEnabled: {
        type: Boolean,
        default: false,
    },
    timeFormat: {
        type: String,
        default: 'HH:mm:ss',
    },
    rangeMode: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits([
    'update:modelValue'
]);

const isOpened = ref(false);
const isMonthsOpen = ref(false);
const isYearsOpen = ref(false);
const currentDate = new Date();
let selectedDate = ref(null);
const selectedTime = ref({
    hours: currentDate.getHours(),
    minutes: currentDate.getMinutes(),
    seconds: currentDate.getSeconds(),
});
const selectedDates = ref([]);

const switchOpen = () => (isOpened.value = !isOpened.value);
const switchMonthsOpen = () => (isMonthsOpen.value = !isMonthsOpen.value);
const switchYearsOpen = () => (isYearsOpen.value = !isYearsOpen.value);


// Функція для додавання нової вибраної дати в масив
function addSelectedDate(date) {
    selectedDates.value.push(date);
}

// Функція для видалення вибраної дати з масиву
function removeSelectedDate(date) {
    const index = selectedDates.value.findIndex(selectedDate => selectedDate.getTime() === date.getTime());
    if (index !== -1) {
        selectedDates.value.splice(index, 1);
    }
}

// Функція для перевірки, чи дата вибрана
function isDateSelected(date) {
    return selectedDates.value.some(selectedDate => selectedDate.getTime() === date.getTime());
}

// Функція для обробки кліку на дату
function handleDateClick(date) {
    if (isDateSelected(date)) {
        removeSelectedDate(date);
    } else {
        addSelectedDate(date);
    }
}

// Функція для підсвічування вибраних дат
function highlightSelectedDates() {
    const dates = document.querySelectorAll('.date');
    dates.forEach(date => {
        const dateObj = new Date(date.dataset.date);
        if (isDateSelected(dateObj)) {
            date.classList.add('selected');
        } else {
            date.classList.remove('selected');
        }
    });
}

const isWithinSelectedRange = (day) => {
    const currentMonthDate = new Date(selectedDates.value[0].getFullYear(), selectedDates.value[0].getMonth(), day);
    return selectedDates.value[0] <= currentMonthDate && currentMonthDate <= selectedDates.value[1];
}

const isSelectedStartDay = (day) => {
    const currentMonthDate = new Date(selectedDates.value[0].getFullYear(), selectedDates.value[0].getMonth(), day);
    return selectedDates.value[0] && selectedDates.value[0].getTime() === currentMonthDate.getTime();
}

const isSelectedEndDay = (day) => {
    const currentMonthDate = new Date(selectedDates.value[1].getFullYear(), selectedDates.value[1].getMonth(), day);
    return selectedDates.value[1] && selectedDates.value[1].getTime() === currentMonthDate.getTime();
}

function selectMonth(monthIndex) {
    if (!selectedDate.value) return;
    const year = selectedDate.value.getFullYear();
    selectedDate.value = new Date(year, monthIndex, 1);
    isMonthsOpen.value = false;
}

function generateYearList() {
    const currentYear = new Date().getFullYear();
    return Array.from({length: 10}, (_, i) => currentYear - i);
}

function selectYear(year) {
    if (!selectedDate.value) return;
    const month = selectedDate.value.getMonth();
    selectedDate.value = new Date(year, month, 1);
    isYearsOpen.value = false;
}

const selectedYearLabel = computed(() => {
    if (!selectedDate.value) return '';
    return selectedDate.value.getFullYear();
});
// Розрахунок днів в місяці
const daysInMonth = computed(() => {
    if (!selectedDate.value) return [];
    const year = selectedDate.value.getFullYear();
    const month = selectedDate.value.getMonth();
    return new Date(year, month + 1, 0).getDate();
});

// Назви днів тижня
const days = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Нд'];
const months = ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'];

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
        currentMonthDays.push(null);
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
    if (!selectedDates.value[0]) return false;
    const modelDateStart = new Date(props.modelValue[0]);
    const modelDateEnd = new Date(props.modelValue[1]);
    if (isNaN(modelDateStart.getTime()) || isNaN(modelDateEnd.getTime())) return false;
    const yearStart = selectedDates.value[0].getFullYear();
    const monthStart = selectedDates.value[0].getMonth();
    const yearEnd = selectedDates.value[1].getFullYear();
    const monthEnd = selectedDates.value[1].getMonth();
    return (
        yearStart === modelDateStart.getFullYear() &&
        monthStart === modelDateStart.getMonth() &&
        day === modelDateStart.getDate() &&
        yearEnd === modelDateEnd.getFullYear() &&
        monthEnd === modelDateEnd.getMonth() &&
        day === modelDateEnd.getDate()
    );
}

// Вибір дати
let selectingStart = true; // Прапорець, який вказує, чи вибирається дата початку або кінця

function selectDate(day) {
    const selectedYear = selectedDates.value[selectingStart ? 0 : 1].getFullYear();
    const selectedMonth = selectedDates.value[selectingStart ? 0 : 1].getMonth();

    selectedDates.value[selectingStart ? 0 : 1] = new Date(selectedYear, selectedMonth, day);

    if (props.rangeMode) {
        if (!selectingStart) { // Якщо вибрано кінцеву дату
            // Перевіряємо, чи вибрана кінцева дата не раніше початкової
            if (selectedDates.value[1] < selectedDates.value[0]) {
                // Якщо так, міняємо їх місцями
                [selectedDates.value[0], selectedDates.value[1]] = [selectedDates.value[1], selectedDates.value[0]];
            }

            const startFormattedDate = formatSelectedDate(selectedDates.value[0].getDate());
            const endFormattedDate = formatSelectedDate(selectedDates.value[1].getDate());

            if (!props.timePickerEnabled) {
                emit('update:modelValue', [startFormattedDate, endFormattedDate]);
            }
        }
        selectingStart = !selectingStart; // Змінюємо дату, яку ми вибираємо на наступний клік
    } else {
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
    if (props.rangeMode) {
        emit('update:modelValue', value.split('-'));
    } else {
        emit('update:modelValue', value);
    }
}

// Поточний місяць та рік
const selectedMonthLabel = computed(() => {
    if (!selectedDate.value) return '';
    const month = selectedDate.value.toLocaleString('default', {month: 'long'});
    return `${month}`;
});

// Значення для відображення в полі вводу
const displayValue = computed(() => {
    if (!props.modelValue) return '';
    if (props.rangeMode) {
        const modelDateStart = new Date(props.modelValue[0]);
        const modelDateEnd = new Date(props.modelValue[1]);
        if (isNaN(modelDateStart.getTime()) || isNaN(modelDateEnd.getTime())) return '';
        const yearStart = modelDateStart.getFullYear();
        const monthStart = (modelDateStart.getMonth() + 1).toString().padStart(2, '0');
        const dayStart = modelDateStart.getDate().toString().padStart(2, '0');
        const yearEnd = modelDateEnd.getFullYear();
        const monthEnd = (modelDateEnd.getMonth() + 1).toString().padStart(2, '0');
        const dayEnd = modelDateEnd.getDate().toString().padStart(2, '0');
        return `${yearStart}-${monthStart}-${dayStart} - ${yearEnd}-${monthEnd}-${dayEnd}`;
    } else {
        const modelDate = new Date(props.modelValue);
        if (isNaN(modelDate.getTime())) return '';
        const year = modelDate.getFullYear();
        const month = (modelDate.getMonth() + 1).toString().padStart(2, '0');
        const day = modelDate.getDate().toString().padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
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

watch(() => selectedDate.value, (newValue, oldValue) => {
    if(newValue && oldValue) {
        if(newValue.getMonth() !== oldValue.getMonth() || newValue.getFullYear() !== oldValue.getFullYear()) {
            highlightSelectedDates();
        }
    }
}, {deep: true});


// Встановлення значень за замовчуванням при монтуванні компонента
onMounted(() => {
    if (!props.modelValue) {
        setDefaultTime();
    }
});
</script>

<style scoped>
#picker {
    --background-color: #fff; /* Колір фону */
    --text-color: #000; /* Колір тексту */
    --border-color: #ccc; /* Колір межі */
    --shadow-color: rgba(128, 128, 128, 0.5); /* Колір тіні */
}

@media (prefers-color-scheme: dark) {
    #picker {
        --background-color: #18181b; /* Колір фону у темній темі */
        --text-color: #fff; /* Колір тексту у темній темі */
        --border-color: #888; /* Колір межі у темній темі */
    }
}

.relative {
    position: relative;
}

/* Підсвітка фону для дат в діапазоні */
.selected-start-day,
.selected-end-day {
    background-color: #3f51b5;
    color: #fff;
}

.within-range {
    background-color: #ccc;
    color: #000;
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

.input {
    width: 100%;
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
    box-shadow: 0 2px 4px var(--shadow-color);
    text-align: center;
}

.time-separator {
    margin: 0 4px;
}

.months-list, .years-list {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 колонки для місяців або років */
    gap: 4px; /* відстань між місяцями або роками */
    padding: 4px;
}

.month-item, .year-item {
    text-align: center; /* текст по центру */
    padding: 4px; /* невеликий внутрішній відступ */
    border-radius: 4px; /* закруглені кути */
    cursor: pointer; /* курсор-указівник при наведенні */
    background-color: var(--background-color); /* колір фону змінної */
    color: var(--text-color); /* колір тексту змінної */
    box-shadow: 0 2px 4px var(--shadow-color); /* додано тінь */
}

.month-item:hover, .year-item:hover {
    background-color: #eee; /* зміна кольору фону при наведенні */
    color: var(--text-color); /* колір тексту при наведенні */
}

.month-item:active, .year-item:active {
    background-color: #ddd; /* зміна кольору фону при натисканні */
    color: var(--text-color); /* колір тексту при натисканні */
}

</style>
