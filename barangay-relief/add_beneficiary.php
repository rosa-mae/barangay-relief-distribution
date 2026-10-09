<?php
require_once "config/database.php";

$error = "";

$name = "";
$address = "";
$purok = "";
$household_size = "";
$contact_number = "";

if (isset($_POST['save'])) {
    $name = trim($_POST['name'] ?? "");
    $address = trim($_POST['address'] ?? "");
    $purok = trim($_POST['purok'] ?? "");
    $household_size = filter_var(
        $_POST['household_size'] ?? "",
        FILTER_VALIDATE_INT
    );
    $contact_number = trim($_POST['contact_number'] ?? "");

    if (
        $name === "" ||
        $address === "" ||
        $purok === "" ||
        $household_size === false ||
        $household_size < 1
    ) {
        $error = "Please complete all required fields correctly.";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO beneficiaries
(name, address, purok, household_size, contact_number)
VALUES (?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssis",
            $name,
            $address,
            $purok,
            $household_size,
            $contact_number
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: beneficiaries.php?added=1");
            exit();
        } else {
            $error = "Unable to save beneficiary. Please try again.";
        }

        mysqli_stmt_close($stmt);
    }

    if ($household_size === false) {
        $household_size = $_POST['household_size'] ?? "";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Beneficiary | Barangay Relief</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include "includes/sidebar.php"; ?>

            <main class="col-md-9 col-lg-10 main-content">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                    <div>
                        <h2 class="mb-1">Add Beneficiary</h2>
                        <p class="text-muted mb-0">
                            Register a household for relief assistance.
                        </p>
                    </div>

                    <a href="beneficiaries.php" class="btn btn-outline-secondary">
                        Back to Beneficiaries
                    </a>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">

                        <?php if ($error !== ""): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" name="name" class="form-control" maxlength="100"
                                        value="<?php echo htmlspecialchars($name); ?>" required>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Address *</label>
                                    <input type="text" name="address" class="form-control" maxlength="255"
                                        value="<?php echo htmlspecialchars($address); ?>" required>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Purok *</label>
                                    <input type="text" name="purok" class="form-control" maxlength="50"
                                        value="<?php echo htmlspecialchars($purok); ?>" required>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Household Size *</label>
                                    <input type="number" name="household_size" class="form-control" min="1"
                                        value="<?php echo htmlspecialchars((string) $household_size); ?>" required>
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control" maxlength="20"
                                        value="<?php echo htmlspecialchars($contact_number); ?>">
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" name="save" class="btn btn-primary">
                                    Save Beneficiary
                                </button>

                                <a href="beneficiaries.php" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>