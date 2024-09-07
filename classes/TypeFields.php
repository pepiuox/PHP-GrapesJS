<?php

class TypeFields {

	protected $connection;

	public function __construct($table) {
		global $conn;
		$this->connection = $conn;
		$result = $conn->query("SELECT * FROM table_settings WHERE table_name='$table'");
		$total = $result->num_rows;
		if ($total > 0) {

			while ($rqu = $result->fetch_assoc()) {

				$c_nm = $rqu['col_name'];
				$c_tp = $rqu['col_type'];
				$i_tp = $rqu['input_type'];
				$c_jo = $rqu['joins'];
				$c_tb = $rqu['j_table'];
				$c_id = $rqu['j_id'];
				$c_vl = $rqu['j_value'];

				$remp = ucfirst(str_replace("_", " ", $c_nm));
				$frmp = str_replace(" id", "", $remp);

				if ($c_nm === $ncol) {
					continue;
				}

				if ($c_tp === 'int' || $c_tp === 'tinyint' || $c_tp === 'smallint' || $c_tp === 'mediumint' || $c_tp === 'bigint' || $c_tp === 'bit' || $c_tp === 'float' || $c_tp === 'double' || $c_tp === 'decimal') {

					if ($i_tp != 3) {
						echo '<div class="form-group">
					   <label for="' . $c_nm . '">' . $frmp . ':</label>
					   <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">
				  </div>' . "\n";
					} else {
// -------------
						echo '<div class="form-group">
					   <label for="' . $c_nm . '">' . $frmp . ':</label>
					   <select type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" >' . "\n";

						$sqp1 = "select * from $c_tb";

						$qres = $this->connection->query($sqp1);

						while ($options = $qres->fetch_array()) {
							echo '<option value="' . $options[$c_id] . '">' . $options[$c_vl] . '</option>' . "\n";
						}

						echo '</select>' . "\n";
						echo '</div>' . "\n";
// --------------
					}
				}
				if ($c_tp === 'time' || $c_tp === 'year') {
					echo '<div class="form-group">
					   <label for="' . $c_nm . '">' . $frmp . ':</label>
					   <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">
				  </div>' . "\n";
				}
				if ($c_tp === 'date' || $c_tp === 'datetime' || $c_tp === 'timestamp') {
					echo '<div class="form-group">
					   <label for="' . $c_nm . '">' . $frmp . ':</label>
					   <input type="text" data-date-format="dd/mm/yyyy" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">
				  </div>' . "\n";
					echo '<script type="text/javascript">
										$(document).ready(function ()
										{
											$("#' . $c_nm . '").datepicker({
												weekStart: 1,
												daysOfWeekHighlighted: "6,0",
												autoclose: true,
												todayHighlight: true
											});
											$("#' . $c_nm . '").datepicker("setDate", new Date());
										});
									</script>' . "\n";
				}
				if ($c_tp === 'varchar' || $c_tp === 'char') {
					if ($c_nm === 'imagen') {
						echo "<script>$('.custom-file-input').on('change',function(){
							var fileName = document.getElementById('imagen').files[0].name;
							$(this).next('.form-control-file').addClass('selected').php(fileName);
						});</script>";
						echo '<div class="form-group">
					   <label for="' . $c_nm . '">' . $frmp . ':</label>
						<div class="input-group">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="' . $c_nm . '">Subir</span>
						  </div>
						  <div class="custom-file">
							<input type="file" class="custom-file-input" id="' . $c_nm . '" name="' . $c_nm . '"
							  aria-describedby="i' . $c_nm . '">
							<label class="custom-file-label" for="' . $c_nm . '">Elegir archivo</label>
						  </div>
						</div>
						<div id="preview">
														<?= $preview;?>
												</div>
						</div>
						' . "\n";
					} else {
						echo '<div class="form-group">
					   <label for="' . $c_nm . '">' . $frmp . ':</label>
					   <input type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '">
				  </div>' . "\n";
					}
				}
				if ($c_tp === 'text' || $c_tp === 'tinytext' || $c_tp === 'mediumtext' || $c_tp === 'longtext' || $c_tp === 'json') {
					echo '<div class="form-group">
					   <label for="' . $c_nm . '">' . $frmp . ':</label>
					   <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '"></textarea>
				  </div>' . "\n";
				}
				if ($c_tp === 'point' || $c_tp === 'linestring' || $c_tp === 'polygon' || $c_tp === 'geometry' || $c_tp === 'multipoint' || $c_tp === 'multilinestring' || $c_tp === 'multipolygon' || $c_tp === 'geometrycollection') {
					echo '<div class="form-group">
					   <label for="' . $c_nm . '">' . $frmp . ':</label>
					   <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '"></textarea>
				  </div>' . "\n";
				}
				if ($c_tp === 'binary' || $c_tp === 'varbinary' || $c_tp === 'tinyblob' || $c_tp === 'blob' || $c_tp === 'mediumblob' || $c_tp === 'longblob') {
					echo '<div class="form-group">
					   <label for="' . $c_nm . '">' . $frmp . ':</label>
					   <textarea type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '"></textarea>
				  </div>' . "\n";
				}
				if ($c_tp === 'enum' || $c_tp === 'set') {
// ----------------------
					$isql = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $tble . "' AND COLUMN_NAME = '" . $c_nm . "'";

					$iresult = $this->connection->query($isql);
					$row = mysqli_fetch_array($iresult);
					$enum_list = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE']) - 6))));
					$default_value = '';
//
					echo '<div class="form-group">
					   <label for="' . $c_nm . '">' . $frmp . ':</label>
					   <select type="text" class="form-control" id="' . $c_nm . '" name="' . $c_nm . '" >' . "\n";

					$options = $enum_list;
					foreach ($options as $option) {
						$soption = '<option value="' . $option . '"';
						$soption .= ($default_value === $option) ? ' SELECTED' : '';
						$soption .= '>' . $option . '</option>' . "\n";
						echo $soption . "\n";
					}
					echo '</select>' . "\n";
					echo '</div>' . "\n";

// ----------------------
				}
			}
		}
	}
}
