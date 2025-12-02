@extends('layouts.admin')

@section('title', 'Administración de Productos')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div>
        </div>
        <h1 class="admin-title">Administración de Productos</h1>
        <a href="/home" class="btn-back">
            <i class="fas fa-arrow-left"></i> Volver a la tienda
        </a>
    </div>

    <div class="admin-form-section">
        <!-- Formulario de Agregar/Editar Producto -->
        <div class="form-container">
            <h2 class="form-title">Agregar Nuevo Producto</h2>
            <form id="productForm" enctype="multipart/form-data">
                <input type="hidden" id="productId" name="id">
                <div class="form-row">
                   <div class="form-group">
                        <label for="name">Nombre del Producto</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                   </div>
                    <div class="form-group">
                        <label for="price">Precio ($)</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" required>
                    </div>

                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" id="stock" name="stock" class="form-control" required>
                    </div>
                    <div class="form-group">
                     <label for="image">Imagen del Producto</label>
                     <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    <div id="imagePreview" class="image-preview"></div>
                </div>
                </div>

                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save"></i> Guardar Producto
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>

        <!-- Logo -->
        <div class="logo-container">
            <img src="{{ asset('img/nuevologo.png') }}" alt="Logo" class="admin-logo">
        </div>
    </div>

    <!-- Tabla de Productos -->
    <div class="products-table-section">
        <div class="table-header">
            <div class="search-box">
                <input type="text" id="searchProduct" placeholder="Buscar producto..." class="search-input">
                <i class="fas fa-search"></i>
            </div>
        </div>

        <div class="table-responsive">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody">
                    <!-- Los productos se cargarán aquí dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let editingProductId = null;
let isLoading = false;
let currentImageBase64 = null; // Guardar imagen en base64

// Cargar productos al iniciar
window.onload = function() {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        alert('No estás autenticado. Redirigiendo al login...');
        window.location.href = '/';
        return;
    }
    loadProducts();
};

// Cargar productos desde la API
async function loadProducts() {
    if (isLoading) return; // Evitar múltiples llamadas simultáneas
    isLoading = true;
    
    const authToken = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch('/api/products', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        if (response.status === 401) {
            alert('Sesión expirada. Por favor inicia sesión nuevamente.');
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user_data');
            window.location.href = '/';
            return;
        }
        
        if (!response.ok) {
            console.error('Error en la respuesta:', response.status);
            displayProducts([]);
            isLoading = false;
            return;
        }
        
        const data = await response.json();
        console.log('Productos cargados:', data);
        displayProducts(Array.isArray(data) ? data : []);
    } catch (error) {
        console.error('Error al cargar productos:', error);
        displayProducts([]);
    } finally {
        isLoading = false;
    }
}

// Mostrar productos en la tabla
function displayProducts(products) {
    const tbody = document.getElementById('productsTableBody');
    tbody.innerHTML = '';
    
    if (!products || products.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; padding: 2rem; color: #666;">
                    <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                    <strong>No hay productos registrados</strong>
                    <p style="margin-top: 0.5rem;">Agrega tu primer producto usando el formulario de arriba</p>
                </td>
            </tr>
        `;
        return;
    }
    
    products.forEach(product => {
        const imageSrc = product.image || 'https://via.placeholder.com/60x60?text=No+Image';
        const row = `
            <tr>
                <td>${product.id}</td>
                <td>
                    <img src="${imageSrc}" 
                         alt="${product.name}" 
                         class="product-thumb" 
                         onerror="this.onerror=null; this.src='https://via.placeholder.com/60x60?text=Error';">
                </td>
                <td>${product.name}</td>
                <td>$${parseFloat(product.price).toFixed(2)}</td>
                <td>${product.stock || 0}</td>
                <td class="description-cell">${product.description}</td>
                <td class="actions-cell">
                    <button onclick="editProduct(${product.id})" class="btn-edit" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteProduct(${product.id})" class="btn-delete" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

// Enviar formulario
document.getElementById('productForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const authToken = localStorage.getItem('auth_token');
    
    if (!authToken) {
        alert('No estás autenticado. Por favor inicia sesión.');
        window.location.href = '/';
        return;
    }
    
    const productId = document.getElementById('productId').value;
    const productData = {
        name: document.getElementById('name').value,
        price: parseFloat(document.getElementById('price').value),
        stock: parseInt(document.getElementById('stock').value),
        description: document.getElementById('description').value,
        image: currentImageBase64 // Enviar imagen en base64
    };
    
    console.log('Datos a enviar:', productData);
    
    const url = productId ? `/api/products/${productId}` : '/api/products';
    const method = productId ? 'PUT' : 'POST';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(productData)
        });
        
        console.log('Respuesta del servidor:', response.status);
        
        if (response.status === 401) {
            alert('Sesión expirada. Por favor inicia sesión nuevamente.');
            localStorage.removeItem('auth_token');
            window.location.href = '/';
            return;
        }
        
        const result = await response.json();
        console.log('Resultado:', result);
        
        if (response.ok) {
            alert(result.message || 'Operación exitosa');
            resetForm();
            loadProducts();
        } else {
            alert(result.message || 'Error al guardar el producto');
            console.error('Error:', result);
        }
    } catch (error) {
        console.error('Error completo:', error);
        alert('Error al conectar con el servidor: ' + error.message);
    }
});

// Editar producto
async function editProduct(id) {
    const authToken = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch(`/api/products/${id}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.status === 401) {
            alert('Sesión expirada. Por favor inicia sesión nuevamente.');
            localStorage.removeItem('auth_token');
            window.location.href = '/';
            return;
        }
        
        if (!response.ok) {
            throw new Error('Producto no encontrado');
        }
        
        const product = await response.json();
        
        document.getElementById('productId').value = product.id;
        document.getElementById('name').value = product.name;
        document.getElementById('price').value = product.price;
        document.getElementById('stock').value = product.stock || 0;
        document.getElementById('description').value = product.description;
        
        // Mostrar imagen actual si existe
        if (product.image) {
            currentImageBase64 = product.image;
            document.getElementById('imagePreview').innerHTML = `<img src="${product.image}" alt="Imagen actual" style="max-width: 100%; max-height: 150px; border-radius: 8px;">`;
        }
        
        document.querySelector('.form-title').textContent = 'Editar Producto';
        document.querySelector('.btn-save').innerHTML = '<i class="fas fa-save"></i> Actualizar Producto';
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar el producto');
    }
}

// Eliminar producto
async function deleteProduct(id) {
    if (!confirm('¿Estás seguro de eliminar este producto?')) return;
    
    const authToken = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch(`/api/products/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.status === 401) {
            alert('Sesión expirada. Por favor inicia sesión nuevamente.');
            localStorage.removeItem('auth_token');
            window.location.href = '/';
            return;
        }
        
        const result = await response.json();
        
        if (response.ok) {
            alert(result.message || 'Producto eliminado exitosamente');
            loadProducts();
        } else {
            alert('Error al eliminar el producto');
            console.error('Error:', result);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al eliminar el producto');
    }
}

// Resetear formulario
function resetForm() {
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
    document.getElementById('imagePreview').innerHTML = '';
    currentImageBase64 = null;
    document.querySelector('.form-title').textContent = 'Agregar Nuevo Producto';
    document.querySelector('.btn-save').innerHTML = '<i class="fas fa-save"></i> Guardar Producto';
    
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.value = '';
    }
}

// Buscador
document.getElementById('searchProduct').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#productsTableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Preview de imagen y convertir a base64
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        // Validar tamaño del archivo (máximo 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('La imagen es muy grande. El tamaño máximo es 5MB.');
            this.value = '';
            preview.innerHTML = '';
            currentImageBase64 = null;
            return;
        }
        
        // Validar tipo de archivo
        if (!file.type.match('image.*')) {
            alert('Por favor selecciona un archivo de imagen válido.');
            this.value = '';
            preview.innerHTML = '';
            currentImageBase64 = null;
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            currentImageBase64 = e.target.result; // Guardar base64
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-width: 100%; max-height: 150px; border-radius: 8px;">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
        currentImageBase64 = null;
    }
});
</script>
@endsection
