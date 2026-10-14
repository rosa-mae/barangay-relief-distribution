<?php
require_once "config/database.php";

$total = mysqli_query($conn, "SELECT COUNT(*) AS total FROM beneficiaries");
$total_row = mysqli_fetch_assoc($total);
$total_beneficiaries = (int) $total_row['total'];

$received = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS received FROM beneficiaries WHERE status = 'Received'"
);
$received_row = mysqli_fetch_assoc($received);
$received_count = (int) $received_row['received'];

$pending = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS pending FROM beneficiaries WHERE status = 'Pending'"
);
$pending_row = mysqli_fetch_assoc($pending);
$pending_count = (int) $pending_row['pending'];

$completed = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS completed FROM distributions"
);
$completed_row = mysqli_fetch_assoc($completed);
$completed_count = (int) $completed_row['completed'];

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

$chart_labels = ['Received', 'Pending'];
$chart_values = [$received_count, $pending_count];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Barangay Relief Distribution Monitoring System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel=" stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <?php include "includes/sidebar.php"; ?>

            <div class="col-md-9 col-lg-10 main-content">

                <div class="mb-4">
                    <h2>🏡 Dashboard</h2>
                    <p class="text-muted mb-0">
                        Barangay Relief Distribution Monitoring System
                    </p>
                </div>

                <div class="row g-4 mb-4">

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-muted">Total Families</h6>
                                <h2 class="fw-bold">
                                    <?php echo $total_beneficiaries; ?>
                                </h2>
                                <p class="mb-0">Registered beneficiaries</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-muted">Received</h6>
                                <h2 class="fw-bold text-success">
                                    <?php echo $received_count; ?>
                                </h2>
                                <p class="mb-0">Marked as received</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-muted">Pending</h6>
                                <h2 class="fw-bold text-warning">
                                    <?php echo $pending_count; ?>
                                </h2>
                                <p class="mb-0">Awaiting assistance</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-muted">Distribution Records</h6>
                                <h2 class="fw-bold text-primary">
                                    <?php echo $completed_count; ?>
                                </h2>
                                <p class="mb-0">Recorded distributions</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">

                        <h5 class="card-title mb-3">
                            Beneficiary Status Overview
                        </h5>

                        <p class="text-muted small">
                            Number of beneficiaries by current status
                        </p>

                        <div style="position: relative; height: 300px; width: 100%;">
                            <canvas id="beneficiaryChart"></canvas>
                        </div>

                    </div>
                </div>

                <div class="card shadow-sm mb-4">

                    <div class="card-body">

                        <h5 class="card-title mb-3">
                            Recent Distribution
                        </h5>

                        <div class="table-responsive">

                            <table class="table table-hover align-middle">

                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Purok</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php if ($recent && mysqli_num_rows($recent) > 0): ?>

                                        <?php while ($row = mysqli_fetch_assoc($recent)): ?>

                                            <tr>
                                                <td>
                                                    <?php echo htmlspecialchars($row['name']); ?>
                                                </td>

                                                <td>
                                                    <?php echo htmlspecialchars($row['purok']); ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    echo htmlspecialchars(
                                                        $row['date_distributed']
                                                    );
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    $status = $row['status'];
                                                    $badge_class = (
                                                        strtolower($status) === 'received'
                                                    ) ? 'bg-success' : 'bg-warning text-dark';
                                                    ?>

                                                    <span class="badge <?php echo $badge_class; ?>">
                                                        <?php echo htmlspecialchars($status); ?>
                                                    </span>
                                                </td>
                                            </tr>

                                        <?php endwhile; ?>

                                    <?php else: ?>

                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                No distribution records found.
                                            </td>
                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const chartCanvas = document.getElementById('beneficiaryChart');

            if (chartCanvas) {

                const chartLabels = <?php echo json_encode($chart_labels); ?>;
                const chartValues = <?php echo json_encode($chart_values); ?>;

                new Chart(chartCanvas, {
                    type: 'bar',

                    data: {
                        labels: chartLabels,

                        datasets: [{
                            label: 'Number of Beneficiaries',
                            data: chartValues,

                            backgroundColor: [
                                '#198754',
                                '#ffc107'
                            ],

                            borderColor: [
                                '#146c43',
                                '#d39e00'
                            ],

                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,

                        scales: {
                            y: {
                                beginAtZero: true,

                                ticks: {
                                    precision: 0
                                },

                                title: {
                                    display: true,
                                    text: 'Number of Beneficiaries'
                                }
                            },

                            x: {
                                title: {
                                    display: true,
                                    text: 'Beneficiary Status'
                                }
                            }
                        },

                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>