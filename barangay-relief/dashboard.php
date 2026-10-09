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

$completed = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS completed
FROM distributions"
);

$completed_row = mysqli_fetch_assoc($completed);

$completed_count = $completed_row['completed'];

$recent = mysqli_query(
    $conn,
    "SELECT
distributions.id,
beneficiaries.name,
beneficiaries.purok,
distributions.date_distributed,
distributions.status
FROM distributions
INNER JOIN beneficiaries
ON distributions.beneficiary_id = beneficiaries.id
ORDER BY distributions.id DESC
LIMIT 5"
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Relief Distribution Monitoring System</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <div class="container-fluid">

        <div class="row">

                <?php include "includes/sidebar.php"; ?>

                    <div class="col-md-9 col-lg-10 main-content">

                            <div class="mb-4">

                                <h2>
                                    Dashboard
                                </h2>

                                <p class="text-muted mb-0">
                                    Barangay Relief Distribution Monitoring System
                                </p>

                            </div>

                                <div class="row g-4">

                                        <div class="col-md-6 col-xl-3">

                                            <div class="card shadow-sm stat-card">

                                                <div class="card-body">

                                                    <h6 class="text-muted">
                                                        Total Families
                                                    </h6>

                                                    <h2 class="mt-2">
                                                        <?php echo $total_beneficiaries; ?>
                                                    </h2>

                                                    <small class="text-muted">
                                                        Registered beneficiaries
                                                    </small>

                                                </div>

                                            </div>

                                        </div>

                                            <div class="col-md-6 col-xl-3">

                                                <div class="card shadow-sm stat-card">

                                                    <div class="card-body">

                                                        <h6 class="text-muted">
                                                            Received
                                                        </h6>

                                                        <h2 class="mt-2 text-success">
                                                            <?php echo $received_count; ?>
                                                        </h2>

                                                        <small class="text-muted">
                                                            Beneficiaries assisted
                                                        </small>

                                                    </div>

                                                </div>

                                            </div>

                                                <div class="col-md-6 col-xl-3">

                                                    <div class="card shadow-sm stat-card">

                                                        <div class="card-body">

                                                            <h6 class="text-muted">
                                                                Pending
                                                            </h6>

                                                            <h2 class="mt-2 text-warning">
                                                                <?php echo $pending_count; ?>
                                                            </h2>

                                                            <small class="text-muted">
                                                                Still awaiting assistance
                                                            </small>

                                                        </div>

                                                    </div>

                                                </div>

                                                    <div class="col-md-6 col-xl-3">

                                                        <div class="card shadow-sm stat-card">

                                                            <div class="card-body">

                                                                <h6 class="text-muted">
                                                                    Distribution Records
                                                                </h6>

                                                                <h2 class="mt-2">
                                                                    <?php echo $completed_count; ?>
                                                                </h2>

                                                                <small class="text-muted">
                                                                    Recorded distributions
                                                                </small>

                                                            </div>

                                                        </div>

                                                    </div>

                                </div>

                                    <div class="card shadow-sm table-card">

                                        <div class="card-body">


                                            <div class="d-flex justify-content-between align-items-center mb-3">

                                                <h5 class="card-title mb-0">
                                                    Recent Distribution
                                                </h5>


                                                <a href="records.php" class="btn btn-sm btn-primary">

                                                    View All

                                                </a>

                                            </div>


                                            <div class="table-responsive">


                                                <table class="table table-hover">


                                                    <thead>

                                                        <tr>

                                                            <th>Name</th>

                                                            <th>Purok</th>

                                                            <th>Date</th>

                                                            <th>Status</th>

                                                        </tr>

                                                    </thead>


                                                    <tbody>


                                                        <?php if (mysqli_num_rows($recent) > 0) { ?>


                                                            <?php while ($row = mysqli_fetch_assoc($recent)) { ?>


                                                                <tr>


                                                                    <td>
                                                                        <?php echo $row['name']; ?>
                                                                    </td>


                                                                    <td>
                                                                        <?php echo $row['purok']; ?>
                                                                    </td>


                                                                    <td>
                                                                        <?php echo $row['date_distributed']; ?>
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

                                                                <td colspan="4" class="text-center text-muted py-4">

                                                                    No distribution records yet.

                                                                </td>

                                                            </tr>


                                                        <?php } ?>


                                                    </tbody>


                                                </table>


                                            </div>


                                        </div>

                                    </div>


                    </div>

        </div>

    </div>


</body>

</html>