<form action='<?php echo base_url();?>index.php/user/login' method='post' name='login'>
    <font color ="blue" size="3"><?php if(isset($registermsg)) { echo $registermsg; } ?></font>
    
    <h2> Login </h2>

    <?php
        if (isset($message))
        {
            echo $message;
        }
    ?>

    <label for="username">Username</label>
    <input type="text" name="username" />
    <br />
    <label for="password">Password&nbsp;</label>
    <input id="pass_login_field" type="password" name="password" />
    <br /><br />
    <input type="submit" value="Submit">

</form>

<font>Don't have an account ? <a href="<?php echo base_url(); ?>index.php/user/register"> Register </a> </font>
<br /><br />
<font><a href="<?php echo base_url();?>index.php/user/resetpassword"> Forgot your password? </a> </font>
    