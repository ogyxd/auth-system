<?php require partials("_head.php") ?>
<?php require partials("_navbar.php") ?>

<div class="container">
    <h1 class="mb-1 text-center">Log In</h1>

    <form class="mi-auto" method="POST">
        <input value="<?= $old["username"] ?? '' ?>" class="form-input" name="username" type="text" placeholder="Username:"><br>
        <?php if (isset($errors["username"])) { ?>
            <small class="error"><?= $errors["username"] ?></small>
        <?php } ?>
        <input class="form-input" name="password" type="password" placeholder="Password:"><br>
        <?php if (isset($errors["password"])) { ?>
            <small class="error"><?= $errors["password"] ?></small>
        <?php } ?>
        <button class="form-submit" type="submit">Log In</button>
    </form>
</div>

<?php require partials("_footer.php") ?>