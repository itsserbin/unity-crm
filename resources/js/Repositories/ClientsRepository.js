export default {
    async fetch(params) {
        try {
            const response = await axios.get(route('api.clients.index', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async destroy(id) {
        try {
            const response = await axios.delete(route('api.clients.destroy', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async edit(id) {
        try {
            const response = await axios.get(route('api.clients.edit', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async update(item) {
        try {
            const response = await axios.put(route('api.clients.update', item.id), item);
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async create(item) {
        try {
            const response = await axios.post(route('api.clients.create'), item);
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async search(query) {
        try {
            const response = await axios.get(route('api.clients.search', query));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async list() {
        try {
            const response = await axios.get(route('api.clients.list'));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
