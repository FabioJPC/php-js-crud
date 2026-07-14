
import { getProducts } from "../api/read.js";

let productsCache = [];

export function findProductById(id) 
{
    return productsCache.find((user) => user.id === id);
}

export async function renderProducts(apiUrl) 
{
    const products = await getProducts(apiUrl);

    productsCache = products;
    const productsSection = document.getElementById('products');

    if(products.length === 0) {
        productsSection.innerHTML = `<p class="text-muted">No products found!</p>`;
        return;
    }

    productsSection.innerHTML = "";

    products.forEach((product) => {
        const productsDiv = document.createElement('div');
        productsDiv.classList.add('col-md-3');

        productsDiv.innerHTML = /*html*/`
            <div class="card product-card h-100" id="${product.id}">
                <div class="card-body">
                    <h5 class="card-title">${product.name}</h5>
                    <p class="card-text mb-1"><strong>Category:</strong> ${product.category}</p>
                    <p class="card-text"><strong>Price:</strong> ${product.price}</p>
                    <p class="cart-text"><strong>Stock:</strong>${product.stock}</p>
                </div>
                <div class="card-footer d-flex gap-2">
                    <button 
                        class="btn btn-sm btn-outline-dark flex-fill" 
                        data-action="edit">Edit
                    </button>
                    <button 
                        class="btn btn-sm btn-outline-danger flex-fill" 
                        data-action="delete">Delete
                    </button>
                </div>
            </div>
        `;

        productsSection.appendChild(productsDiv);

    });
}