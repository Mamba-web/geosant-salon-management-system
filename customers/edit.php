<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include("../config/database.php");

$id = $_GET['id'];

$sql = "SELECT * FROM customers WHERE id = $id";
$result = mysqli_query($conn, $sql);
$customer = mysqli_fetch_assoc($result);

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/navbar.php");
?>

<div class="main-content">

<div class="container-fluid">

<div class="card shadow">

<div class="card-header">
<h3>Edit Customer</h3>
</div>

<div class="card-body">

<form action="update.php" method="POST">

<input type="hidden" name="id"
value="<?php echo $customer['id']; ?>">

<div class="mb-3">
<label>Customer Name</label>

<input
type="text"
name="customer_name"
class="form-control"
value="<?php echo $customer['customer_name']; ?>"
required>

</div>

<div class="mb-3">

<label>Phone</label>

<input
type="text"
name="phone"
class="form-control"
value="<?php echo $customer['phone']; ?>"
required>

</div>

<div class="mb-3">

<label>Gender</label>

<select
name="gender"
class="form-select">

<option value="Male"
<?php if($customer['gender']=="Male") echo "selected"; ?>>
Male
</option>

<option value="Female"
<?php if($customer['gender']=="Female") echo "selected"; ?>>
Female
</option>

</select>

</div>

<button class="btn btn-success">
Update Customer
</button>

<a href="index.php"
class="btn btn-secondary">
Cancel
</a>

</form>

</div>

</div>

</div>
<?php include("../includes/footer_content.php"); ?>
</div>

<?php include("../includes/footer.php"); ?>