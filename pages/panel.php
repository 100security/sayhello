<?php include 'header.php'; ?>

    <style>

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


<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <h1 class="display-4 mb-4">😀 SayHello</h1>
                    <p class="lead mb-4">Welcome to the SayHello dashboard.</p>
                    
                    <div class="row mt-5">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-images fa-3x text-primary mb-3"></i>
                                    <h3>Captures</h3>
                                    <p class="text-muted">View and manage captured images</p>
                                    <a href="captures.php" class="btn btn-outline-primary mt-3">View</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-network-wired fa-3x text-primary mb-3"></i>
                                    <h3>IP Log Viewer</h3>
                                    <p class="text-muted">Analyze captured IP addresses</p>
                                    <a href="ip_log_viewer.php" class="btn btn-outline-primary mt-3">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
