export default {
    async fetch(params) {
        try {
            const response = await axios.get(route('api.bank-account-movements.index', params));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async destroy(id) {
        try {
            const response = await axios.delete(route('api.bank-account-movements.destroy', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async edit(id) {
        try {
            const response = await axios.get(route('api.bank-account-movements.edit', id));
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    },
    async update(item) {
        try {
            const response = await axios.put(route('api.bank-account-movements.update', item.id), item);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response;
        }
    },
    async create(item) {
        try {
            const response = await axios.post(route('api.bank-account-movements.create'), item);
            return response.data;
        } catch (error) {
            console.error(error);
            return error.response;
        }
    },
    async massCreate(items) {
        try {
            const response = await axios.post(route('api.bank-account-movements.mass-create'), items);
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
