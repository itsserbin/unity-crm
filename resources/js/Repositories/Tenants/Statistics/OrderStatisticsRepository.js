export default {
    async fetch(params) {
        try {
            const response = await axios.get(route('api.statistics.orders', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
