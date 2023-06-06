export default {
    async fetch(params) {
        try {
            const response = await axios.get(route('api.movement-categories.index', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async destroy(id) {
        try {
            const response = await axios.delete(route('api.movement-categories.destroy', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async edit(id) {
        try {
            const response = await axios.get(route('api.movement-categories.edit', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async update(item) {
        try {
            const response = await axios.put(route('api.movement-categories.update', item.id), item);
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async create(item) {
        try {
            const response = await axios.post(route('api.movement-categories.create'), item);
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async list(val) {
        try {
            const response = await axios.get(route('api.movement-categories.list', val));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
