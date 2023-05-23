export default {
    async fetch(params) {
        try {
            const response = await axios.get(route('api.statuses.index', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async destroy(id) {
        try {
            const response = await axios.delete(route('api.statuses.destroy', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async edit(id) {
        try {
            const response = await axios.get(route('api.statuses.edit', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async update(item) {
        try {
            const response = await axios.put(route('api.statuses.update', item.id), item);
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async create(item) {
        try {
            const response = await axios.post(route('api.statuses.create'), item);
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async list() {
        try {
            const response = await axios.get(route('api.statuses.list'));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async setPublished(params) {
        try {
            const response = await axios.get(route('api.statuses.set-published', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
