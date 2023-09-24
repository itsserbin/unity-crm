export default {
    async fetch(params) {
        try {
            const response = await axios.get(route('api.products.index', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async destroy(id) {
        try {
            const response = await axios.delete(route('api.products.destroy', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async edit(id) {
        try {
            const response = await axios.get(route('api.products.edit', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async update(item) {
        try {
            const response = await axios.put(route('api.products.update', item.id), item);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response;
        }
    },
    async create(item) {
        try {
            const response = await axios.post(route('api.products.create'), item);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response;
        }
    },
    async list(params) {
        try {
            const response = await axios.get(route('api.products.list', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async search(query) {
        try {
            const response = await axios.get(route('api.products.search', query));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
