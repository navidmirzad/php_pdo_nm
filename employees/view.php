<?php

$employeeID = (int) ($_GET['id'] ?? 0);

if ($employeeID === 0) {
    header('Location: employees.php');
    exit;
}

require_once '../src/employee.php';

$employee = new Employee();
$employee = $employee->getEmployeeByID($employeeID);

if (!$employee) {
    $errorMessage = 'There was an error retrieving employee information.';
} else {
    $employee = $employee[0];
}

include_once '../views/header.php';
include_once '../views/navbar.php';
?>
    <nav>
        <ul>
            <a href="employees.php" title="back">Back</a>
        </ul>
    </nav>
    <main>
        <?php if (isset($errorMessage)): ?>
            <section>
                <p class="error"><?=$errorMessage ?></p>
            </section>
        <?php else: ?>
            <p><strong>First name: </strong><?=$employee['first_name'] ?></p>
            <p><strong>Last name: </strong><?=$employee['last_name'] ?></p>
            <p><strong>Email: </strong><?=$employee['email'] ?></p>
            <p><strong>Birth date: </strong><?=$employee['birth_date'] ?></p>
            <p><strong>Department: </strong><?=$employee['department_name'] ?></p>
        <?php endif; ?>
    </main>

<?php include_once '../views/footer.php'; ?>