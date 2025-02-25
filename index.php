<?php

require_once 'src/employee.php';

$searchText = trim($_GET['search'] ?? '');

$employee = new Employee();

if ($searchText === '') {
    $employees = $employee->getAllEmployees();
} else {
    $employees = $employee->searchEmployees($searchText);
}
if (!$employees) {
    $errorMessage = 'There was an error while retrieving the list of employees.';
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["id"])) {
    $employeeID = $_POST["id"] ?? "";
    $employee = $employee->deleteEmployee($employeeID);
    header("Location: index.php");
    exit;
}

include_once 'views/header.php';
?>
<nav>
    <ul>
        <li>
            <a href="new.php">Add employee</a>
        </li>
    </ul>
</nav>
<main>
    <?php if (isset($errorMessage)): ?>
    <section>
        <p class="error"><?= $errorMessage ?></p>
    </section>
    <?php else: ?>
    <form action="index.php" method="GET">
        <div>
            <label for="txtSearch">Search</label>
            <input type="search" id="txtSearch" name="search">
        </div>
        <div>
            <button type="submit">Search</button>
        </div>
    </form>
    <section>
        <?php foreach ($employees as $employee): ?>
        <article>
            <p><strong>First name: </strong><?= $employee['cFirstName'] ?></p>
            <p><strong>Last name: </strong><?= $employee['cLastName'] ?></p>
            <p><strong>Birth date: </strong><?= $employee['dBirth'] ?></p>
            <br>
            <button><a href="view.php?id=<?= $employee['nEmployeeID'] ?>">View details</a></button>
            <button><a href="edit.php?id=<?= $employee['nEmployeeID'] ?>">Edit details</a></button>
            <form action="index.php" method="POST">
                <input type="hidden" name="id" value="<?= $employee['nEmployeeID'] ?>">
                <button type="submit">Delete</button>
            </form>
        </article>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>
</main>
<?php include_once 'views/footer.php'; ?>
