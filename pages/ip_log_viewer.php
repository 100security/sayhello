<!DOCTYPE html>
<?php include 'header.php'; ?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SayHello</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
            padding-bottom: 50px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .content-container {
            padding: 20px 0;
            flex: 1;
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
        
        .card {
            border-radius: 10px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 15px 20px;
        }
        
        .card-body {
            padding: 20px;
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
        
        .loading {
            text-align: center;
            padding: 50px;
            font-size: 1.2rem;
            color: #6c757d;
        }
        
        .no-data {
            text-align: center;
            padding: 50px;
            font-size: 1.2rem;
            color: #6c757d;
        }
        
        .search-box {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        
        table.dataTable {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        
        .dataTables_wrapper .dataTables_length, 
        .dataTables_wrapper .dataTables_filter, 
        .dataTables_wrapper .dataTables_info, 
        .dataTables_wrapper .dataTables_processing, 
        .dataTables_wrapper .dataTables_paginate {
            padding: 15px;
            color: #495057;
        }
        
        .ip-cell {
            font-family: monospace;
            font-weight: 600;
        }
        
        .date-cell {
            white-space: nowrap;
        }
        
        .badge-private {
            color: #ff0000;
        }
        
        .badge-public {
            color: #0000ff;
        }
        
        .btn-refresh {
            background-color: #17a2b8;
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-refresh:hover {
            background-color: #138496;
            transform: scale(1.05);
            color: white;
        }
        
        .btn-export {
            background-color: #28a745;
            color: white;
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-export:hover {
            background-color: #218838;
            transform: scale(1.05);
            color: white;
        }
        
        .stats-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        
        .stats-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        
        .stats-number {
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        .stats-text {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        /* Custom colors for different IP types */
        .ip-local {
            color: #6c757d;
        }
        
        .ip-public {
            color: #28a745;
        }
        
        /* Toast notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1060;
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


    </style>
</head>
<body>
    <!-- Header -->
    <div class="header text-center">
        <div class="container"><br>
            <h1><i class="fas fa-network-wired me-2"></i>IP Log Viewer</h1>
            <p>View and search captured IP addresses from the log file</p>
        </div>
    </div>
    
    <div class="container content-container">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stats-card text-center h-100">
                    <div class="card-body">
                        <div class="stats-icon text-primary">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stats-number" id="total-records">0</div>
                        <div class="stats-text">Total Records</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card text-center h-100">
                    <div class="card-body">
                        <div class="stats-icon text-success">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div class="stats-number" id="unique-ips">0</div>
                        <div class="stats-text">Unique IP Addresses</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card text-center h-100">
                    <div class="card-body">
                        <div class="stats-icon text-info">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="stats-number" id="unique-dates">0</div>
                        <div class="stats-text">Unique Dates</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stats-card text-center h-100">
                    <div class="card-body">
                        <div class="stats-icon text-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div id="last-update">Never</div>
                        <div class="stats-text">Last Updated</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Advanced Search Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-search me-2"></i>Advanced Search</h5>
                <div>
                    <button id="btn-refresh" class="btn btn-refresh btn-sm me-2">
                        <i class="fas fa-sync-alt me-1"></i> Refresh Data
                    </button>
                    <button id="btn-export" class="btn btn-export btn-sm">
                        <i class="fas fa-file-export me-1"></i> Export CSV
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="date-filter" class="form-label">Date Filter</label>
                        <input type="date" class="form-control" id="date-filter">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="ip-filter" class="form-label">IP Address</label>
                        <input type="text" class="form-control" id="ip-filter" placeholder="e.g. 192.168.0.1">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="ip-type" class="form-label">IP Type</label>
                        <select class="form-select" id="ip-type">
                            <option value="all">All Types</option>
                            <option value="public">Public IPs</option>
                            <option value="private">Private IPs</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button id="btn-clear-filters" class="btn btn-secondary me-2">
                        <i class="fas fa-eraser me-1"></i> Clear Filters
                    </button>
                    <button id="btn-apply-filters" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Data Table Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i>IP Log Data</h5>
            </div>
            <div class="card-body">
                <!-- Loading indicator -->
                <div id="loading" class="loading">
                    <i class="fas fa-spinner fa-spin me-2"></i> Loading log data...
                </div>
                
                <!-- No data message -->
                <div id="no-data" class="no-data" style="display: none;">
                    <i class="fas fa-exclamation-circle me-2"></i> No log data found.
                </div>
                
                <!-- Data table -->
                <div id="data-table-container" style="display: none;">
                    <div class="table-responsive">
                        <table id="ip-log-table" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>IP Address</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table data will be inserted here by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <a href="https://github.com/100security/sayhello" >
                <i class="fab fa-github me-2"></i>github.com/100security/sayhello
            </a>
        </div>
    </footer>
    
    <!-- Toast notifications -->
    <div class="toast-container"></div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize variables
            let ipLogData = [];
            let dataTable;
            
            // Load log data
            loadLogData();
            
            // Set up event listeners
            $('#btn-refresh').click(function() {
                loadLogData();
            });
            
            $('#btn-export').click(function() {
                exportToCSV();
            });
            
            $('#btn-apply-filters').click(function() {
                applyFilters();
            });
            
            $('#btn-clear-filters').click(function() {
                clearFilters();
            });
            
            // Function to load log data
            function loadLogData() {
                // Show loading indicator
                $('#loading').show();
                $('#data-table-container').hide();
                $('#no-data').hide();
                
                // Fetch log data from the server
                fetch('get_ip_log.php')
                    .then(response => response.json())
                    .then(data => {
                        // Hide loading indicator
                        $('#loading').hide();
                        
                        if (data.success && data.entries.length > 0) {
                            // Store the data
                            ipLogData = data.entries;
                            
                            // Update statistics
                            updateStatistics(ipLogData);
                            
                            // Render the data table
                            renderDataTable(ipLogData);
                            
                            // Show the data table
                            $('#data-table-container').show();
                        } else {
                            // Show no data message
                            $('#no-data').show();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading log data:', error);
                        $('#loading').hide();
                        $('#no-data').show();
                        $('#no-data').html(`
                            <i class="fas fa-exclamation-triangle me-2"></i> 
                            Error loading log data. Please check if the get_ip_log.php file is configured correctly.
                        `);
                        showToast('Error', 'Failed to load log data. See console for details.', 'danger');
                    });
            }
            
            // Function to render the data table
            function renderDataTable(data) {
                // Destroy existing DataTable if it exists
                if (dataTable) {
                    dataTable.destroy();
                }
                
                // Clear the table body
                const tableBody = $('#ip-log-table tbody');
                tableBody.empty();
                
                // Add rows to the table
                data.forEach((entry, index) => {
                    const [date, time, ip] = parseLogEntry(entry);
                    const ipType = isPrivateIP(ip) ? 'Private' : 'Public';
                    const ipClass = isPrivateIP(ip) ? 'ip-local' : 'ip-public';
                    const badgeClass = isPrivateIP(ip) ? 'badge-private' : 'badge-public';
                    
                    tableBody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td class="date-cell">${date}</td>
                            <td>${time}</td>
                            <td class="ip-cell ${ipClass}">${ip}</td>
                            <td><span class="ip-cell ${badgeClass}">${ipType}</span></td>
                        </tr>
                    `);
                });
                
                // Initialize DataTable
                dataTable = $('#ip-log-table').DataTable({
                    responsive: true,
                    order: [[1, 'desc'], [2, 'desc']], // Sort by date and time descending
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Quick search...",
                        lengthMenu: "Show _MENU_ entries per page",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "Showing 0 to 0 of 0 entries",
                        infoFiltered: "(filtered from _MAX_ total entries)"
                    }
                });
            }
            
            // Function to update statistics
            function updateStatistics(data) {
                // Calculate total records
                const totalRecords = data.length;
                $('#total-records').text(totalRecords);
                
                // Calculate unique IPs
                const uniqueIPs = new Set(data.map(entry => {
                    const [, , ip] = parseLogEntry(entry);
                    return ip;
                })).size;
                $('#unique-ips').text(uniqueIPs);
                
                // Calculate unique dates
                const uniqueDates = new Set(data.map(entry => {
                    const [date] = parseLogEntry(entry);
                    return date;
                })).size;
                $('#unique-dates').text(uniqueDates);
                
                // Update last updated time
                const now = new Date();
                const formattedTime = now.toLocaleTimeString();
                $('#last-update').html(`<span class="stats-number">${formattedTime}</span>`);
            }
            
            // Function to parse a log entry
            function parseLogEntry(entry) {
                // Expected format: "2025-05-22 08:50:07 - 177.68.91.210"
                const match = entry.match(/^(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2}) - (.+)$/);
                if (match) {
                    return [match[1], match[2], match[3]];
                }
                return ['Unknown', 'Unknown', 'Unknown'];
            }
            
            // Function to check if an IP is private
            function isPrivateIP(ip) {
                // Check for localhost
                if (ip === '127.0.0.1' || ip === 'localhost') {
                    return true;
                }
                
                // Check for private IP ranges
                const parts = ip.split('.');
                if (parts.length !== 4) {
                    return false;
                }
                
                const a = parseInt(parts[0], 10);
                const b = parseInt(parts[1], 10);
                const c = parseInt(parts[2], 10);
                
                // Check for private IP ranges
                // 10.0.0.0 - 10.255.255.255
                if (a === 10) {
                    return true;
                }
                
                // 172.16.0.0 - 172.31.255.255
                if (a === 172 && b >= 16 && b <= 31) {
                    return true;
                }
                
                // 192.168.0.0 - 192.168.255.255
                if (a === 192 && b === 168) {
                    return true;
                }
                
                return false;
            }
            
            // Function to apply filters
            function applyFilters() {
                const dateFilter = $('#date-filter').val();
                const ipFilter = $('#ip-filter').val().trim().toLowerCase();
                const ipTypeFilter = $('#ip-type').val();
                
                // Filter the data
                let filteredData = ipLogData.filter(entry => {
                    const [date, , ip] = parseLogEntry(entry);
                    
                    // Apply date filter
                    if (dateFilter && date !== dateFilter) {
                        return false;
                    }
                    
                    // Apply IP filter
                    if (ipFilter && !ip.toLowerCase().includes(ipFilter)) {
                        return false;
                    }
                    
                    // Apply IP type filter
                    if (ipTypeFilter !== 'all') {
                        const isPrivate = isPrivateIP(ip);
                        if (ipTypeFilter === 'public' && isPrivate) {
                            return false;
                        }
                        if (ipTypeFilter === 'private' && !isPrivate) {
                            return false;
                        }
                    }
                    
                    return true;
                });
                
                // Update the data table with filtered data
                renderDataTable(filteredData);
                
                // Show toast notification
                const count = filteredData.length;
                showToast('Filter Applied', `Showing ${count} ${count === 1 ? 'result' : 'results'} matching your criteria.`, 'info');
            }
            
            // Function to clear filters
            function clearFilters() {
                $('#date-filter').val('');
                $('#ip-filter').val('');
                $('#ip-type').val('all');
                
                // Reset the data table to show all data
                renderDataTable(ipLogData);
                
                // Show toast notification
                showToast('Filters Cleared', 'Showing all log entries.', 'info');
            }
            
            // Function to export data to CSV
            function exportToCSV() {
                // Get the current filtered data from the DataTable
                const data = dataTable.rows().data();
                const csvContent = [];
                
                // Add CSV header
                csvContent.push('Index,Date,Time,IP Address,Type');
                
                // Add data rows
                for (let i = 0; i < data.length; i++) {
                    const rowData = data[i];
                    const csvRow = [
                        rowData[0], // Index
                        rowData[1], // Date
                        rowData[2], // Time
                        rowData[3].replace(/<[^>]*>/g, ''), // IP Address (remove HTML)
                        rowData[4].replace(/<[^>]*>/g, '') // Type (remove HTML)
                    ];
                    csvContent.push(csvRow.join(','));
                }
                
                // Create CSV file
                const csvString = csvContent.join('\n');
                const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                
                // Create download link
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `ip_log_export_${new Date().toISOString().slice(0, 10)}.csv`);
                document.body.appendChild(link);
                
                // Trigger download
                link.click();
                
                // Clean up
                document.body.removeChild(link);
                
                // Show toast notification
                showToast('Export Complete', 'CSV file has been downloaded.', 'success');
            }
            
            // Function to show toast notifications
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
        });
    </script>
</body>
</html>
