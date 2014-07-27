<?php use \Phalcon\Tag; ?>

<h1>Test!</h1>

<h1>{{result}}</h1>

<h2>Login using this form</h2>

<?php echo Tag::form(array("test/login","method" => "post")); ?>

<p>
    <label for="user">UserName</label>
    <?php echo Tag::textField("user"); ?>
</p>

<p>
    <label for="pass">PassWord</label>
    <?php echo Tag::textField("pass"); ?>
</p>

<p>
    <?php echo Tag::submitButton("Login") ?>
</p>

<p>
    <?php echo Tag::linkTo("test","Return") ?>
</p>

</form>
