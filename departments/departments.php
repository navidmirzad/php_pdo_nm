<?php 

require_once '../src/department.php';

$searchText = trim($_GET['search'] ?? '');

$department = new Department();

if ($searchText === '') {
    $departments = $department->getAllDepartments();
} else {
    $departments = $department->searchDepartments($searchText);
}

if (!$departments) {
    $errorMessage = "Error retrieving departments.";
}

include_once '../views/header.php';
include_once '../views/navbar.php';

?>

<main style="padding-top: 4rem;";>
    <?php if (isset($errorMessage)): ?>
    <section>
        <p class="error"><?= $errorMessage ?></p>
    </section>
    <?php else: ?>
        <form action="departments.php" method="GET" style="display: flex; align-items: center; gap: 10px;">
    <div style="display: flex; align-items: center; gap: 5px;">
        <label for="txtSearch">Search:</label>
        <input type="search" id="txtSearch" name="search">
    </div>
    <div>
        <button type="submit">Search</button>
    </div>
    <nav style="margin-left: auto;">
        <ul style="list-style: none; margin: 0; padding: 0;">
            <li style="display: inline;">
                <button><a href="new.php" style="text-decoration: none";>Add department</a></button>
            </li>
        </ul>
    </nav>
</form>
    <section>
        <?php foreach ($departments as $department): ?>
            <article style="border: 2px solid black; border-radius: 1rem; padding: 1rem; margin-top: 1rem;">
            <p><strong>Department ID: </strong><?= $department['nDepartmentID'] ?></p>
            <p><strong>Department Name: </strong><?= $department['cName'] ?></p>
            <br>
            <div style="display: flex; gap: 0.1rem;">
                <button><a href="view.php?id=<?= $employee['nDepartmentID'] ?>" style="text-decoration: none;">View details</a></button>
                <button><a href="edit.php?id=<?= $employee['nDepartmentID'] ?>" style="text-decoration: none;">Edit details</a></button>
                <form action="departments.php" method="POST">
                    <input type="hidden" name="id" value="<?= $employee['nEmployeeID'] ?>">
                    <button type="submit" style="background-color: red;">Delete</button>
                </form>
            </div>
        </article>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>
</main>



<?php include_once '../views/footer.php' ?>