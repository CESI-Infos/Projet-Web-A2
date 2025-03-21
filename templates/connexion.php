<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se Connecter - TheGoodPlan</title>
    <link rel="stylesheet" href="/static/styles.css">
</head>
<body>
<?php include "header.html" ?>

    <main class="cadre">
        <h1>Se Connecter</h1>
        <p>Nouveau sur le site ? <a href="inscription.php" class="blue-link">S’inscrire</a></p>
        <form>
            <label for="email">E-mail *</label>
            <input type="email" id="email" placeholder="E-mail" required>

            <label for="password">Mot de passe *</label>
            <input type="password" id="password" required>

            <a href="mot-de-passe-oublie.php" class="blue-link">Mot de passe oublié ?</a>

            <button type="submit">Se connecter</button>
        </form>
    </main>

    <?php include "footer.html" ?>
</body>
</html>
