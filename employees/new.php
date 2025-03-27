<?php

require_once '../src/employee.php';
require_once '../src/department.php';

$department = new Department();
$employee = new Employee();
$departments = $department->getAllDepartments();

if (!$departments) {
    $errorMessage = "There was an error retrieving departments";
} 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validationErrors = $employee->validateEmployee($_POST);

    if (!empty($validationErrors)) {
        $errorMessage = join(', ', $validationErrors);
    } else {
        if ($employee->createEmployee($_POST)) {
            header('Location: employees.php');
            exit;
        }
        $errorMessage = 'It was not possible to insert the new employee';
    }
}

include_once '../views/header.php';
include_once '../views/navbar.php';

?>
    <nav>
        <ul>
            <a href="employees.php" title="Back">Back</a>
        </ul>
    </nav>
    <?php if (isset($errorMessage)): ?>
            <section>
                <p class="error"><?=$errorMessage ?></p>
            </section>
        <?php else: ?>
            <form action="new.php" method="POST">
                <div>
                    <label for="txtFirstName">First name</label>
                    <input type="text" id="txtFirstName" name="first_name" required>
                </div>
                <div>
                    <label for="txtLastName">Last name</label>
                    <input type="text" id="txtLastName" name="last_name" required>
                </div>
                <div>
                    <label for="txtEmail">Email</label>
                    <input type="email" id="txtEmail" name="email" required>
                </div>
                <div>
                    <label for="txtBirthDate">Birth date</label>
                    <input type="date" id="txtBirthDate" name="birth_date">
                </div>
                <div>
                    <label for="cmbDepartment">Department</label>
                    <select name="department" id="cmbDepartment">
                        <?php foreach ($departments as $department): ?>
                            <option value="<?=$department['nDepartmentID'] ?>"><?=$department['cName'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <button type="submit">Create new Employee</button>
                </div>
            </form>
        <?php endif; ?>
    </main>

<?php include_once '../views/footer.php'; ?>