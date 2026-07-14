import axios from 'axios';

export async function createProduct(apiUrl, {name, category, price, stock}) {
    try {
        const response = await axios.put(apiUrl,{
        name,
        category,
        price: Number(price),
        stock
        });

        return response.data;
    } catch (error) {
        const message = error.response?.data?.error || 'Failed to create product';
        throw new Error(message);
    }

}