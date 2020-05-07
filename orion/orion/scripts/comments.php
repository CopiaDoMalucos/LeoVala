<?php
chdir("..");
header("Content-Type: application/javascript");
require("backend/functions.php");
dbconn(false);
if (intval($_GET["id"]) == 0) die;
?>
var ShowComment = function(t) { document.getElementById('commentsdiv').innerHTML = t.responseText; }

function loadComments(page) {
        page = parseInt(page);
        new Ajax.Request('comments_ajax.php', {method: 'get', parameters: 'id=<?php echo $_GET["id"] ;?>&page='+page, onSuccess:ShowComment});
}

<?php if ($CURUSER["delete_torrents"] == "yes" || $CURUSER["delete_forum"] == "yes") {?>
var ShowDeleted = function (t) { document.getElementById('commentsdel').innerHTML = "Comment deleted."; loadComments(-1); }
function deleteComment (id) {
        document.getElementById('commentsdel').innerHTML = "<center><img src='images/loading.gif' border='0'><BR>Loading...</center>";
        new Ajax.Request('comments_ajax.php', {method: 'get', parameters: 'id=<?php echo $_GET["id"] ;?>&do=del&cid=' + id, onSuccess:ShowDeleted});
}

<?php
}?>