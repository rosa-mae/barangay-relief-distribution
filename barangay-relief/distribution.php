<?php

require_once "config/database.php";

$message = "";
$message_type = "danger";

if (isset($_POST['save_distribution'])) {

    $beneficiary_id = (int) $_POST['beneficiary_id'];
    $relief_package = trim($_POST['relief_package']);
    $quantity = (int) $_POST['quantity'];
    $date_distributed = $_POST['date_distributed'];

    if (
        $beneficiary_id <= 0 ||
        $relief_package === "" ||
        $quantity <= 0 ||
        $date_distributed === ""
    ) {
        $message = "Please complete all required fields.";
    } else {

        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM distributions
WHERE beneficiary_id = ?
AND date_distributed = ?"
        );

        mysqli_stmt_bind_param(
            $check,
            "is",
            $beneficiary_id,
            $date_distributed
        );

        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {

            $message = "This beneficiary already has a distribution record for this date.";

        } else {

            $insert = mysqli_prepare(
                $conn,
                "INSERT INTO distributions
(beneficiary_id, relief_package, quantity, date_distributed, status)
VALUES (?, ?, ?, ?, 'Received')"
            );

            mysqli_stmt_bind_param(
                $insert,
                "isis",
                $beneficiary_id,
                $relief_package,
                $quantity,
                $date_distributed
            );

            if (mysqli_stmt_execute($insert)) {

                $update = mysqli_prepare(
                    $conn,
                    "UPDATE beneficiaries SET status = 'Received' WHERE id = ?"
                );

                mysqli_stmt_bind_param($update, "i", $beneficiary_id);
                mysqli_stmt_execute($update);

                header("Location: distribution.php?success=1");
                exit();

            } else {

                $message = "Unable to save the distribution record.";

            }

            mysqli_stmt_close($insert);
        }

        mysqli_stmt_close($check);
    }
}

if (isset($_GET['success'])) {
    $message = "Distribution recorded successfully!";
    $message_type = "success";
}

$beneficiaries = mysqli_query(
    $conn,
    "SELECT id, name, purok
FROM beneficiaries
ORDER BY name ASC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Relief Distribution</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <?php include "includes/sidebar.php"; ?>

            <main class="col-md-9 col-lg-10 main-content">

                <div class="mb-4">
                    <h2>📦 Relief Distribution</h2>
                    <p class="text-muted mb-0">
                        Record relief goods distributed to beneficiaries.
                    </p>
                </div>

                <?php if ($message !== "") { ?>
                    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">

                        <?php echo htmlspecialchars($message); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php } ?>

                <div class="card shadow-sm">
                    <div class="card-body">

                        <h5 class="card-title mb-4">Record Distribution</h5>

                        <?php if (mysqli_num_rows($beneficiaries) > 0) { ?>

                            <form method="POST">

                                <div class="mb-3">
                                    <label class="form-label">Beneficiary</label>

                                    <select name="beneficiary_id" class="form-select" required>

                                        <option value="">Select Beneficiary</option>

                                        <?php while ($row = mysqli_fetch_assoc($beneficiaries)) { ?>

                                            <option value="<?php echo $row['id']; ?>" <?php
                                               if (
                                                   isset($_POST['beneficiary_id']) &&
                                                   (int) $_POST['beneficiary_id'] === (int) $row['id']
                                               ) {
                                                   echo 'selected';
                                               }
                                               ?>>

                                                <?php echo htmlspecialchars($row['name']); ?>
                                                - Purok <?php echo htmlspecialchars($row['purok']); ?>

                                            </option>

                                        <?php } ?>

                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Relief Package</label>

                                    <input type="text" name="relief_package" class="form-control"
                                        placeholder="Example: Food Pack"
                                        value="<?php echo htmlspecialchars($_POST['relief_package'] ?? ''); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>

                                    <input type="number" name="quantity" class="form-control" min="1"
                                        value="<?php echo htmlspecialchars($_POST['quantity'] ?? '1'); ?>" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Date Distributed</label>

                                    <input type="date" name="date_distributed" class="form-control"
                                        value="<?php echo htmlspecialchars($_POST['date_distributed'] ?? date('Y-m-d')); ?>"
                                        required>
                                </div>

                                <div class="d-flex flex-wrap gap-2">

                                    <button type="submit" name="save_distribution" class="btn btn-primary">
                                        Record Distribution
                                    </button>

                                    <a href="dashboard.php" class="btn btn-secondary">
                                        Cancel
                                    </a>

                                </div>

                            </form>

                        <?php } else { ?>

                            <div class="alert alert-warning mb-0">
                                No beneficiaries registered yet.
                                <a href="add_beneficiary.php">Add a beneficiary</a>
                                before recording a distribution.
                            </div>

                        <?php } ?>

                    </div>
                </div>

            </main>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>