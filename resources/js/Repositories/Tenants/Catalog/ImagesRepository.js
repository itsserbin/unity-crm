export default {
    async fetch(params) {
        try {
            const response = await axios.get(route('api.images.index', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async destroy(id) {
        try {
            const response = await axios.delete(route('api.images.destroy', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async edit(id) {
        try {
            const response = await axios.get(route('api.images.edit', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async update(item) {
        try {
            const response = await axios.put(route('api.images.update', item.id), item);
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async search(query) {
        try {
            const response = await axios.get(route('api.images.search', query));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async list() {
        try {
            const response = await axios.get(route('api.images.list'));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
