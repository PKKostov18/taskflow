<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Project - TaskFlow</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="/css/projects-create.css" rel="stylesheet">
</head>
<body>

    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="container">
        <h1>🚀 Create New Project</h1>
        
        <form action="/projects/store" method="POST">
            <div>
                <label>Project Name</label>
                <input type="text" name="name" placeholder="e.g. Website Redesign" required>
            </div>

            <div>
                <label>Description</label>
                <textarea name="description" rows="4" placeholder="What is this project about?"></textarea>
            </div>

            <button type="submit">Create Project</button>
            <a href="/projects" class="cancel-link">Cancel</a>
        </form>
    </div>

</body>
</html>