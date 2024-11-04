function productDetail(){
      // Get the product ID from the URL
      const urlParams = new URLSearchParams(window.location.search);
      const productId = urlParams.get('id');

      document.addEventListener('DOMContentLoaded', () => {
          // Fetch product details from server
          fetch(`../controller/productDetail.php?id=${productId}`)
              .then(response => response.json())
              .then(data => {
                  const productDetailContainer = document.getElementById('productDetail');
                  
                  // Populate product details
                  productDetailContainer.innerHTML = `
                  <div class="product-container">
                        <div class="product-container-image">
                      <img src="../src/${data.product_image}" alt="${data.product_name}" class="product-image">
                      </div>
                      <div class="product-details">
                      <h2 class="product-details-name">${data.product_name}</h2>
                    
                      </div>

                      </div>
                  `;
              })
              .catch(error => {
                  console.error('Error fetching product details:', error);
              });
      });
}

productDetail();