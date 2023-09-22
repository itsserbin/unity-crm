export default {
    async fetch(params) {
        try {
            const response = await axios.get(route('api.cash-flow.index', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
