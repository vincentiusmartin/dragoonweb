<h2> Welcome !! - <?php echo $username ?> </h2> 
<a href="<?php echo base_url() ?>index.php/user/logout">logout</a> 

<br />

<?php echo $notification; ?>

<h3>Your Files : </h3>
<ul style="margin-top: -10px">
<?php foreach ($fileslist as $usrfile): ?>
    <li><?php echo $usrfile; ?></li>
<?php endforeach; ?>
</ul>

<hr style ="margin : 0px 50px 0px 50px" />

<!-- Upload File -->
<h3> Upload Your File </h3>
<?php echo form_open_multipart('user/upload'); ?>

<input type="file" name="userfile" size="20" />

<br /><br />

<input type="submit" value="Upload" />

<!-- End of Upload File -->