export default {
    async list(params) {
        try {
            const response = await axios.get(route('api.user.tokens.list', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async edit(id) {
        try {
            const response = await axios.get(route('api.user.tokens.edit', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async destroy(id) {
        try {
            const response = await axios.delete(route('api.user.tokens.destroy', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async update(item) {
        try {
            const response = await axios.put(route('api.user.tokens.update', item.id), item);
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async create(item) {
        try {
            const response = await axios.post(route('api.user.tokens.create'), item);
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
