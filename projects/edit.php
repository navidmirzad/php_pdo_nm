<?php

require_once '../src/project.php';
require_once '../src/employee.php';

$projectID = $_GET['id'] ?? 0;

if (!$projectID) {
    header('Location: projects.php');
    exit;
}

$projectObj = new Project();
$employeeObj = new Employee();

$project = $projectObj->getProjectByID($projectID);
if (!$project) {
    $errorMessage = 'There was an error retrieving the project.';
}

$assignedEmployees = $projectObj->getEmployeesByProjectID($projectID);
$allEmployees = $employeeObj->getAllEmployees();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Add employee to project
    if (isset($_POST['action']) && $_POST['action'] === 'add_employee') {
        $projectObj->addEmployeeToProject($_POST['employeeID'], $_POST['nProjectID']);
        header("Location: edit.php?id=" . $_POST['nProjectID']);
        exit;
    }

    // Remove employee from project
    if (isset($_POST['action']) && $_POST['action'] === 'remove_employee') {
        $projectObj->removeEmployeeFromProject($_POST['employeeID'], $_POST['nProjectID']);
        header("Location: edit.php?id=" . $_POST['nProjectID']);
        exit;
    }

    // Update project name
    if (isset($_POST['cName'])) {
        $validationErrors = $projectObj->validateProject($_POST);
        if (!empty($validationErrors)) {
            $errorMessage = is_array($validationErrors) ? join(', ', $validationErrors) : $validationErrors;
        } else {
            if ($projectObj->updateProject($_POST)) {
                header('Location: projects.php');
                exit;
            }
            $errorMessage = 'It was not possible to update the project.';
        }
    }
}

include_once '../views/header.php';
include_once '../views/navbar.php';

?>

<nav>
    <ul>
        <a href="projects.php">Back</a>
    </ul>
</nav>

<main style="padding: 2rem;">
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?= $errorMessage ?></p>
        </section>
    <?php endif; ?>

    <?php if (isset($project)): ?>
        <form action="edit.php?id=<?= $project['nProjectID'] ?>" method="POST" style="margin-bottom: 2rem;">
            <input type="hidden" name="nProjectID" value="<?= $project['nProjectID'] ?>">
            <div>
                <label for="cName">Project Name</label>
                <input type="text" id="cName" name="cName" required value="<?= htmlspecialchars($project['cName']) ?>">
            </div>
            <div style="margin-top: 1rem;">
                <button type="submit">Update Project</button>
            </div>
        </form>

        <h3>Assigned Employees</h3>
        <ul>
            <?php foreach ($assignedEmployees as $employee): ?>
                <li>
                    <?= htmlspecialchars($employee['cLastName'] . ', ' . $employee['cFirstName']) ?> - <?= htmlspecialchars($employee['cName']) ?>
                    <form action="edit.php?id=<?= $projectID ?>" method="POST" style="display:inline;">
                        <input type="hidden" name="nProjectID" value="<?= $projectID ?>">
                        <input type="hidden" name="employeeID" value="<?= $employee['nEmployeeID'] ?>">
                        <input type="hidden" name="action" value="remove_employee">
                        <button type="submit" style="color:red;">Remove</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>

        <h3 style="margin-top: 2rem;">Add Employee to Project</h3>
        <form action="edit.php?id=<?= $projectID ?>" method="POST">
            <input type="hidden" name="nProjectID" value="<?= $projectID ?>">
            <input type="hidden" name="action" value="add_employee">

            <select name="employeeID" required>
                <option value="" disabled selected>-- Select Employee --</option>
                <?php
                $assignedIDs = array_column($assignedEmployees, 'nEmployeeID');
                foreach ($allEmployees as $emp):
                    if (!in_array($emp['nEmployeeID'], $assignedIDs)):
                ?>
                    <option value="<?= $emp['nEmployeeID'] ?>">
                        <?= htmlspecialchars($emp['cLastName'] . ', ' . $emp['cFirstName']) ?>
                    </option>
                <?php
                    endif;
                endforeach;
                ?>
            </select>
            <button type="submit">Add</button>
        </form>
    <?php endif; ?>
</main>

<?php include_once '../views/footer.php'; ?>