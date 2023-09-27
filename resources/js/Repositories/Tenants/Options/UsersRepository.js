export default {
    async fetch(params) {
        try {
            const response = await axios.get(route('api.users.index', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async destroy(id) {
        try {
            const response = await axios.delete(route('api.users.destroy', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async edit(id) {
        try {
            const response = await axios.get(route('api.users.edit', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async update(item) {
        try {
            const response = await axios.put(route('api.users.update', item.id), item);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response;
        }
    },
    async create(item) {
        try {
            const response = await axios.post(route('api.users.create'), item);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response;
        }
    },
    async list(params) {
        try {
            const response = await axios.get(route('api.users.list',params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
