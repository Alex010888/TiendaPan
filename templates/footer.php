</main>
</br>
        <footer>

        
           @panaderisonia
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
        <script>
    $(document).ready(function(){
        $("#tabla_id").DataTable({
            "pageLength": 3,
            "lengthMenu": [
                [3, 10, 25, 50],
                [3, 10, 25, 50]
            ],
            "language": {
                "url": 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json'
            }
        });
    });
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const productTable = document.getElementById('productTable');
    const selectedProductsList = document.getElementById('selectedProductsList');
    const totalAmount = document.getElementById('totalAmount');

    let total = 0;

    // Función para actualizar el total a pagar
    function updateTotal(amount) {
        total += amount;
        totalAmount.textContent = `$${total.toFixed(2)}`;
    }

    // Manejar el clic en el botón "Agregar"
    productTable.addEventListener('click', function(event) {
        if (event.target.classList.contains('add-to-list')) {
            event.preventDefault();
            const button = event.target;
            const row = button.closest('tr');
            const quantityInput = row.querySelector('.quantity');
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            const productPrice = parseFloat(button.getAttribute('data-product-price'));
            const quantity = parseInt(quantityInput.value);

            // Calcular el precio total del producto seleccionado
            const totalProductPrice = productPrice * quantity;

            // Crear el elemento de la lista
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            listItem.textContent = `${productName} - $${productPrice.toFixed(2)} x ${quantity} = $${totalProductPrice.toFixed(2)}`;

            // Botón para eliminar el producto de la lista
            const removeButton = document.createElement('button');
            removeButton.className = 'btn btn-danger btn-sm';
            removeButton.textContent = 'Eliminar';
            removeButton.addEventListener('click', function() {
                selectedProductsList.removeChild(listItem);
                updateTotal(-totalProductPrice);
            });

            listItem.appendChild(removeButton);
            selectedProductsList.appendChild(listItem);

            // Actualizar el total
            updateTotal(totalProductPrice);
        }
    });
});
</script>

<script>
    document.getElementById('Foto').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview');
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
</script>

<script>
    function activarCamara() {
            const video = document.getElementById('video');

            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then((stream) => {
                        video.srcObject = stream;
                        video.play();
                    })
                    .catch((error) => {
                        alert('Error al acceder a la cámara: ' + error.message);
                    });
            } else {
                alert('La cámara no es compatible con este navegador.');
            }
        }
    </script>

    </body>
</html>