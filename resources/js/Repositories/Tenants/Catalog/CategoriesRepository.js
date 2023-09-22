export default {
    async fetch(params) {
        try {
            const response = await axios.get(route('api.categories.index', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async destroy(id) {
        try {
            const response = await axios.delete(route('api.categories.destroy', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async edit(id) {
        try {
            const response = await axios.get(route('api.categories.edit', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async update(item) {
        try {
            const response = await axios.put(route('api.categories.update', item.id), item);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response;
        }
    },
    async create(item) {
        try {
            const response = await axios.post(route('api.categories.create'), item);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response;
        }
    },
    async search(query) {
        try {
            const response = await axios.get(route('api.categories.search', query));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async list() {
        try {
            const response = await axios.get(route('api.categories.list'));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
