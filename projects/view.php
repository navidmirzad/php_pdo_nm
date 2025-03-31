<?php

$projectID = $_GET['id'] ?? 0;

if (!$projectID) {
    header('Location: projects.php');
    exit;
}

require_once '../src/project.php';

$projectObj = new Project();
$project = $projectObj->getProjectByID($projectID);

if (!$project) {
    $errorMessage = 'There was an error retrieving Project Information.';
} else {
    $employees = $projectObj->getEmployeesByProjectID($projectID);

    if ($employees === false) {
        $employeeErrorMessage = 'There was an error retrieving employee information.';
    } elseif (empty($employees)) {
        $employeeEmptyMessage = 'There are currently no employees for this project.';
    }
}

include_once '../views/header.php';
include_once '../views/navbar.php';
?>

<nav>
    <ul>
        <a href="projects.php" title="back">Back</a>
    </ul>
</nav>
<main>
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?= $errorMessage ?></p>
        </section>
    <?php else: ?>
        <p><strong>Project ID: </strong><?= $project['nProjectID'] ?></p>
        <p><strong>Project name: </strong><?= $project['cName'] ?></p>

        <?php if (isset($employeeErrorMessage)): ?>
            <p class="error"><?= $employeeErrorMessage ?></p>
        <?php elseif (isset($employeeEmptyMessage)): ?>
            <br>
            <p><?= $employeeEmptyMessage ?></p>
        <?php else: ?>
            <h3>Employees on this project: </h3>
                <ul>
                    <?php foreach ($employees as $employee): ?>
                        <li>
                            <?= htmlspecialchars($employee['cLastName'] . ', ' . $employee['cFirstName']) . ' - ' . htmlspecialchars($employee['cName']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php endif; ?>
</main>

<?php include_once '../views/footer.php'; ?>