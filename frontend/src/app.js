import { renderUsers } from './scripts/dom/render.js';
import { createUser } from './scripts/api/create.js';

const apiUrl = 'http://localhost:8000/api/users';

const form = document.getElementById('create-user-form');
const formError = document.getElementById('form-error');

document.addEventListener('DOMContentLoaded', () => renderUsers(apiUrl));

form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const name = document.getElementById('name').value;
    const age = document.getElementById('age').value;
    const email = document.getElementById('email').value;

    hideError();

    try{
        await createUser(apiUrl, {name, age, email});

        form.reset();
        renderUsers(apiUrl);
    } catch (error) {
        showError(error.message);
    }
});

function showError(message) {
    formError.textContent = message;
    formError.classList.remove('d-none');
}

function hideError() {
    formError.classList.add('d-none');
    formError.textContent = '';
}