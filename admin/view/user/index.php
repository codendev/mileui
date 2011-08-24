<?print $header?>
            <table id="hor-minimalist-b">
                <tr>    <th>Serial Number</th>
                    <th>Name</th>
                    <th>Edit</th>
                    <!--<th>Delete</th>-->
                </tr>
                <?foreach($user as $key=>$item) {?>
                <tr>
                    <td><?=$paging->firstRecord +1+$key?></td>
                    <td><?=$item->username?></td>
                    <td><a href="?action=user&event=update&id=<?=$item->id?>">Edit</a></td>
                   <!-- <td><a href="?action=delete&id=<?=$item->id?>"><img src="images/Button-Close-icon.png" alt="Delete"/></a></td>-->
                </tr>
                    <?}?>
            </table>
            <div class="paging">
                Displaying <?=$paging->firstRecord +1 ?> to <?=$paging->lastRecord + 1?>
                <a href="?action=user&event=index&idx=1"><img src="images/Button-First-icon.png" alt="next"/></a>
                <a href="?action=user&event=index&idx=<?=$paging->prevPage()?>"><img src="images/Button-Rewind-icon.png" alt="next"/></a>
                <a href="?action=user&event=index&idx=<?=$paging->nextPage()?>"><img src="images/Button-Fast-Forward-icon.png" alt="next"/></a>
                <a href="?action=user&event=index&idx=<?=$paging->numPages?>"><img src="images/Button-Last-icon.png" alt="next"/></a>
            </div>
        </div>
<?print $footer?>
