<html>
    <head>
        <title>
        </title>
    </head>
    <body>
        <div id="content">
            <div class="heading">
                <h2><?=@$title?></h2>
            </div>
            <table id="hor-minimalist-b">
                <tr>    <th>Serial Number</th>
                    <th>Label</th>
                    <th>Edit</th>
                    <!--<th>Delete</th>-->
                </tr>
                <?foreach($data as $key=>$item) {?>
                <tr>
                    <td><?=$paging->firstRecord +1+$key?></td>
                    <td><?=$item['label']?></td>
                    <td><a href="content.php?action=update&id=<?=$item['id']?>">Edit</a></td>
                   <!-- <td><a href="?action=delete&id=<?=$item['id']?>"><img src="images/Button-Close-icon.png" alt="Delete"/></a></td>-->
                </tr>
                    <?}?>
            </table>
            <div class="paging">
                Displaying <?=$paging->firstRecord +1 ?> to <?=$paging->lastRecord + 1?>
                <a href="?page=1"><img src="images/Button-First-icon.png" alt="next"/></a>
                <a href="?page=<?=$paging->prevPage()?>"><img src="images/Button-Rewind-icon.png" alt="next"/></a>
                <a href="?page=<?=$paging->nextPage()?>"><img src="images/Button-Fast-Forward-icon.png" alt="next"/></a>
                <a href="?page=<?=$paging->numPages?>"><img src="images/Button-Last-icon.png" alt="next"/></a>
            </div>
        </div>
    </body>

</html>
