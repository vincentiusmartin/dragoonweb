<h2>Register</h2>

<?php echo form_open('user/register') ?>
<!-- form -->
<label><font size="5">Email</font></label>
<br />
<input type="text" name="email" placeholder="Your email" size='50' />
<br />
<label><font color="red"><?php echo $email_error ?></font></label>
<br />
<label><font size="5">Password</font></label>
<br />
<input type="password" name="password" placeholder="Your password" size='50' />
<br />
<label><font color="red"><?php echo $password_error ?></font></label>
<br />
<label><font size="5">Confirm Password</font></label>
<br />
<input type="password" name="passconf" placeholder="Confirm your password" size='50' />
<br />
<label><font color="red"><?php echo $passconf_error ?></font></label>
<br />
<button type="submit" name="submit">Register</button>
<!-- end of form -->
