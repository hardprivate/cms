<?php
$data_out4 = $data[0];
						if ($data_out4)
						{
							?>
							<table border="0">
							<tr>
								<td colspan="4"><b>Donate:</b> <br> <?=$data_out4['donat_type'];?> <br><h3> <?=$data_out4['donat_score'];?> </h3></td>
							</tr>
							<tr>
							<td colspan="4">&nbsp;</td>
							</tr>
							</table>
							<?php
						}
						?>