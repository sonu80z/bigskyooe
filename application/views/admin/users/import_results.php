<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-file-import"></i> Bulk Import Results
                    </h4>
                </div>
                <div class="card-body">
                    
                    <!-- Summary Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="alert alert-info">
                                <h5>Total Records</h5>
                                <h3><?php echo $import_results['total']; ?></h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="alert alert-success">
                                <h5>Successful</h5>
                                <h3><?php echo $import_results['success']; ?></h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="alert alert-danger">
                                <h5>Failed</h5>
                                <h3><?php echo $import_results['failed']; ?></h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="alert alert-warning">
                                <h5>Skipped</h5>
                                <h3><?php echo $import_results['skipped']; ?></h3>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <h5>Import Progress</h5>
                        <div class="progress" style="height: 30px;">
                            <?php 
                            $success_percent = $import_results['total'] > 0 ? ($import_results['success'] / $import_results['total'] * 100) : 0;
                            $failed_percent = $import_results['total'] > 0 ? ($import_results['failed'] / $import_results['total'] * 100) : 0;
                            $skipped_percent = $import_results['total'] > 0 ? ($import_results['skipped'] / $import_results['total'] * 100) : 0;
                            ?>
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $success_percent; ?>%" title="Successful">
                                <?php echo round($success_percent); ?>%
                            </div>
                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $failed_percent; ?>%" title="Failed">
                                <?php echo round($failed_percent); ?>%
                            </div>
                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $skipped_percent; ?>%" title="Skipped">
                                <?php echo round($skipped_percent); ?>%
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Records Table -->
                    <div class="mb-4">
                        <h5>Detailed Record List</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 5%;">Row #</th>
                                        <th style="width: 15%;">Username</th>
                                        <th style="width: 15%;">First Name</th>
                                        <th style="width: 15%;">Last Name</th>
                                        <th style="width: 20%;">Email</th>
                                        <th style="width: 15%;">Status</th>
                                        <th style="width: 15%;">Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($import_results['records'] as $record): ?>
                                        <tr class="<?php 
                                            if (strpos($record['status'], 'SUCCESS') !== false) {
                                                echo 'table-success';
                                            } elseif ($record['status'] === 'FAILED') {
                                                echo 'table-danger';
                                            } elseif ($record['status'] === 'SKIPPED') {
                                                echo 'table-warning';
                                            }
                                        ?>">
                                            <td>
                                                <strong><?php echo $record['row_number']; ?></strong>
                                            </td>
                                            <td>
                                                <code><?php echo htmlspecialchars($record['username']); ?></code>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($record['firstname']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($record['lastname']); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($record['email']); ?>
                                            </td>
                                            <td>
                                                <?php 
                                                $status_class = '';
                                                $status_icon = '';
                                                
                                                if (strpos($record['status'], 'SUCCESS') !== false) {
                                                    $status_class = 'success';
                                                    $status_icon = '<i class="fa fa-check-circle"></i>';
                                                } elseif ($record['status'] === 'FAILED') {
                                                    $status_class = 'danger';
                                                    $status_icon = '<i class="fa fa-times-circle"></i>';
                                                } elseif ($record['status'] === 'SKIPPED') {
                                                    $status_class = 'warning';
                                                    $status_icon = '<i class="fa fa-minus-circle"></i>';
                                                }
                                                ?>
                                                <span class="badge badge-<?php echo $status_class; ?>">
                                                    <?php echo $status_icon; ?> <?php echo $record['status']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small><?php echo htmlspecialchars($record['message']); ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Filter by Status -->
                    <div class="mb-4">
                        <h5>Filters</h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-sm filter-btn" data-filter="all">
                                All (<?php echo $import_results['total']; ?>)
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm filter-btn" data-filter="success">
                                Successful (<?php echo $import_results['success']; ?>)
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm filter-btn" data-filter="failed">
                                Failed (<?php echo $import_results['failed']; ?>)
                            </button>
                            <button type="button" class="btn btn-outline-warning btn-sm filter-btn" data-filter="skipped">
                                Skipped (<?php echo $import_results['skipped']; ?>)
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4">
                        <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-primary">
                            <i class="fa fa-list"></i> Back to Users List
                        </a>
                        <a href="javascript:window.print();" class="btn btn-secondary">
                            <i class="fa fa-print"></i> Print Report
                        </a>
                        <a href="<?php echo base_url('admin/users/import_bulk'); ?>" class="btn btn-info">
                            <i class="fa fa-refresh"></i> Import Again
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .btn-group, .filter-btn {
            display: none;
        }
    }

    .table-success {
        background-color: #d4edda;
    }

    .table-danger {
        background-color: #f8d7da;
    }

    .table-warning {
        background-color: #fff3cd;
    }

    .badge {
        font-size: 11px;
        padding: 5px 8px;
    }
</style>

<script>
    $(document).ready(function() {
        $('.filter-btn').on('click', function() {
            var filter = $(this).data('filter');
            
            $('.filter-btn').removeClass('active').addClass('btn-outline-secondary').removeClass('btn-secondary');
            $(this).addClass('active').removeClass('btn-outline-secondary').addClass('btn-secondary');
            
            if (filter === 'all') {
                $('tbody tr').show();
            } else if (filter === 'success') {
                $('tbody tr').hide();
                $('tbody tr.table-success').show();
            } else if (filter === 'failed') {
                $('tbody tr').hide();
                $('tbody tr.table-danger').show();
            } else if (filter === 'skipped') {
                $('tbody tr').hide();
                $('tbody tr.table-warning').show();
            }
        });
    });
</script>
