<!DOCTYPE html>
<?php include 'header.php'; ?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SayHello</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
            padding-bottom: 50px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .gallery-container {
            padding: 20px 0;
            flex: 1;
        }
        
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        
        .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: all 0.3s ease;
        }
        
        .card-body {
            padding: 15px;
            background-color: white;
        }
        
        .card-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .card-text {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .btn-view {
            background-color: #007bff;
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-view:hover {
            background-color: #0056b3;
	    transform: scale(1.05);
	    color: #ffffff;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-delete:hover {
            background-color: #bd2130;
            transform: scale(1.05);
	    color: #ffffff;
        }
        
        .button-group {
            display: flex;
            gap: 8px;
        }
        
        .header {
            background-color: #666666;
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-weight: 700;
        }
        
        .header p {
            opacity: 0.8;
        }
        
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }
        
        .modal-body {
            padding: 0;
        }
        
        .modal-img {
            width: 100%;
        }
        
        .modal-footer {
            justify-content: space-between;
        }
        
        .image-info {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .loading {
            text-align: center;
            padding: 50px;
            font-size: 1.2rem;
            color: #6c757d;
        }
        
        .no-images {
            text-align: center;
            padding: 50px;
            font-size: 1.2rem;
            color: #6c757d;
        }
        
        .filters {
            margin-bottom: 20px;
        }
        
        .footer {
            background-color: #343a40;
            color: white;
            padding: 15px 0;
            text-align: center;
            margin-top: 30px;
        }
        
        .footer a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: #ff9900;
        }
        
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1060;
        }
        
        @media (max-width: 768px) {
            .card-img-top {
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header text-center">
        <div class="container"><br><br>
            <h1><i class="fas fa-images me-2"></i> Captures</h1>
            <p>View all images from the "captures" folder</p>
        </div>
    </div>
    
    <div class="container gallery-container">
        <!-- Filters -->
        <div class="row filters">
            <div class="col-md-8 mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="search-input" class="form-control" placeholder="Search by filename...">
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <select id="sort-by" class="form-select">
                    <option value="name-asc">Name (A-Z)</option>
                    <option value="name-desc">Name (Z-A)</option>
                    <option value="date-newest">Date (Newest first)</option>
                    <option value="date-oldest">Date (Oldest first)</option>
                </select>
            </div>
        </div>
        
        <!-- Loading indicator -->
        <div id="loading" class="loading">
            <i class="fas fa-spinner fa-spin me-2"></i> Loading images...
        </div>
        
        <!-- No images message -->
        <div id="no-images" class="no-images" style="display: none;">
            <i class="fas fa-exclamation-circle me-2"></i> No images found in the "captures" folder.
        </div>
        
        <!-- Gallery -->
        <div id="gallery" class="row" style="display: none;"></div>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <a href="https://github.com/100security/sayhello" target="_blank">
                <i class="fab fa-github me-2"></i>github.com/100security/sayhello
            </a>
        </div>
    </footer>
    
    <!-- Toast notifications -->
    <div class="toast-container"></div>
    
    <!-- Modal for image viewing -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="" id="modal-image" class="modal-img">
                </div>
                <div class="modal-footer">
                    <div class="image-info">
                        <span id="image-name"></span>
                        <span id="image-dimensions"></span>
                        <span id="image-date"></span>
                    </div>
                    <div>
                        <a href="#" id="download-link" class="btn btn-primary" download>
                            <i class="fas fa-download me-1"></i> Download
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete confirmation modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Are you sure you want to delete this image?</p>
                    <p class="text-danger"><strong id="delete-image-name"></strong></p>
                    <p>This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">
                        <i class="fas fa-trash-alt me-1"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load images from the PHP script
            fetch('list_images.php')
                .then(response => response.json())
                .then(images => {
                    // Hide loading indicator
                    document.getElementById('loading').style.display = 'none';
                    
                    if (images.length > 0) {
                        // Show gallery
                        document.getElementById('gallery').style.display = 'flex';
                        
                        // Render images
                        renderImages(images);
                        
                        // Setup filters
                        setupFilters(images);
                    } else {
                        // Show "no images" message
                        document.getElementById('no-images').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error loading images:', error);
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('no-images').style.display = 'block';
                    document.getElementById('no-images').innerHTML = `
                        <i class="fas fa-exclamation-triangle me-2"></i> 
                        Error loading images. Please check if the list_images.php file is configured correctly.
                    `;
                });
                
            // Setup delete confirmation
            setupDeleteConfirmation();
        });
        
        function renderImages(images) {
            const gallery = document.getElementById('gallery');
            gallery.innerHTML = '';
            
            images.forEach(image => {
                // Create a card for each image
                const card = document.createElement('div');
                card.className = 'col-lg-3 col-md-4 col-sm-6';
                
                // Format the date
                const formattedDate = new Date(image.date * 1000).toLocaleDateString('en-US');
                
                card.innerHTML = `
                    <div class="card">
                        <img src="../captures/${image.name}" class="card-img-top" alt="${image.name}">
                        <div class="card-body">
                            <h5 class="card-title text-center">${image.name}</h5>
                            <p class="card-text text-center">
                                <small>
                                    <i class="far fa-calendar-alt me-1"></i> ${formattedDate}
                                </small>
                            </p>
                            <div class="button-group">
                                <button class="btn btn-view btn-sm flex-grow-1" data-image="../captures/${image.name}" data-name="${image.name}" data-date="${formattedDate}">
                                    <i class="fas fa-eye me-1"></i> View
                                </button>
                                <button class="btn btn-delete btn-sm flex-grow-1" data-image="${image.name}">
                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                gallery.appendChild(card);
            });
            
            // Setup view buttons
            document.querySelectorAll('.btn-view').forEach(button => {
                button.addEventListener('click', function() {
                    const imagePath = this.getAttribute('data-image');
                    const imageName = this.getAttribute('data-name');
                    const imageDate = this.getAttribute('data-date');
                    
                    // Fill the modal
                    document.getElementById('modal-image').src = imagePath;
                    document.getElementById('image-name').textContent = imageName;
                    document.getElementById('image-date').textContent = ` | ${imageDate}`;
                    document.getElementById('download-link').href = imagePath;
                    document.getElementById('download-link').download = imageName;
                    
                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
                    modal.show();
                    
                    // Get image dimensions when it loads
                    const modalImage = document.getElementById('modal-image');
                    modalImage.onload = function() {
                        document.getElementById('image-dimensions').textContent = ` | ${this.naturalWidth} x ${this.naturalHeight}px`;
                    };
                });
            });
            
            // Setup delete buttons
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const imageName = this.getAttribute('data-image');
                    
                    // Set the image name in the delete confirmation modal
                    document.getElementById('delete-image-name').textContent = imageName;
                    
                    // Store the image name as a data attribute on the confirm button
                    document.getElementById('confirm-delete').setAttribute('data-image', imageName);
                    
                    // Show the delete confirmation modal
                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    deleteModal.show();
                });
            });
        }
        
        function setupFilters(allImages) {
            const searchInput = document.getElementById('search-input');
            const sortBy = document.getElementById('sort-by');
            
            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase();
                const sortOption = sortBy.value;
                
                // Filter images
                let filteredImages = allImages.filter(image => {
                    // Filter by search term
                    return image.name.toLowerCase().includes(searchTerm);
                });
                
                // Sort images
                filteredImages.sort((a, b) => {
                    switch (sortOption) {
                        case 'name-asc':
                            return a.name.localeCompare(b.name);
                        case 'name-desc':
                            return b.name.localeCompare(a.name);
                        case 'date-newest':
                            return b.date - a.date;
                        case 'date-oldest':
                            return a.date - b.date;
                        default:
                            return 0;
                    }
                });
                
                // Render filtered images
                renderImages(filteredImages);
                
                // Show message if no results
                if (filteredImages.length === 0) {
                    document.getElementById('gallery').innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-search me-2"></i> No images found matching your search criteria.
                        </div>
                    `;
                }
            }
            
            // Setup filter events
            searchInput.addEventListener('input', applyFilters);
            sortBy.addEventListener('change', applyFilters);
        }
        
        function setupDeleteConfirmation() {
            document.getElementById('confirm-delete').addEventListener('click', function() {
                const imageName = this.getAttribute('data-image');
                
                // Send delete request to the server
                fetch('delete_image.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `image=${encodeURIComponent(imageName)}`
                })
                .then(response => response.json())
                .then(data => {
                    // Hide the delete modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                    deleteModal.hide();
                    
                    if (data.success) {
                        // Show success message
                        showToast('Success', `Image "${imageName}" was deleted successfully.`, 'success');
                        
                        // Reload images
                        reloadImages();
                    } else {
                        // Show error message
                        showToast('Error', data.message || 'Failed to delete the image.', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error deleting image:', error);
                    
                    // Hide the delete modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                    deleteModal.hide();
                    
                    // Show error message
                    showToast('Error', 'An error occurred while trying to delete the image.', 'danger');
                });
            });
        }
        
        function reloadImages() {
            // Show loading indicator
            document.getElementById('loading').style.display = 'block';
            document.getElementById('gallery').style.display = 'none';
            
            // Reload images from the server
            fetch('list_images.php')
                .then(response => response.json())
                .then(images => {
                    // Hide loading indicator
                    document.getElementById('loading').style.display = 'none';
                    
                    if (images.length > 0) {
                        // Show gallery
                        document.getElementById('gallery').style.display = 'flex';
                        
                        // Render images
                        renderImages(images);
                        
                        // Reset filters
                        document.getElementById('search-input').value = '';
                    } else {
                        // Show "no images" message
                        document.getElementById('no-images').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error reloading images:', error);
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('no-images').style.display = 'block';
                    document.getElementById('no-images').innerHTML = `
                        <i class="fas fa-exclamation-triangle me-2"></i> 
                        Error loading images. Please check if the list_images.php file is configured correctly.
                    `;
                });
        }
        
        function showToast(title, message, type) {
            const toastContainer = document.querySelector('.toast-container');
            
            const toastEl = document.createElement('div');
            toastEl.className = `toast align-items-center text-white bg-${type} border-0`;
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');
            
            toastEl.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}:</strong> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            toastContainer.appendChild(toastEl);
            
            const toast = new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 5000
            });
            
            toast.show();
            
            // Remove the toast element after it's hidden
            toastEl.addEventListener('hidden.bs.toast', function() {
                toastContainer.removeChild(toastEl);
            });
        }
    </script>
</body>
</html>
