<!DOCTYPE html>

<head>
    <title>Parcourir</title>
    <link rel="stylesheet" href="/static/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
</head>

<body>
<?php include "header.html" ?>
    <section class="aide">
        <div>
            Bonjour
            <p>Comment pouvons-nous vous aider ?</p>
            <input type="text" name="keywords">
        </div>
    </section>
    <main class="grille">
        <article class="support">
            <h2>Général</h2>
        </article>
        <article class="support">
            <h2>Candidats</h2>
        </article>
        <article class="support">
            <h2>Recruteurs</h2>
        </article>
    </main>
    <?php include "footer.html" ?>
</body>
