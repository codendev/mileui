<?php print $header?>
<form action="" method="POST">
    <div>
        <label>Name:</label><br/>
        <input type="text" id="name" name="name" value="<?php echo $object[0]->name;?>">
    </div>
    <div>
        <label>Priveleges:</label><br/>
        <table border="1px">
            <tr>
                <th>
                    Group Name
                </th>
                <th>
                    Read
                </th>
                <th>
                    Write
                </th>

            </tr>

            <? foreach($groups as $group):?>
            <?
            $group_checked="";
            $read_checked="";
            $write_checked="";

            foreach($object[0]->object_group as $item){

                if($group->id==$item->group->id&&$item->read==1){
                    $read_checked="checked";
                }
                if($group->id==$item->group->id&&$item->write==1){
                    $write_checked="checked";
                }
                
            }
            
            ?>
            <tr>
                <td> <?php echo $group->name?><input type="hidden" name="user_group[groups][]" value="<?php echo $group->id?>"/></td>
           
                
                <td><input type="checkbox" name="user_group[reads][]" value="1"
                           <?php echo $read_checked;?>>

                </td>
          
                <td>
                    <input type="checkbox" name="user_group[writes][]" value="1"
                           <?php echo $write_checked;?>>
                </td>
            </tr>
          
            <? endforeach;?>
        </table>
    </div>
    <div>
        <input type="submit" value="Save" name="submit"/>
        <input type="reset" value="Cancel" name="submit"/>
    </div>

</form>



<? print $footer?>
