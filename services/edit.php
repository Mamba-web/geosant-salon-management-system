<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Service ID not found.";
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "SELECT * FROM services WHERE id='$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Service not found.";
    header("Location: index.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content">

<div class="container-fluid">

<div class="card shadow-sm">

<div class="card-header">
<h4>Edit Service</h4>
</div>

<div class="card-body">

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<div class="mb-3">
<label>Service Name</label>
<input
type="text"
name="service_name"
class="form-control"
value="<?php echo $row['service_name']; ?>"
required>
</div>

<div class="mb-3">
<label>Price (GHS)</label>
<input
type="number"
step="0.01"
name="price"
class="form-control"
value="<?php echo $row['price']; ?>"
required>
</div>

<button class="btn btn-success">
Update Service
</button>

<a href="index.php" class="btn btn-secondary">
Cancel
</a>

</form>

</div>

</div>

</div>
<?php include("../includes/footer_content.php"); ?>
</div>

<?php include("../includes/footer.php"); ?>