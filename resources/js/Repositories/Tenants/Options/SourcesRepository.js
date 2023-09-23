export default {
    async fetch(params) {
        try {
            const response = await axios.get(route('api.sources.index', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async destroy(id) {
        try {
            const response = await axios.delete(route('api.sources.destroy', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async edit(id) {
        try {
            const response = await axios.get(route('api.sources.edit', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async update(item) {
        try {
            const response = await axios.put(route('api.sources.update', item.id), item);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response;
        }
    },
    async create(item) {
        try {
            const response = await axios.post(route('api.sources.create'), item);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response;
        }
    },
    async search(query) {
        try {
            const response = await axios.get(route('api.sources.search', query));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async list() {
        try {
            const response = await axios.get(route('api.sources.list'));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
