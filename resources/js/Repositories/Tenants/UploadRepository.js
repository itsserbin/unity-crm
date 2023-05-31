export default {
    async uploadImage(params) {
        try {
            const response = await axios.post(route('api.upload.product-preview'), params, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            return response.data;
        } catch (error) {
            throw new Error(error);
        }
    }
}
