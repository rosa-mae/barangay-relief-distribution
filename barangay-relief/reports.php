<?php

require_once "config/database.php";

$total = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM beneficiaries"
);

$total_row = mysqli_fetch_assoc($total);
$total_beneficiaries = $total_row['total'];

$received = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS received
FROM beneficiaries
WHERE status = 'Received'"
);

$received_row = mysqli_fetch_assoc($received);
$received_count = $received_row['received'];

$pending = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS pending
FROM beneficiaries
WHERE status = 'Pending'"
);

$pending_row = mysqli_fetch_assoc($pending);
$pending_count = $pending_row['pending'];

$distributions = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM distributions"
);

$distribution_row = mysqli_fetch_assoc($distributions);
$total_distributions = $distribution_row['total'];

$records = mysqli_query(
    $conn,
    "SELECT
distributions.id,
beneficiaries.name,
beneficiaries.purok,
distributions.relief_package,
distributions.quantity,
distributions.date_distributed,
distributions.status
FROM distributions
INNER JOIN beneficiaries
ON distributions.beneficiary_id = beneficiaries.id
ORDER BY distributions.date_distributed DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reports
        Barangay Relief Monitoring System</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <div class="container-fluid">

        <div class="row">

                <?php include "includes/sidebar.php"; ?>

                    <div class="col-md-9 col-lg-10 main-content">

                            <div class="mb-4">
                                <h2>Reports</h2>

                                <p class="text-muted mb-0">
                                    Summary of beneficiaries and relief distributions
                                </p>

                            </div>

                                <div class="row g-4 mb-4">

                                        <div class="col-6 col-xl-3">
                                            <div class="card shadow-sm h-100">

                                                <div class="card-body">

                                                    <h6 class="text-muted">
                                                        Total Beneficiaries
                                                    </h6>

                                                    <h2 class="mt-2">
                                                        <?php echo $total_beneficiaries; ?>
                                                    </h2>

                                                </div>

                                            </div>

                                        </div>

                                            <div class="col-6 col-xl-3">
                                                <div class="card shadow-sm h-100">

                                                    <div class="card-body">

                                                        <h6 class="text-muted">
                                                            Received
                                                        </h6>

                                                        <h2 class="mt-2 text-success">
                                                            <?php echo $received_count; ?>
                                                        </h2>

                                                    </div>

                                                </div>

                                            </div>

                                                <div class="col-6 col-xl-3">
                                                    <div class="card shadow-sm h-100">

                                                        <div class="card-body">

                                                            <h6 class="text-muted">
                                                                Pending
                                                            </h6>

                                                            <h2 class="mt-2 text-warning">
                                                                <?php echo $pending_count; ?>
                                                            </h2>

                                                        </div>

                                                    </div>

                                                </div>

                                                    <div class="col-6 col-xl-3">
                                                        <div class="card shadow-sm h-100">

                                                            <div class="card-body">

                                                                <h6 class="text-muted">
                                                                    Distribution Records
                                                                </h6>

                                                                <h2 class="mt-2">
                                                                    <?php echo $total_distributions; ?>
                                                                </h2>

                                                            </div>

                                                        </div>

                                                    </div>

                                </div>

                                    <div class="card shadow-sm">
                                        <div class="card-body">

                                            <div
                                                class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">

                                                <h5 class="mb-0">
                                                    Distribution Report
                                                </h5>

                                                <button type="button" class="btn btn-primary btn-sm"
                                                    onclick="window.print()">

                                                    Print Report

                                                </button>

                                            </div>


                                            <div class="table-container">

                                                <div class="table-responsive">

                                                    <table class="table table-bordered table-hover">

                                                        <thead class="table-dark">

                                                            <tr>

                                                                <th>ID</th>
                                                                <th>Beneficiary</th>
                                                                <th>Purok</th>
                                                                <th>Relief Package</th>
                                                                <th>Quantity</th>
                                                                <th>Date Distributed</th>
                                                                <th>Status</th>

                                                            </tr>

                                                        </thead>


                                                        <tbody>

                                                            <?php if (mysqli_num_rows($records) > 0) { ?>

                                                                <?php while ($row = mysqli_fetch_assoc($records)) { ?>

                                                                    <tr>

                                                                        <td>
                                                                            <?php echo htmlspecialchars($row['id']); ?>
                                                                        </td>

                                                                        <td>
                                                                            <?php echo htmlspecialchars($row['name']); ?>
                                                                        </td>

                                                                        <td>
                                                                            <?php echo htmlspecialchars($row['purok']); ?>
                                                                        </td>

                                                                        <td>
                                                                            <?php echo htmlspecialchars($row['relief_package']); ?>
                                                                        </td>

                                                                        <td>
                                                                            <?php echo htmlspecialchars($row['quantity']); ?>
                                                                        </td>

                                                                        <td>
                                                                            <?php echo htmlspecialchars($row['date_distributed']); ?>
                                                                        </td>

                                                                        <td>

                                                                            <?php if ($row['status'] == 'Received') { ?>

                                                                                <span class="badge bg-success">
                                                                                    Received
                                                                                </span>

                                                                            <?php } else { ?>

                                                                                <span class="badge bg-warning text-dark">
                                                                                    Pending
                                                                                </span>

                                                                            <?php } ?>

                                                                        </td>

                                                                    </tr>

                                                                <?php } ?>

                                                            <?php } else { ?>

                                                                <tr>

                                                                    <td colspan="7" class="text-center text-muted py-4">

                                                                        No distribution records found.

                                                                    </td>

                                                                </tr>

                                                            <?php } ?>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-muted small mt-3">
                                        Report data is based on the records currently saved in the database.
                                    </p>

                    </div>

        </div>

    </div>

    <style>
        @media print {

            .sidebar,
            .btn {
                display: none !important;
            }

            .main-content {
                width: 100% !important;
                padding: 0 !important;
            }

            body {
                background: white !important;
            }

            .card {
                box-shadow: none !important;
            }
        }
    </style>

</body>

</html>