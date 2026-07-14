import axios from 'axios';

export async function updateProduct(apiUrl, id, {name, category, price, stock}) 
{
    try {
        const response = await axios.put(`${apiUrl}?id=${id}`, { 
            name, 
            category,
            price: Number(price), 
            stock
        });
        return response.data;
    } catch (error) {
        const message = error.response?.data?.error || 'Unable to update product';
        throw new Error(message);
    }
}

export async function patchProduct(apiUrl, id, fields) 
{
    if (fields.price !== undefined) {
        fields.price = Number(fields.price);
    }

    try {
        const response = await axios.patch(`${apiUrl}?id=${id}`, fields);
        return response.data;
    } catch (error) {
        const message = error.response?.data?.error || 'Unable to create product';
        throw new Error(message);
    }
}