<?php




if($CURUSER){//Somente logado
	begin_block("Pesquisa Rápida");
	?>
	<!-- Keep all menus within masterdiv-->
<div id="masterdiv">

	<div class="menutitle" onclick="SwitchMenu('sub1')">Torrent</div>
	<span class="submenu" id="sub1">

	
	
	
	
	
	
	
	
	<table border="0" width="100%">
		<tr>
			<td valign="bottom">
				<form action="torrents-search.php" method="get">
					<div style="vertical-align:bottom;">
						<input type="text" size="20" name="search"  onfocus="if (this.value == 'Torrents') this.value='';" onblur="if (this.value == '') this.value='';" value="Torrents">&nbsp;<input type="submit" name="submit" value="Enviar" >

					</div>
				</form>
			</td>
		</tr>
		<tr>
		
		</tr>
		<tr>
	
		</tr>
	</table>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	</span>

	<div class="menutitle" onclick="SwitchMenu('sub2')">Usuários</div>
	<span class="submenu" id="sub2">
	<table border="0" width="100%">
		<tr>

		</tr>
		<tr>
			<td valign="bottom">
					<form action="memberlist.php" method="get">
					<div style="vertical-align:bottom;">
						<input type="text" size="20" name="Usuario"  onfocus="if (this.value == 'Usuários') this.value='';" onblur="if (this.value == '') this.value='';" value="Usuários">&nbsp;<input type="submit" name="submit" value="Enviar" >
					
					</div>
				</form>
			</td>
		</tr>
		<tr>
	
		</tr>
	</table>
	</span>

	<div class="menutitle" onclick="SwitchMenu('sub3')">Forum</div>
	<span class="submenu" id="sub3">
		<table border="0" width="100%">
		<tr>
	
		</tr>
		<tr>
	
		</tr>
		<tr>
			<td valign="bottom">
				<form action="forums.php" method="get"><input type="hidden" name="action" value="Forum" />
					<div style="vertical-align:bottom;">
						<input type="text" size="20" name="keywords"  onfocus="if (this.value == 'SearchForum') this.value='';" onblur="if (this.value == '') this.value='SearchForum';">&nbsp;<input type="submit" name="submit" value="Enviar" >
		
					
					</div>
				</form>
			</td>
		</tr>
	</table>
	</span>
	</div>
	<?php
	end_block();
}
?>