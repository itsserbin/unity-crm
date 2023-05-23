import numeral from "numeral";

export default {
    formatMoney(value) {
        return numeral(value).format('0,0[.]00');
    },
    formatPhone(value) {
        return `+${value.substr(0, 2)} (${value.substr(2, 3)}) ${value.substr(5, 3)}-${value.substr(8, 2)}-${value.substr(10, 2)}`;
    }
}
