export default {
    async fetch(params) {
        try {
            const response = await axios.get(route('api.orders.index', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async destroy(id) {
        try {
            const response = await axios.delete(route('api.orders.destroy', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async edit(id) {
        try {
            const response = await axios.get(route('api.orders.edit', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async update(id, item) {
        try {
            const response = await axios.put(route('api.orders.update', id), item);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response;
        }
    },
    async create(item) {
        try {
            const response = await axios.post(route('api.orders.create'), item);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response;
        }
    },
    async search(query) {
        try {
            const response = await axios.get(route('api.orders.search', query));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
