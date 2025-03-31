<?php

require_once '../src/project.php';

$projectObj = new Project();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validationErrors = $projectObj->validateProject($_POST);

    if (!empty($validationErrors)) {
        $errorMessage = join(', ', $validationErrors);
    } else {
        if ($projectObj->createProject($_POST)) {
            header('Location: projects.php');
            exit;
        }
        $errorMessage = 'It was not possible to create new project.';
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
<main>
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?= $errorMessage ?></p>
        </section>
    <?php else: ?>
        <form action="new.php" method="POST">
            <div>
                <label for="txtProjectName">Project Name</label>
                <input type="text" id="cName" name="cName" required>
            </div>
            <button type="submit">Add Project</button>
        </form>
    <?php endif; ?>
</main>



<?php include_once '../views/footer.php'; ?>