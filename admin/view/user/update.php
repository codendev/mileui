<?php print $header?>
<form action="" method="POST">
<div>
    <label>Name:</label><br/>
    <input type="text" id="name" name="name" value="<?php echo $user[0]->username;?>">
</div>
<div>
<label>Groups:</label><br/>
<select multiple name="groups[]" style="width:200px">
    <? foreach($groups as $group):?>

    <option value="<?php echo $group->id?>"
             <?php 
             foreach($user[0]->user_group as $user_group){ 
             if($user_group->group->id==$group->id){?> selected<?}}?>>
             <?php echo $group->name?>
    </option>
        <? endforeach;?>
</select>
</div>
    <div>
        <input type="submit" value="Save" name="submit"/>
        <input type="reset" value="Cancel" name="submit"/>
</div>

</form>

   

<? print $footer?>
