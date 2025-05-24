<?php
include 'header.php';
// Credenciais do Azure Maps
$AZURE_MAPS_KEY = 'PRIMARY-KEY';
$AZURE_MAPS_CLIENT_ID = 'CLIENT-ID';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Azure Maps CSS -->
    <link rel="stylesheet" href="https://atlas.microsoft.com/sdk/javascript/mapcontrol/3/atlas.min.css" type="text/css">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #343a40;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .navbar-brand {
            color: white;
            font-weight: 700;
        }

        .content-container {
            display: flex;
            flex: 1;
            margin-top: 20px;
            overflow: hidden;
        }

        #map {
            flex: 1;
            height: 100%;
        }

        .sidebar {
            width: 450px;
            background-color: #f8f9fa;
            padding: 20px;
            overflow-y: auto;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }

        .locations-container {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .sidebar-footer {
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
        }

        .btn-export {
            width: 100%;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-export:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .btn-export i {
            margin-right: 8px;
        }

        .location-card {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .location-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .location-card.active {
            border-left: 4px solid #007bff;
        }

        .location-time {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .location-coords {
            font-family: monospace;
            font-size: 0.9rem;
            margin-top: 5px;
            color: #495057;
        }

        .location-details {
            margin-top: 10px;
            font-size: 0.85rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .detail-label {
            font-weight: 600;
            color: #6c757d;
        }

        .detail-value {
            text-align: right;
        }

        .btn-refresh {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .btn-refresh:hover {
            transform: rotate(180deg);
            background-color: #0056b3;
        }

        .no-data {
            text-align: center;
            padding: 50px 20px;
            color: #6c757d;
        }

        .no-data i {
            font-size: 3rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        /* Card actions row */
        .card-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
        
        .btn-view {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-view:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        
        .btn-locate {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-locate:hover {
            background-color: #0069d9;
            transform: translateY(-2px);
        }
        
        /* Toast notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
        
        .toast {
            background-color: #343a40;
            color: white;
        }
        
        /* Modal styles */
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        /* Estilo para aumentar a largura do modal */
        #wideModal {
            max-width: 800px;
            width: 100%;
        }
        
        @media (max-width: 850px) {
            #wideModal {
                max-width: 95%;
            }
        }
        
        .modal-header {
            background-color: #343a40;
            color: white;
            border-bottom: none;
            padding: 1.5rem;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            border-top: none;
            padding: 1.5rem;
        }
        
        .detail-table {
            width: 100%;
            margin-bottom: 0;
        }
        
        .detail-table td {
            padding: 10px 5px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .detail-table tr:last-child td {
            border-bottom: none;
        }
        
        .detail-name {
            font-weight: 600;
            color: #495057;
            width: 40%;
        }
        
        .detail-value {
            color: #212529;
            font-family: monospace;
        }
        
        .map-preview {
            width: 100%;
            height: 300px; /* Aumentado para aproveitar o espaço extra */
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .content-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: 200px;
            }

            #map {
                height: calc(100% - 200px);
            }
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
	/* Estilos para o popup do Azure Maps */
	.popup-content {
	    padding: 10px;
	    max-width: 500px;
	    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
	}

	.popup-content h4 {
	    margin-top: 0;
	    margin-bottom: 10px;
	    font-size: 16px;
	    font-weight: 600;
	    color: #343a40;
	    border-bottom: 1px solid #dee2e6;
	    padding-bottom: 8px;
	}

	.popup-content p {
	    margin: 5px 0;
	    font-size: 14px;
	    line-height: 1.4;
	    color: #495057;
	}

	.popup-content strong {
	    font-weight: 600;
	    color: #212529;
	}

	/* Ajustes para o popup do Azure Maps */
	.atlas-popup .atlas-popup-content {
	    padding: 0 !important;
	    background-color: white;
	    border-radius: 8px;
	    overflow: hidden;
	    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
	}

	.atlas-popup .atlas-popup-tip {
	    background-color: white;
	}

	.atlas-popup-close {
	    color: #495057;
	    font-size: 16px;
	    top: 10px;
	    right: 10px;
	}

	.atlas-popup-close:hover {
	    color: #212529;
	    background-color: #f8f9fa;
	}
    </style>

</head>
    <div class="content-container">
        <div class="sidebar">
            <div class="locations-container" id="locationsList">
                <!-- Location entries will be inserted here -->
                <div class="no-data" id="noDataMessage">
                    <i class="bi bi-geo-alt"></i>
                    <h5>No Location Data</h5>
                    <p>No geolocation data has been captured yet.</p>
                </div>
            </div>
            
            <!-- Export button in sidebar footer -->
            <div class="sidebar-footer">
                <button id="exportBtn" class="btn-export">
                    <i class="bi bi-file-earmark-arrow-down"></i> Export to CSV
                </button>
            </div>
        </div>
        <div id="map"></div>
    </div>

    <!-- Location Details Modal -->
    <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" id="wideModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="locationModalLabel">Location Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="detail-table">
                        <tr>
                            <td class="detail-name">Timestamp</td>
                            <td class="detail-value" id="modal-timestamp">-</td>
                        </tr>
                        <tr>
                            <td class="detail-name">IP Address</td>
                            <td class="detail-value" id="modal-ip">-</td>
                        </tr>
                        <tr>
                            <td class="detail-name">Latitude</td>
                            <td class="detail-value" id="modal-latitude">-</td>
                        </tr>
                        <tr>
                            <td class="detail-name">Longitude</td>
                            <td class="detail-value" id="modal-longitude">-</td>
                        </tr>
                        <tr>
                            <td class="detail-name">Accuracy</td>
                            <td class="detail-value" id="modal-accuracy">-</td>
                        </tr>
                        <tr>
                            <td class="detail-name">Device Type</td>
                            <td class="detail-value" id="modal-device">-</td>
                        </tr>
                        <tr>
                            <td class="detail-name">Platform</td>
                            <td class="detail-value" id="modal-platform">-</td>
                        </tr>
                        <tr>
                            <td class="detail-name">Browser</td>
                            <td class="detail-value" id="modal-browser">-</td>
                        </tr>
                        <tr>
                            <td class="detail-name">Language</td>
                            <td class="detail-value" id="modal-language">-</td>
                        </tr>
                        <tr>
                            <td class="detail-name">Screen Resolution</td>
                            <td class="detail-value" id="modal-resolution">-</td>
                        </tr>
                    </table>
                    
                    <div class="map-preview" id="modal-map-preview"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast container for notifications -->
    <div class="toast-container"></div>

    <button class="btn-refresh" id="refreshBtn" title="Refresh Data">
        <i class="bi bi-arrow-clockwise"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Azure Maps SDK -->
    <script src="https://atlas.microsoft.com/sdk/javascript/mapcontrol/3/atlas.min.js"></script>

    <script>
        // Azure Maps credentials
        const AZURE_MAPS_KEY = '<?php echo $AZURE_MAPS_KEY; ?>';
        const AZURE_MAPS_CLIENT_ID = '<?php echo $AZURE_MAPS_CLIENT_ID; ?>';

        // Array to store location data
        let locationData = [];
        let map;
        let datasource;
        let popup;
        let markers = [];
        let modalMap; // Map for the modal preview

        // Function to load geolocation data
        function loadGeolocationData() {
            $.ajax({
                url: 'get_geolocation_data.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success && data.locations.length > 0) {
                        locationData = data.locations;
                        renderLocationsList();
                        if (map) {
                            renderMapMarkers();
                        }
                        $('#noDataMessage').hide();
                        $('#exportBtn').prop('disabled', false);
                    } else {
                        $('#locationsList').html('');
                        $('#noDataMessage').show();
                        $('#exportBtn').prop('disabled', true);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading geolocation data:', error);
                    $('#locationsList').html(`
                        <div class="no-data">
                            <i class="bi bi-exclamation-triangle"></i>
                            <h5>Error Loading Data</h5>
                            <p>Could not load geolocation data. Please try again later.</p>
                        </div>
                    `);
                    $('#exportBtn').prop('disabled', true);
                }
            });
        }

        // Function to render the locations list
        function renderLocationsList() {
            const container = $('#locationsList');
            container.html('');

            locationData.forEach((location, index) => {
                const date = new Date(location.timestamp);
                const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();

                const card = $(`
                    <div class="location-card" data-index="${index}">
                        <div class="location-time">${formattedDate}</div>
                        <div class="location-coords">
                            <i class="bi bi-geo-alt-fill me-1"></i>
                            ${parseFloat(location.latitude).toFixed(6)}, ${parseFloat(location.longitude).toFixed(6)}
                        </div>
                        <div class="location-details">
                            <div class="detail-item">
                                <span class="detail-label">IP Address:</span>
                                <span class="detail-value">${location.ip}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Device:</span>
                                <span class="detail-value">${location.deviceType}</span>
                            </div>
                        </div>
                        <div class="card-actions">
                            <button class="btn-locate" data-index="${index}">
                                <i class="bi bi-geo-alt me-1"></i> Locate
                            </button>
                            <button class="btn-view" data-index="${index}">
                                <i class="bi bi-eye me-1"></i> View Details
                            </button>
                        </div>
                    </div>
                `);

                // Set up locate button
                card.find('.btn-locate').on('click', function(e) {
                    e.stopPropagation();
                    const idx = $(this).data('index');
                    highlightLocation(idx);
                });
                
                // Set up view details button
                card.find('.btn-view').on('click', function(e) {
                    e.stopPropagation();
                    const idx = $(this).data('index');
                    showLocationDetails(idx);
                });

                container.append(card);
            });
        }

        // Function to highlight a location on the map
        function highlightLocation(index) {
            $('.location-card').removeClass('active');
            $(`.location-card[data-index="${index}"]`).addClass('active');

            const location = locationData[index];
            
            // Center the map on the location
            map.setCamera({
                center: [parseFloat(location.longitude), parseFloat(location.latitude)],
                zoom: 15
            });

            // Show popup for this location
            if (popup) {
                const date = new Date(location.timestamp);
                const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
                
                popup.setOptions({
                    position: [parseFloat(location.longitude), parseFloat(location.latitude)],
                    content: `
                        <div class="popup-content">
                            <p><strong>Date/Time:</strong> ${formattedDate}</p>
                            <p><strong>IP Address:</strong> ${location.ip}</p>
                            <p><strong>Device:</strong> ${location.deviceType}</p>
                            <p><strong>Coordinates:</strong> ${location.latitude}, ${location.longitude}</p>
                        </div>
                    `
                });
                
                popup.open(map);
            }
        }
        
        // Function to show location details in modal
        function showLocationDetails(index) {
            const location = locationData[index];
            const date = new Date(location.timestamp);
            const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
            
            // Fill modal with location details
            $('#modal-timestamp').text(formattedDate);
            $('#modal-ip').text(location.ip || '-');
            $('#modal-latitude').text(parseFloat(location.latitude).toFixed(6));
            $('#modal-longitude').text(parseFloat(location.longitude).toFixed(6));
            $('#modal-accuracy').text((location.accuracy || '-') + (location.accuracy ? ' meters' : ''));
            $('#modal-device').text(location.deviceType || '-');
            $('#modal-platform').text(location.platform || '-');
            $('#modal-browser').text(location.browser ? location.browser.substring(0, 100) + '...' : '-');
            $('#modal-language').text(location.language || '-');
            $('#modal-resolution').text(location.screenResolution || '-');
            
            // Show the modal
            const locationModal = new bootstrap.Modal(document.getElementById('locationModal'));
            locationModal.show();
            
            // Initialize the mini map in the modal after it's shown
            $('#locationModal').on('shown.bs.modal', function() {
                initModalMap(location.latitude, location.longitude);
            });
        }
        
        // Function to initialize the mini map in the modal
        function initModalMap(lat, lng) {
            if (modalMap) {
                modalMap.dispose();
            }
            
            // Initialize the Azure Maps in the modal
            modalMap = new atlas.Map('modal-map-preview', {
                center: [parseFloat(lng), parseFloat(lat)],
                zoom: 14,
                language: 'en-US',
                authOptions: {
                    authType: 'subscriptionKey',
                    subscriptionKey: AZURE_MAPS_KEY
                },
                style: 'road',
                showFeedbackLink: false,
                showLogo: false
            });

            modalMap.events.add('ready', function() {
                // Add a marker at the location
                const dataSource = new atlas.source.DataSource();
                modalMap.sources.add(dataSource);

                // Add the marker
                dataSource.add(new atlas.data.Feature(
                    new atlas.data.Point([parseFloat(lng), parseFloat(lat)])
                ));

                // Add a layer for rendering point data as symbols
                modalMap.layers.add(new atlas.layer.SymbolLayer(dataSource, null, {
                    iconOptions: {
                        image: 'marker-red',
                        anchor: 'center',
                        size: 0.8
                    }
                }));
            });
        }

        // Function to initialize the main map
        function initializeMap() {
            // Initialize the Azure Maps
            map = new atlas.Map('map', {
                center: [0, 0],
                zoom: 2,
                language: 'en-US',
                authOptions: {
                    authType: 'subscriptionKey',
                    subscriptionKey: AZURE_MAPS_KEY
                },
                style: 'road'
            });

            // Wait until the map resources are ready
            map.events.add('ready', function() {
                // Create a data source and add it to the map
                datasource = new atlas.source.DataSource();
                map.sources.add(datasource);

                // Create a symbol layer to render point data
                const symbolLayer = new atlas.layer.SymbolLayer(datasource, null, {
                    iconOptions: {
                        image: 'marker-red',
                        anchor: 'center',
                        allowOverlap: true
                    }
                });
                
                map.layers.add(symbolLayer);

                // Create a popup but leave it closed
                popup = new atlas.Popup({
                    pixelOffset: [0, -30],
                    closeButton: true
                });

                // Add click event to the symbol layer
                map.events.add('click', symbolLayer, function(e) {
                    // Check if a feature was clicked
                    if (e.shapes && e.shapes.length > 0) {
                        const properties = e.shapes[0].getProperties();
                        if (properties && properties.index !== undefined) {
                            highlightLocation(properties.index);
                        }
                    }
                });

                // Render markers if we have data
                if (locationData.length > 0) {
                    renderMapMarkers();
                }
            });
        }

        // Function to render markers on the map
        function renderMapMarkers() {
            if (!map || !datasource) return;
            
            // Clear existing data
            datasource.clear();
            
            // If we have locations, center the map on the most recent one
            if (locationData.length > 0) {
                const mostRecent = locationData[0];
                map.setCamera({
                    center: [parseFloat(mostRecent.longitude), parseFloat(mostRecent.latitude)],
                    zoom: 12
                });
            }

            // Add points for each location
            locationData.forEach((location, index) => {
                // Create a point feature
                const point = new atlas.data.Feature(
                    new atlas.data.Point([parseFloat(location.longitude), parseFloat(location.latitude)]),
                    {
                        title: `Location ${index + 1}`,
                        description: new Date(location.timestamp).toLocaleDateString(),
                        index: index // Store the index for reference
                    }
                );
                
                // Add the point to the datasource
                datasource.add(point);
            });
        }
        
        // Function to export data to CSV
        function exportToCSV() {
            if (locationData.length === 0) {
                showToast('No Data', 'There is no geolocation data to export.', 'warning');
                return;
            }
            
            $.ajax({
                url: 'export_geolocation.php',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        // Create a download link
                        const link = document.createElement('a');
                        link.href = response.file;
                        link.download = 'report_geolocation.csv';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        
                        showToast('Export Successful', 'Geolocation data has been exported to CSV.', 'success');
                    } else {
                        showToast('Export Failed', response.message, 'danger');
                    }
                },
                error: function() {
                    showToast('Export Failed', 'An error occurred while exporting the data.', 'danger');
                }
            });
        }
        
        // Function to show toast notification
        function showToast(title, message, type) {
            const toastContainer = $('.toast-container');
            
            const toast = $(`
                <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                    <div class="toast-header bg-${type} text-white">
                        <strong class="me-auto">${title}</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `);
            
            toastContainer.append(toast);
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Remove toast after it's hidden
            toast.on('hidden.bs.toast', function() {
                toast.remove();
            });
        }

        // Initialize on document ready
        $(document).ready(function() {
            // Initialize the map
            initializeMap();
            
            // Load geolocation data
            loadGeolocationData();

            // Set up refresh button
            $('#refreshBtn').on('click', function() {
                loadGeolocationData();
            });
            
            // Set up export button
            $('#exportBtn').on('click', function() {
                exportToCSV();
            });
            
            // Clean up modal map when modal is hidden
            $('#locationModal').on('hidden.bs.modal', function() {
                if (modalMap) {
                    modalMap.dispose();
                    modalMap = null;
                }
            });
        });
    </script>
</body>
</html>
