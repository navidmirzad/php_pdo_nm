<?php

require_once '../src/project.php';

$projectObj = new Project();

if (isset($_GET['id'])) {
    $projectID = $_GET['id'];
    $project = $projectObj->getProjectByID($projectID);
    if (!$project) {
        $errorMessage = 'Therer was an error retrieving the project.';
    } else {
        $project;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validationErrors = $projectObj->validateProject($_POST);
    if (!empty($validationErrors)) {
        $errorMessage = join(', ', $validationErrors);
    } else {
        if ($projectObj->updateProject($_POST)) {
            header('Location: projects.php');
            exit;
        }
        $errorMessage = 'It was not possible to update the project.';
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
    <?php endif; ?>

    <?php if (isset($project)): ?>
        <form action="edit.php?id=<?= $project['nProjectID']?>" method="POST">
            <input type="hidden" name="nProjectID" value="<?= $project['nProjectID']?>">
            <div>
                <label for="txtProjectName">Project Name</label>
                <input type="text" id="cName" name="cName" required
                value="<?= htmlspecialchars($project['cName']) ?>">
            </div>
            <div>
                <button type="submit">Update Project</button>
            </div>
        </form>
    <?php endif; ?>
</main>

<?php include_once '../views/footer.php'; ?>