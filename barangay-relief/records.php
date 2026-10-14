<?php

require_once "config/database.php";

$sql = "SELECT
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
ORDER BY distributions.date_distributed DESC, distributions.id DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Distribution Records</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <?php include "includes/sidebar.php"; ?>

            <main class="col-md-9 col-lg-10 main-content">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

                    <div>
                        <h2>📋 Distribution Records</h2>

                        <p class="text-muted mb-0">
                            View all recorded relief distributions.
                        </p>
                    </div>

                    <a href="distribution.php" class="btn btn-primary">
                        + Record Distribution
                    </a>

                </div>

                <div class="card shadow-sm">
                    <div class="card-body">

                        <h5 class="card-title mb-3">
                            All Distribution Records
                        </h5>

                        <div class="table-container">
                            <div class="table-responsive">

                                <table class="table table-bordered table-hover align-middle">

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

                                        <?php if (mysqli_num_rows($result) > 0) { ?>

                                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['purok']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['relief_package']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['date_distributed']); ?></td>

                                                    <td>
                                                        <?php if ($row['status'] == 'Received') { ?>
                                                            <span class="badge bg-success">Received</span>
                                                        <?php } else { ?>
                                                            <span class="badge bg-warning text-dark">
                                                                <?php echo htmlspecialchars($row['status']); ?>
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

            </main>

        </div>
    </div>

</body>

</html>