<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content">

    <div class="container-fluid">
        <div class="card shadow-sm">

            <div class="card-header">
                <h3>Add New Customer</h3>
            </div>

            <div class="card-body">

                <form action="store.php" method="POST">

                    <div class="mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text"
                               name="customer_name"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text"
                               name="phone"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender</label>

                        <select name="gender" class="form-select" required>

                            <option value="">Select Gender</option>
                            <option>Male</option>
                            <option>Female</option>

                        </select>

                    </div>

                    <button type="submit" class="btn btn-success">
                        Save Customer
                    </button>

                    <a href="index.php" class="btn btn-secondary">
                        Cancel
                    </a>

                </form>

            </div>

        </div>

    </div>
</div>

<?php include("../includes/footer.php"); ?>