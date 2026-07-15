import axios from 'axios';

export async function getProducts(apiUrl, search = "") 
{
    try {
        const response = await axios.get(apiUrl, {
            params: search ? {search: search} : {}
        });
        return response.data.products;
    } catch (error) {
        const message = error.response?.data?.error || 'Failed to load products';
        throw new Error(message);
    }
}