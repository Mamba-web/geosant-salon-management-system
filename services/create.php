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
                <h4>Add New Service</h4>
            </div>

            <div class="card-body">

                <form action="store.php" method="POST">

                    <div class="mb-3">
                        <label class="form-label">Service Name</label>
                        <input type="text"
                               name="service_name"
                               class="form-control"
                               placeholder="Enter service name"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Price (GHS)</label>
                        <input type="number"
                               name="price"
                               class="form-control"
                               step="0.01"
                               min="0"
                               placeholder="Enter price"
                               required>
                    </div>

                    <button type="submit" class="btn btn-success">
                        Save Service
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