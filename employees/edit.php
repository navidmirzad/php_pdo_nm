<?php

require_once '../src/employee.php';
require_once '../src/department.php';


$deparment = new Department();
$departments = $deparment->getAllDepartments();
$emp = new Employee();

if (!$departments) {
    $errorMessage = 'There was an error retrieving departments';
}

if (isset($_GET['id'])) {
    $employeeId = $_GET['id'];
    $employee = $emp->getEmployeeById($employeeId);
    if (!$employee) {
        $errorMessage = 'Employee not found';
    } else {
        $employee = $employee[0];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validationErrors = $emp->validateEmployee($_POST);
    if (!empty($validationErrors)) {
        $errorMessage = join(', ', $validationErrors);
    } else {
        // Make sure to pass the employee id to the update function
        if ($emp->updateEmployee($_POST)) {
            header('Location: employees.php');
            exit;
        }
        $errorMessage = 'It was not possible to update the employee';
    }
}

include_once '../views/header.php';
include_once '../views/navbar.php';

?>
<nav>
    <ul>
        <a href="employees.php" title="back">Back</a>
    </ul>
</nav>
<?php if (isset($errorMessage)): ?>
<section>
    <p class="error"><?= $errorMessage ?></p>
</section>
<?php endif; ?>

<main>
    <?php if (isset($employee)): ?>
        <form action="edit.php?id=<?= $employee['employee_id'] ?>" method="POST">
        <input type="hidden" name="id" value="<?= $employee['employee_id'] ?>">
        <div>
            <label for="txtFirstName">First name</label>
            <input type="text" id="txtFirstName" name="first_name" required
                value="<?= htmlspecialchars($employee['first_name']) ?>">
        </div>
        <div>
            <label for="txtLastName">Last name</label>
            <input type="text" id="txtLastName" name="last_name" value="<?= htmlspecialchars($employee['last_name']) ?>"
                required>
        </div>
        <div>
            <label for="txtEmail">Email</label>
            <input type="email" id="txtEmail" name="email" required
                value="<?= htmlspecialchars($employee['email']) ?>">
        </div>
        <div>
            <label for="txtBirthDate">Birth date</label>
            <input type="date" id="txtBirthDate" name="birth_date" required
                value="<?= htmlspecialchars($employee['birth_date']) ?>">
        </div>
        <div>
            <label for="cmbDepartment">Department</label>
            <select name="department" id="cmbDepartment">
                <?php foreach ($departments as $department): ?>
                <option value="<?= $department['nDepartmentID'] ?>"
                    <?= $department['nDepartmentID'] == $employee['department_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($department['cName']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <button type="submit">Update employee</button>
        </div>
    </form>
    <?php endif ?>
</main>

<?php include_once '../views/footer.php'; ?>
