<?
require_once("config.php");
Connection::setBD("alemao");
$path = __DIR__ . "\alc";
$files1 = scandir(__DIR__ . "\alc");
foreach ($files1 as $keyFileRoot => $fileRoot) {
	if (in_array($fileRoot, array(".", ".."))) {
		continue;
	}

	if (is_dir($path . '\\' . $fileRoot)) {
		$files2 = scandir($path . '\\' . $fileRoot);
		foreach ($files2 as $keyFile => $file) {
			if (strpos($file, "h_00_annot.json") > 0) {
				importarRegistro($path . "\\" . $fileRoot . "\\" . $file, $file);
			}
			try {
				//@unlink($path . "\\" . $fileRoot . "\\" . $file);
			} catch (Exception $e) {}
		}
	}
}

echo "FIM";

function importarRegistro($file, $fileName) {
	try {
		$json = file_get_contents($file);
		$json = json_decode($json);

		$dados = array("texto" => "", "file" => $fileName);
		foreach ($json->levels as $key => $value) {
			if ($value->name == "utterance") {
				foreach ($value->items as $key2 => $part) {
					foreach ($part->labels as $key3 => $folha) {
						$dados[$folha->name] = $folha->value;
					}
				}
			} else if ($value->name == "word") {
					foreach ($value->items as $key2 => $part) {
					foreach ($part->labels as $key3 => $folha) {
						if ($key3 == "word") {
							if ($dados["texto"] != "") {
								$dados["texto"] .= " ";
							}
							$dados["texto"] .= $folha->value;
						}
					}
				}
			}
		}
		
		$fields = array();
		$values = array();
		foreach ($dados as $key => $value) {
			$fields[] = '`' . $key . '`';
			$values[] = '"' . mysqli_real_escape_string(Connection::get(), $value) . '"';
		}

		if (count($values)) {
			$sql = "INSERT INTO `conversa` (" . implode(", ", $fields) . ") VALUES (" . implode(",", $values) . ")";
			query($sql);
		}
	} catch (Exception $e) {
		debug("ERRO");
		debug($e->getMessage());		
	}
}
?>