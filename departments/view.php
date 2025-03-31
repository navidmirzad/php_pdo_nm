<?php

$departmentID = (int) ($_GET['id'] ?? 0);

if (!$departmentID) {
    header('Location: departments.php');
    exit;
}

require_once '../src/department.php';

$departmentObj = new Department();
$department = $departmentObj->getDepartmentByID($departmentID);

if (!$department) {
    $errorMessage = 'There was an error retrieving Department Information.';
} else {
    $employees = $departmentObj->getEmployeesByDepartmentID($departmentID);

    if ($employees === false) {
        $employeeErrorMessage = 'There was an error retrieving employee information.';
    } elseif (empty($employees)) {
        $employeeEmptyMessage = 'There are currently no employees for this department.';
    }
}

include_once '../views/header.php';
include_once '../views/navbar.php';
?>

<nav>
    <ul>
        <a href="departments.php" title="back">Back</a>
    </ul>
</nav>
<main>
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?= $errorMessage ?></p>
        </section>
    <?php else: ?>
        <p><strong>Department ID: </strong><?= $department['nDepartmentID'] ?></p>
        <p><strong>Department Name: </strong><?= $department['cName'] ?></p>
        
    <?php if (isset($employeeErrorMessage)): ?>
        <p class="error"><?= $employeeErrorMessage ?></p>
    <?php elseif (isset($employeeEmptyMessage)): ?>
        <br>
        <p><?= $employeeEmptyMessage ?></p>
    <?php else: ?>
        <h3>Employees in this department: </h3>
            <ul>
                <?php foreach ($employees as $emp): ?>
                    <li>
                        <?= htmlspecialchars($emp['cLastName'] . ' ' . $emp['cFirstName']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php include_once '../views/footer.php'; ?>