<form action='<?php echo base_url();?>index.php/user/newpassprocess' method='post' name='newpass'>
    
    <h2> New Password </h2>

    <label for="password">Password</label>
    <input type="password" name="password" />
    <br />
    <label for="repassword">Retype Password</label>
    <input type="password" name="repassword" />
    <br />
    <input type="hidden" name="t" value="<?php echo $code ?>"/>
    <br />
    <input type="submit" value="Submit">

</form>