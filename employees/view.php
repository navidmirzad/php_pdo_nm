<?php

$employeeID = (int) ($_GET['id'] ?? 0);

if ($employeeID === 0) {
    header('Location: employees.php');
    exit;
} 

require_once '../src/employee.php';

$employeeObj = new Employee();
$employee = $employeeObj->getEmployeeByID($employeeID);

if (!$employee) {
    $errorMessage = 'There was an error retrieving employee information.';
} else {
    $employee = $employee[0];
    $projects = $employeeObj->getProjectsByEmployeeID($employeeID);

    if ($projects === false) {
        $projectErrorMessage = 'There was an error retrieving projects information.';
    } elseif (empty($projects)) {
        $projectsEmptyMessage = 'There are currently no projects for this employee.';
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

    <?php if (isset($projectErrorMessage)): ?>
        <p class="error"><?= $projectErrorMessage; ?></p>
    <?php elseif (isset($projectsEmptyMessage)): ?>
        <br>
        <p><?= $projectsEmptyMessage ?></p>
    <?php else: ?>
        <h3>Projects: </h3>
            <ul>
                <?php foreach ($projects as $project): ?>
                    <li>
                        <?= htmlspecialchars($project['nProjectID'] . ' ' . $project['cName']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php include_once '../views/footer.php'; ?>