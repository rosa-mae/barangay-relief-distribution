<?php

require_once "config/database.php";

$result = mysqli_query(
    $conn,
    "SELECT * FROM beneficiaries ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Beneficiaries</title>

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
                        <h2>👥 Beneficiaries</h2>
                        <p class="text-muted mb-0">Manage registered beneficiary families.</p>
                    </div>

                    <a href="add_beneficiary.php" class="btn btn-primary">
                        + Add Beneficiary
                    </a>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">

                        <h5 class="card-title mb-3">Beneficiary List</h5>

                        <div class="table-container">
                            <div class="table-responsive">

                                <table class="table table-bordered table-hover align-middle">

                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Purok</th>
                                            <th>Household Size</th>
                                            <th>Contact</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php if (mysqli_num_rows($result) > 0) { ?>

                                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['purok']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['household_size']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['contact_number'] ?? ''); ?></td>

                                                    <td>
                                                        <?php if ($row['status'] == 'Received') { ?>
                                                            <span class="badge bg-success">Received</span>
                                                        <?php } else { ?>
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                        <?php } ?>
                                                    </td>

                                                    <td>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            <a href="edit_beneficiary.php?id=<?php echo $row['id']; ?>"
                                                                class="btn btn-warning btn-sm">
                                                                Edit
                                                            </a>

                                                            <a href="delete_beneficiary.php?id=<?php echo $row['id']; ?>"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this beneficiary?');">
                                                                Delete
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>

                                            <?php } ?>

                                        <?php } else { ?>

                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-4">
                                                    No beneficiaries found.
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