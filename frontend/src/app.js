import { findProductById, renderProducts } from './scripts/dom/render.js';
import { createProduct } from './scripts/api/create.js';
import { deleteProduct } from './scripts/api/delete.js';
import { patchProduct, updateProduct } from './scripts/api/update.js';

const apiUrl = 'http://localhost:8000/api/products';

const form = document.getElementById('create-product-form');
const formError = document.getElementById('form-error');
const productsSection = document.getElementById('products');

document.addEventListener('DOMContentLoaded', () => renderProducts(apiUrl));

productsSection.addEventListener('click', async (event) => {
    const { target } = event;

    if(target.dataset.action === 'edit') {
        enterEditMode(getProductFromCard(target));
    }

    if(target.dataset.action === 'delete') {
        const product = getProductFromCard(target);

        if(!confirm('Are you sure you want to delete this product?')) return;

        try {
            await deleteProduct(apiUrl, product.id);

            if (editingId === product.id) exitEditMode();

            renderProducts(apiUrl);
        } catch (error) {
            showError(error.message);
        }
    }
});


form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const name = document.getElementById('name').value;
    const category = document.getElementById('category').value;
    const price = document.getElementById('price').value;
    const stock = document.getElementById('stock').value;

    hideError();

    try {
        const changed = {}
        if(editingId !== null) {
            if (name !== originalProduct.name) changed.name = name;
            if (category !== originalProduct.category) changed.category = category;
            if (Number(price) !== originalProduct.price) changed.price = price;
            if (stock !== originalProduct.stock) changed.stock = stock;

            if (Object.keys(changed).length === 0) {
                exitEditMode();
                return;
            }

            const allChanged = Object.keys(changed).length === 4;

            if (allChanged) {
                await updateProduct(apiUrl, editingId, { name, category, price, stock });
            } else {
                await patchProduct(apiUrl, editingId, changed);
            }
        } else {
            await createProduct(apiUrl, { name, category, price, stock });
        }

        form.reset();
        renderProducts(apiUrl);
    } catch (error) {
        showError(error.message);
    }
});

const formTitle = document.getElementById('form-title');
const submitBtn = form.querySelector('button[type="submit"]');
const cancelBtn = document.getElementById('cancel-edit');

let editingId = null;
let originalProduct = null;

function enterEditMode(product) 
{
    editingId = product.id;
    originalProduct = { ...product };

    document.getElementById('name').value = product.name;
    document.getElementById('category').value = product.category;
    document.getElementById('price').value = product.price;
    document.getElementById('stock').value = product.stock;

    formTitle.textContent = 'Edit product';
    submitBtn.textContent = 'Update';
    cancelBtn.style.display = '';

    document.getElementById('name').focus();
}

function exitEditMode() 
{
    editingId = null;
    originalProduct = null;
    formTitle.textContent = 'Create Product';
    submitBtn.textContent = 'Create';
    cancelBtn.style.display = 'none';
    form.reset();
}

cancelBtn.addEventListener('click', exitEditMode);

function showError(message) 
{
    formError.textContent = message;
    formError.classList.remove('d-none');
}

function hideError() 
{
    formError.classList.add('d-none');
    formError.textContent = '';
}

function getProductFromCard(button) 
{
    const card = button.closest('.product-card');
    return findProductById(Number(card.id));
}