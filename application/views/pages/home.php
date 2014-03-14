<form>
    <h2> Login </h2>
    <label for="username">Username</label>
    <input type="text" name="username" />
    <br />
    <label for="password">Password&nbsp;</label>
    <input id="pass_login_field" type="password" name="password" />
    <br />
    <input type="submit" value="Submit">
    <br />

    <?php foreach ($user as $user_item): ?>

        <h2><?php echo $user_item['email'] ?></h2>
        <div id="main">
            <?php echo $user_item['password'] ?>
        </div>

    <?php endforeach ?>
</form>
