export default {
    async fetch(params) {
        try {
            const response = await axios.get(route('api.statistics.profits', params));
            console.log(response);
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
