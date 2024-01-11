<div class="wrapper">
    <div class="container">
        <nav>
            <h1>Supernitrix</h1>
            <ul>
                <li>
                    <a href="/">Home</a>
                </li>
                <?php if (!$user) { ?>
                    <li>
                        <a href="/login">Login</a>
                    </li>
                    <li>
                        <a href="/register">Register</a>
                    </li>
                <?php } else { ?>
                    <li>
                        <a href="javascript:void()"><?= $user["username"] ?></a>
                    </li>
                    <li>
                        <a href="/logout">Logout</a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
</div>