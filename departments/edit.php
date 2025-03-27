<?php

require_once '../src/department.php';

$departmentObj = new Department();

if (isset($_GET['id'])) {
    $departmentId = $_GET['id'];
    $department = $departmentObj->getDepartmentByID($departmentId);
    if (!$department) {
        $errorMessage = 'Therer was an error retrieving the department.';
    } else {
        $department;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validationErrors = $departmentObj->validateDepartment($_POST);
    if (!empty($validationErrors)) {
        $errorMessage = join(', ', $validationErrors);
    } else {
        if ($departmentObj->updateDepartment($_POST)) {
            header('Location: departments.php');
            exit;
        }
        $errorMessage = 'It was not possible to update the department.';
    }
}

include_once '../views/header.php';
include_once '../views/navbar.php';

?>
<nav>
    <ul>
        <a href="departments.php">Back</a>
    </ul>
</nav>
<main>
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?= $errorMessage ?></p>
        </section>
    <?php endif; ?>

    <?php if (isset($department)): ?>
        <form action="edit.php?id=<?= $department['nDepartmentID']?>" method="POST">
            <input type="hidden" name="nDepartmentID" value="<?= $department['nDepartmentID']?>">
            <div>
                <label for="txtDepartmentName">Department Name</label>
                <input type="text" id="cName" name="cName" required
                value="<?= htmlspecialchars($department['cName']) ?>">
            </div>
            <div>
                <button type="submit">Update Department</button>
            </div>
        </form>
    <?php endif; ?>
</main>

<?php include_once '../views/footer.php'; ?>