import axios from axios;

export async function updateUser(apiUrl, id, {name, age, email}) {
    try {
    const response = await axios.put(`${apiUrl}?id=${id}`, { 
        name, 
        age: Number(age), 
        email
    });
    } catch (error) {
        const message = error.response?.data?.error || 'Unable to update product';
        throw new Error(message);
    }
}

export async function patchUser(apiUrl, id, fields) {
    if (fields.age !== undefined) {
        fields.age = Number(fields.age);
    }

    try {
        const response = await axios.patch(`${apiUrl}?id=${id}`, fields);
        return response.data;
    } catch (error) {
        const message = error.response?.data?.error || 'Unable to create product';
        throw new Error(message);
    }
}