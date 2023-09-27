export default {
    async fetch(params) {
        try {
            const response = await axios.get(route('api.roles.index', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async destroy(id) {
        try {
            const response = await axios.delete(route('api.roles.destroy', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async edit(id) {
        try {
            const response = await axios.get(route('api.roles.edit', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async update(item) {
        try {
            const response = await axios.put(route('api.roles.update', item.id), item);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response;
        }
    },
    async create(item) {
        try {
            const response = await axios.post(route('api.roles.create'), item);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response;
        }
    },
    async list(params) {
        try {
            const response = await axios.get(route('api.roles.list',params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
