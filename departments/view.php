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
} 

require_once '../views/header.php';
require_once '../views/navbar.php';
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
            <?php endif; ?>
    </main>

<?php include_once '../views/footer.php'; ?>