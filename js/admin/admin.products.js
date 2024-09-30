 // Function to activate a menu item based on the stored active state
 function setActiveMenuItem() {
    const activeItemId = localStorage.getItem('activeMenuItem');
    if (activeItemId) {
        document.querySelectorAll('.menu-item').forEach(menu => {
            menu.classList.remove('active');
        });
        const selectedMenuItem = document.querySelector(`.menu-item[data-id="${activeItemId}"]`);
        if (selectedMenuItem) {
            selectedMenuItem.classList.add('active');
        }
    }
}

// Event listener to handle menu item clicks
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', () => {
        document.querySelectorAll('.menu-item').forEach(menu => {
            menu.classList.remove('active');
        });
        item.classList.add('active');
        const id = item.getAttribute('data-id');
        localStorage.setItem('activeMenuItem', id);
    });
});

// Call the function to set the active menu item on page load
setActiveMenuItem();



function setCreateProducts(){
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('productForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            fetch('../../controller/products.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product added successfully!');
                } else {
                    alert('Failed to add product');
                }
            });
        });
    });
    
}
setCreateProducts();

function tableFunction(){
    // JavaScript for fetching product data and displaying it in the table
document.addEventListener('DOMContentLoaded', () => {
    fetch('../../controller/products.php')
        .then(response => response.json())
        .then(data => {
            const productTableBody = document.getElementById('productTableBody');
            productTableBody.innerHTML = ''; // Clear any existing rows

            data.forEach(product => {
                const row = document.createElement('tr');

                // Create table cells for each product field
                row.innerHTML = `
                    <td>${product.id}</td>
                    <td>${product.product_name}</td>
                    <td>${product.product_size}</td>
                    <td>${product.product_type}</td>
                    <td>${product.product_category}</td>
                    <td>$${parseFloat(product.product_price).toFixed(2)}</td>
                    <td>${product.product_quantity}</td>
                    <td><img src="../${product.product_image}" </td>
                    <td>${new Date(product.created_at).toLocaleString()}</td>
                `;

                // Append the row to the table body
                productTableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching products:', error); // Handle fetch errors
        });
});

}
tableFunction();