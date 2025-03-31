<?php

require_once '../src/department.php';

$departmentObj = new Department();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validationErrors = $departmentObj->validateDepartment($_POST);

    if (!empty($validationErrors)) {
        $errorMessage = join(',', $validationErrors);
    } else {
        if ($departmentObj->createDepartment($_POST)) {
            header('Location: departments.php');
            exit;
        }
        $errorMessage = "It was not possible to create new department.";
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
            <p class="error"><?=$errorMessage ?></p>
        </section>
    <?php else: ?>
        <h3>Add Department</h3>
        <form action="new.php" method="POST">
            <div>
                <label for="txtDepartmentName">Department Name</label>
                <input type="text" id="cName" name="cName" required>
            </div>
            <button type="submit">Create new Department</button>
        </form>
    <?php endif; ?>
</main>

<?php include_once '../views/footer.php'; ?>