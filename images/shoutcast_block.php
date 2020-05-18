<?    
if (!$site_config["MEMBERSONLY"] || $CURUSER) {
begin_block("P2BS Live Feed");
$showradioinfo="on";
include("radio-info.php");
end_block();
}
?>