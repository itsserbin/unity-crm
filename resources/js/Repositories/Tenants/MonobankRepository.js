export default {
    async clientInfo(params) {
        try {
            const response = await axios.post(route('api.monobank.client-info', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async extract(params) {
        try {
            const response = await axios.post(route('api.monobank.extract', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
}
