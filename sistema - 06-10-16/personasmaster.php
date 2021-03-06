<?php

// Apellidos_Nombres
// Dni
// Cuil
// Id_Departamento
// Id_Estado
// Id_Curso
// Id_Division
// Id_Turno
// Id_Cargo
// Dni_Tutor
// NroSerie

?>
<?php if ($personas->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $personas->TableCaption() ?></h4> -->
<table id="tbl_personasmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $personas->TableCustomInnerHtml ?>
	<tbody>
<?php if ($personas->Apellidos_Nombres->Visible) { // Apellidos_Nombres ?>
		<tr id="r_Apellidos_Nombres">
			<td><?php echo $personas->Apellidos_Nombres->FldCaption() ?></td>
			<td<?php echo $personas->Apellidos_Nombres->CellAttributes() ?>>
<span id="el_personas_Apellidos_Nombres">
<span<?php echo $personas->Apellidos_Nombres->ViewAttributes() ?>>
<?php echo $personas->Apellidos_Nombres->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->Dni->Visible) { // Dni ?>
		<tr id="r_Dni">
			<td><?php echo $personas->Dni->FldCaption() ?></td>
			<td<?php echo $personas->Dni->CellAttributes() ?>>
<span id="el_personas_Dni">
<span<?php echo $personas->Dni->ViewAttributes() ?>>
<?php echo $personas->Dni->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->Cuil->Visible) { // Cuil ?>
		<tr id="r_Cuil">
			<td><?php echo $personas->Cuil->FldCaption() ?></td>
			<td<?php echo $personas->Cuil->CellAttributes() ?>>
<span id="el_personas_Cuil">
<span<?php echo $personas->Cuil->ViewAttributes() ?>>
<?php echo $personas->Cuil->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->Id_Departamento->Visible) { // Id_Departamento ?>
		<tr id="r_Id_Departamento">
			<td><?php echo $personas->Id_Departamento->FldCaption() ?></td>
			<td<?php echo $personas->Id_Departamento->CellAttributes() ?>>
<span id="el_personas_Id_Departamento">
<span<?php echo $personas->Id_Departamento->ViewAttributes() ?>>
<?php echo $personas->Id_Departamento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->Id_Estado->Visible) { // Id_Estado ?>
		<tr id="r_Id_Estado">
			<td><?php echo $personas->Id_Estado->FldCaption() ?></td>
			<td<?php echo $personas->Id_Estado->CellAttributes() ?>>
<span id="el_personas_Id_Estado">
<span<?php echo $personas->Id_Estado->ViewAttributes() ?>>
<?php echo $personas->Id_Estado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->Id_Curso->Visible) { // Id_Curso ?>
		<tr id="r_Id_Curso">
			<td><?php echo $personas->Id_Curso->FldCaption() ?></td>
			<td<?php echo $personas->Id_Curso->CellAttributes() ?>>
<span id="el_personas_Id_Curso">
<span<?php echo $personas->Id_Curso->ViewAttributes() ?>>
<?php echo $personas->Id_Curso->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->Id_Division->Visible) { // Id_Division ?>
		<tr id="r_Id_Division">
			<td><?php echo $personas->Id_Division->FldCaption() ?></td>
			<td<?php echo $personas->Id_Division->CellAttributes() ?>>
<span id="el_personas_Id_Division">
<span<?php echo $personas->Id_Division->ViewAttributes() ?>>
<?php echo $personas->Id_Division->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->Id_Turno->Visible) { // Id_Turno ?>
		<tr id="r_Id_Turno">
			<td><?php echo $personas->Id_Turno->FldCaption() ?></td>
			<td<?php echo $personas->Id_Turno->CellAttributes() ?>>
<span id="el_personas_Id_Turno">
<span<?php echo $personas->Id_Turno->ViewAttributes() ?>>
<?php echo $personas->Id_Turno->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->Id_Cargo->Visible) { // Id_Cargo ?>
		<tr id="r_Id_Cargo">
			<td><?php echo $personas->Id_Cargo->FldCaption() ?></td>
			<td<?php echo $personas->Id_Cargo->CellAttributes() ?>>
<span id="el_personas_Id_Cargo">
<span<?php echo $personas->Id_Cargo->ViewAttributes() ?>>
<?php echo $personas->Id_Cargo->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->Dni_Tutor->Visible) { // Dni_Tutor ?>
		<tr id="r_Dni_Tutor">
			<td><?php echo $personas->Dni_Tutor->FldCaption() ?></td>
			<td<?php echo $personas->Dni_Tutor->CellAttributes() ?>>
<span id="el_personas_Dni_Tutor">
<span<?php echo $personas->Dni_Tutor->ViewAttributes() ?>>
<?php echo $personas->Dni_Tutor->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($personas->NroSerie->Visible) { // NroSerie ?>
		<tr id="r_NroSerie">
			<td><?php echo $personas->NroSerie->FldCaption() ?></td>
			<td<?php echo $personas->NroSerie->CellAttributes() ?>>
<span id="el_personas_NroSerie">
<span<?php echo $personas->NroSerie->ViewAttributes() ?>>
<?php echo $personas->NroSerie->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
