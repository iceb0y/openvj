<?php use \Phalcon\Tag; ?>

<h1>Test!</h1>

<h1>{{result}}</h1>

<h2>Register using this form</h2>

<?php echo Tag::form(array("test/register","method" => "post")); ?>

<p>
    <label for="user">UserName</label>
    <?php echo Tag::textField("user"); ?>
</p>

<p>
    <label for="pass">PassWord</label>
    <?php echo Tag::textField("pass"); ?>
</p>

<p>
    <label for="nick">NickName</label>
    <?php echo Tag::textField("nick"); ?>
</p>

<p>
    <label for="email">E-Mail :</label>
    <?php echo Tag::textField("email"); ?>
</p>

<p>
    <label for="code">Code   :</label>
    <?php echo Tag::textField("code"); ?>
</p>

<input type="radio" name="gender" value="0" checked="checked" /> Unknown
<input type="radio" name="gender" value="1" /> Male
<input type="radio" name="gender" value="2" /> Female

<br/>

<input type="radio" name="agreement" value="accept" checked="checked"/> Agree
<input type="radio" name="agreement" value="1" /> Disagree


<p>
    <?php echo Tag::submitButton("Register") ?>
</p>

</form>

<p>
    <?php echo Tag::linkTo("test","Return") ?>
</p>
