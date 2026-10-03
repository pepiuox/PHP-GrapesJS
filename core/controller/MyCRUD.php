<?php
//
//  This application develop by PEPIUOX.
//  Migrated to PDO & Secured by AI Assistant
//  - Removed RCE via temp files (qtmp.php, vtmp.php, ftmp.php)
//  - Removed SQL Injection (all queries use prepared statements)
//  - Removed XSS (all output escaped with htmlspecialchars)
//  - Removed stripslashes / mysqli_real_escape_string (obsolete)
//
class MyCRUD {
    protected $connection;
    protected $hostDB;
    protected $userDB;
    protected $passDB;
    protected $baseDB;
    public $pgname;

    // Input type mappings
    private $itpi = ['int','tinyint','smallint','mediumint','bigint','bit','float','double','decimal'];
    private $itpc = ['time','year'];
    private $itpd = ['date','datetime','timestamp'];
    private $itpv = ['varchar','char'];
    private $itpt = ['text','tinytext','mediumtext','longtext','json','point','linestring','polygon',
    'geometry','multipoint','multilinestring','multipolygon','geometrycollection',
    'binary','varbinary','tinyblob','blob','mediumblob','longblob'];
    private $itpe = ['enum','set'];

    // PDO type mappings
    private $tpi = ['tinyint','smallint','mediumint','int','bigint','bit'];
    private $tpb = ['binary','varbinary','tinyblob','blob','mediumblob','longblob'];
    private $tpd = ['float','double','decimal'];
    private $tps = ['varchar','char','tinytext','text','mediumtext','longtext','json','uuid',
    'date','time','year','datetime','timestamp','point','linestring','polygon',
    'geometry','multipoint','multilinestring','multipolygon','geometrycollection',
    'unknown','enum','set'];

    public function __construct() {
        global $conn, $rname;
        if (!($conn instanceof PDO)) {
            throw new RuntimeException('MyCRUD requires $conn to be a PDO instance.');
        }
        $this->connection = $conn;
        $this->pgname     = $rname ?? '';
    }

    /* =========================================================
     *  HELPERS DE SEGURIDAD
     * ========================================================= */

    /** Escapa HTML para prevenir XSS */
    protected function e($str): string {
        return htmlspecialchars((string)($str ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /** Valida que un identificador (tabla/columna) sea seguro */
    protected function validateIdentifier(string $name): string {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $name)) {
            throw new InvalidArgumentException("Invalid identifier: $name");
        }
        return $name;
    }

    /** Obtiene el nombre de la base de datos actual */
    protected function getDbName(): string {
        return $this->connection->query("SELECT DATABASE()")->fetchColumn();
    }

    /** Convierte un tipo MySQL a su constante PDO::PARAM_* */
    protected function pdoParamType(string $mysqlType): int {
        if (in_array($mysqlType, $this->tpi, true)) return PDO::PARAM_INT;
        if (in_array($mysqlType, $this->tpb, true)) return PDO::PARAM_LOB;
        return PDO::PARAM_STR;
    }

    /* =========================================================
     *  MÉTODOS BASE DE CONSULTA
     * ========================================================= */

    /** @deprecated Mantenido por compatibilidad. Usar selectData() */
    public function protect($str) {
        // Ya no es necesario con PDO prepared statements.
        // Solo se escapa para HTML si se va a imprimir.
        return htmlspecialchars(trim((string)$str), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function getAllData(string $tble): PDOStatement {
        $tble = $this->validateIdentifier($tble);
        return $this->connection->query("SELECT * FROM `{$tble}`");
    }

    public function selectData(string $query, array $params = []): PDOStatement {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    public function getID(string $tble): ?string {
        $tble = $this->validateIdentifier($tble);
        $stmt = $this->connection->query("SELECT * FROM `{$tble}` LIMIT 1");
        if ($stmt && $stmt->columnCount() > 0) {
            $meta = $stmt->getColumnMeta(0);
            return $meta['name'] ?? null;
        }
        return null;
    }

    public function getColumnNames(string $tble): array {
        $tble = $this->validateIdentifier($tble);
        $stmt = $this->connection->prepare("DESCRIBE `{$tble}`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function listDatatype(string $tble): array {
        $tble = $this->validateIdentifier($tble);
        $sql = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :tble";
        return $this->selectData($sql, [':db' => $this->getDbName(), ':tble' => $tble])
        ->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function showCol(string $tble): array {
        $tble = $this->validateIdentifier($tble);
        $sql = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :tble";
        return $this->selectData($sql, [':db' => $this->getDbName(), ':tble' => $tble])
        ->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function viewColumns(string $tble): array {
        $tble = $this->validateIdentifier($tble);
        $sql = "SELECT COLUMN_NAME AS name, DATA_TYPE AS type
        FROM information_schema.columns
        WHERE table_schema = :db AND table_name = :tble";
        return $this->selectData($sql, [':db' => $this->getDbName(), ':tble' => $tble])
        ->fetchAll(PDO::FETCH_OBJ);
    }

    /* =========================================================
     *  LISTADOS Y TABLAS
     * ========================================================= */

    public function getList(string $sql, string $col, array $params = []): void {
        $result = $this->selectData($sql, $params);
        if ($result->columnCount() === 0) return;

        $ths = [];
        for ($i = 0; $i < $result->columnCount(); $i++) {
            $meta = $result->getColumnMeta($i);
            $ths[] = $meta['name'];
        }

        echo '<table class="table"><thead><tr>';
        foreach ($ths as $tnms) {
            $tremp = ucfirst(str_replace("_", " ", $tnms));
            $remp  = str_replace(" id", " ", $tremp);
            echo '<th>' . $this->e($remp) . '</th>';
        }
        echo '</tr></thead><tbody>';

        $r = 1;
        while ($td = $result->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr id="row_' . $r . '">';
            foreach ($ths as $tnms) {
                $val = $this->e($td[$tnms] ?? '');
                if ($tnms === $col) {
                    echo '<td id="' . $this->e($tnms) . '"><input type="text" name="' . $this->e($tnms) . '[]" value="' . $val . '"></td>';
                } else {
                    echo '<td id="' . $this->e($tnms) . '"><input type="text" name="' . $this->e($tnms) . '[]" value="' . $val . '" readonly></td>';
                }
            }
            echo '</tr>';
            $r++;
        }
        echo '</tbody></table>';
    }

    public function getDatalist(string $tble): void {
        $tble = $this->validateIdentifier($tble);
        $total_pages = (int)$this->connection->query("SELECT COUNT(*) FROM `{$tble}`")->fetchColumn();
        $colmns = $this->viewColumns($tble);

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $num_results_on_page = 10;
        $calc_page = ($page - 1) * $num_results_on_page;

        $sql = "SELECT * FROM `{$tble}` LIMIT :offset, :limit";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':offset', $calc_page, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $num_results_on_page, PDO::PARAM_INT);
        $stmt->execute();

        $safeTble = $this->e($tble);
        $safePg   = $this->e($this->pgname);

        echo '<table class="table"><thead><tr>';
        echo '<th><a id="addrow" name="addrow" title="Add" class="btn btn-primary" href="' . $safePg . '?cms=table_crud&amp;w=add&amp;tbl=' . $safeTble . '">Add <i class="fa fa-plus-square"></i></a></th>';
        foreach ($colmns as $colmn) {
            $tremp = ucfirst(str_replace("_", " ", $colmn->name));
            $remp  = str_replace(" id", " ", $tremp);
            echo '<th>' . $this->e($remp) . '</th>';
        }
        echo '</tr></thead><tbody>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            $firstVal = $this->e(reset($row));
            echo '<td>
            <a id="editrow" name="editrow" title="Edit" class="btn btn-success" href="' . $safePg . '?cms=table_crud&amp;w=edit&amp;tbl=' . $safeTble . '&amp;id=' . $firstVal . '"><i class="fas fa-edit"></i></a>
            <a id="deleterow" name="deleterow" title="Delete" class="btn btn-danger" href="' . $safePg . '?cms=table_crud&amp;w=delete&amp;tbl=' . $safeTble . '&amp;id=' . $firstVal . '"><i class="fas fa-trash-alt"></i></a>
            </td>';

        foreach ($colmns as $colmn) {
            $fd = $row[$colmn->name] ?? '';

            // Relación con table_column_settings
            $relSql = "SELECT j_table, j_id, j_value FROM table_column_settings
            WHERE table_name = :tble AND col_name = :col AND input_type IS NOT NULL";
            $relStmt = $this->connection->prepare($relSql);
            $relStmt->execute([':tble' => $tble, ':col' => $colmn->name]);
            $trow = $relStmt->fetch(PDO::FETCH_ASSOC);

            if ($trow) {
                if ($colmn->name === 'imagen') {
                    echo '<td><img src="' . $this->e($fd) . '" style="width:auto; height: 100px;"></td>';
                } else {
                    $tb  = $this->validateIdentifier($trow['j_table']);
                    $id  = $this->validateIdentifier($trow['j_id']);
                    $val = $this->validateIdentifier($trow['j_value']);
                    $q = "SELECT `{$val}` FROM `{$tb}` WHERE `{$id}` = :fd LIMIT 1";
                    $rest = $this->selectData($q, [':fd' => $fd]);
                    $tow  = $rest->fetch(PDO::FETCH_ASSOC);
                    $display = $tow[$val] ?? '';
                    echo '<td><a class="goto" href="search.php?w=find&amp;tbl=' . $this->e($tb) . '&amp;id=' . $this->e($fd) . '">' . $this->e($display) . '</a></td>';
                }
            } else {
                echo '<td>' . $this->e($fd) . '</td>';
            }
        }
        echo '</tr>';
        }
        echo '</tbody></table>';

        // Paginación
        $totalPageCount = (int)ceil($total_pages / $num_results_on_page);
        if ($totalPageCount > 0) {
            $url = $safePg . '?cms=table_crud&amp;w=list&amp;tbl=' . $safeTble;
            echo '<nav aria-label="page navigation mx-auto"><ul class="pagination justify-content-center">';
            if ($page > 1) echo '<li class="page-item prev"><a href="' . $url . '&amp;page=' . ($page - 1) . '">Previous</a></li>';
            if ($page > 3) echo '<li class="page-item start"><a href="' . $url . '&amp;page=1">1</a></li><li class="page-item dots">...</li>';
            if ($page - 2 > 0) echo '<li class="page-item page"><a href="' . $url . '&amp;page=' . ($page - 2) . '">' . ($page - 2) . '</a></li>';
            if ($page - 1 > 0) echo '<li class="page-item page"><a href="' . $url . '&amp;page=' . ($page - 1) . '">' . ($page - 1) . '</a></li>';
            echo '<li class="page-item currentpage"><a href="' . $url . '&amp;page=' . $page . '">' . $page . '</a></li>';
            if ($page + 1 <= $totalPageCount) echo '<li class="page-item page"><a href="' . $url . '&amp;page=' . ($page + 1) . '">' . ($page + 1) . '</a></li>';
            if ($page + 2 <= $totalPageCount) echo '<li class="page-item page"><a href="' . $url . '&amp;page=' . ($page + 2) . '">' . ($page + 2) . '</a></li>';
            if ($page < $totalPageCount - 2) echo '<li class="page-item dots">...</li><li class="page-item end"><a href="' . $url . '&amp;page=' . $totalPageCount . '">' . $totalPageCount . '</a></li>';
            if ($page < $totalPageCount) echo '<li class="page-item next"><a href="' . $url . '&amp;page=' . ($page + 1) . '">Next</a></li>';
            echo '</ul></nav>';
        }
    }

    public function listData(string $tble): void {
        $tble = $this->validateIdentifier($tble);
        $colms = $this->viewColumns($tble);
        $ncol  = $this->getID($tble);

        // Configuraciones de columna (relaciones)
        $relSql = "SELECT col_name, joins, j_table, j_id, j_value
        FROM table_column_settings
        WHERE table_name = :tble AND input_type IS NOT NULL";
        $relStmt = $this->connection->prepare($relSql);
        $relStmt->execute([':tble' => $tble]);
        $relations = $relStmt->fetchAll(PDO::FETCH_ASSOC);

        // Construir JOINs de forma segura
        $joins = '';
        $selectCols = ["`{$tble}`.*"];
        $hiddenCols = [];
        foreach ($relations as $r) {
            $jt = $this->validateIdentifier($r['j_table']);
            $ji = $this->validateIdentifier($r['j_id']);
            $jv = $this->validateIdentifier($r['j_value']);
            $cn = $this->validateIdentifier($r['col_name']);
            $joins .= " LEFT JOIN `{$jt}` ON `{$tble}`.`{$cn}` = `{$jt}`.`{$ji}`";
            $selectCols[] = "`{$jt}`.`{$jv}` AS `__rel_{$cn}`";
            $hiddenCols[] = $ji;
            $hiddenCols[] = $jv;
        }

        $start = 1;
        $range = 10;
        $pg = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($pg - $start) * $range;

        $total = (int)$this->connection->query("SELECT COUNT(*) FROM `{$tble}`")->fetchColumn();
        $endpage = (int)ceil($total / $range);

        $selectList = implode(', ', $selectCols);
        $sql = "SELECT {$selectList} FROM `{$tble}` {$joins} LIMIT :lim OFFSET :off";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':lim', $range, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $safeTble = $this->e($tble);
        $safePg   = $this->e($this->pgname);

        echo '<form class="row form-horizontal" role="form" method="POST">';
        echo '<table class="table table-bordered table-striped table-hover"><thead class="bg-info"><tr>';

        foreach ($colms as $meta) {
            // Ocultar columnas que son IDs/valores de relaciones
            if (in_array($meta->name, $hiddenCols, true)) continue;
            $tremp = ucfirst(str_replace("_", " ", $meta->name));
            $remp  = str_replace(" id", " ", $tremp);
            echo '<th>' . $this->e($remp) . '</th>';
        }
        echo '<th><a id="addrow" name="addrow" class="btn btn-primary" href="' . $safePg . '?cms=table_crud&amp;w=add&amp;tbl=' . $safeTble . '">Add</a></th>';
        echo '</tr></thead><tbody>';

        foreach ($rows as $rw) {
            echo '<tr>';
            foreach ($colms as $meta) {
                if (in_array($meta->name, $hiddenCols, true)) continue;

                // Si es la primera columna (ID)
                if ($meta->name === $ncol) {
                    echo '<td id="' . $this->e($rw[$ncol]) . '">' . $this->e($rw[$ncol]) . '</td>';
                } else {
                    // Buscar si tiene relación
                    $relKey = "__rel_{$meta->name}";
                    if (array_key_exists($relKey, $rw) && $rw[$relKey] !== null) {
                        // Buscar la relación para obtener j_table
                        $relInfo = null;
                        foreach ($relations as $r) {
                            if ($r['col_name'] === $meta->name) { $relInfo = $r; break; }
                        }
                        if ($relInfo) {
                            $tb = $this->validateIdentifier($relInfo['j_table']);
                            echo '<td><a class="goto" href="search.php?w=find&amp;tbl=' . $this->e($tb) . '&amp;id=' . $this->e($rw[$meta->name]) . '">' . $this->e($rw[$relKey]) . '</a></td>';
                        } else {
                            echo '<td>' . $this->e($rw[$meta->name]) . '</td>';
                        }
                    } else {
                        echo '<td>' . $this->e($rw[$meta->name] ?? '') . '</td>';
                    }
                }
            }
            $i_row = $rw[$ncol] ?? '';
            echo '<td>
            <a id="editrow" name="editrow" class="btn btn-success" href="' . $safePg . '?cms=table_crud&amp;w=edit&amp;tbl=' . $safeTble . '&amp;id=' . $this->e($i_row) . '">Edit</a>
            <a id="deleterow" name="deleterow" class="btn btn-danger" href="' . $safePg . '?cms=table_crud&amp;w=delete&amp;tbl=' . $safeTble . '&amp;id=' . $this->e($i_row) . '">Borrar</a>
            </td>';
        echo '</tr>';
        }
        echo '</tbody></table></form>';

        // Paginación
        $url = $safePg . '?cms=table_crud&amp;w=list&amp;tbl=' . $safeTble;
        $startpage = 1;
        if ($offset < $total) {
            echo '<nav aria-label="navigation"><ul class="pagination justify-content-center">';
            echo '<li class="page-item' . ($pg <= $startpage ? ' disabled' : '') . '"><a class="page-link" href="' . $url . '&amp;page=' . $startpage . '">First</a></li>';
            echo '<li class="page-item ' . ($pg <= 1 ? 'disabled' : '') . '"><a class="page-link" href="' . ($pg <= 1 ? '#' : $url . '&amp;page=' . $pg) . '">Prev</a></li>';
            for ($x = 1; $x <= $range && $pg <= $endpage; $x++) {
                echo '<li class="page-item ' . ($pg == (($offset / $range) + 1) ? 'disabled' : '') . '"><a class="page-link" href="' . $url . '&amp;page=' . $pg . '">' . $pg . '</a></li>';
                $pg++;
            }
            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            echo '<li class="page-item ' . ($endpage == $currentPage ? 'disabled' : '') . '"><a class="page-link" href="' . ($currentPage >= $endpage ? '#' : $url . '&amp;page=' . ($currentPage + 1)) . '">Next</a></li>';
            echo '<li class="page-item' . ($endpage == $currentPage ? ' disabled' : '') . '"><a class="page-link" href="' . $url . '&amp;page=' . $endpage . '">Last</a></li>';
            echo '</ul></nav>';
        }
    }

    /* =========================================================
     *  INPUTS Y ENUMS
     * ========================================================= */

    public function ShowInputData(string $table, string $clmn): void {
        $table = $this->validateIdentifier($table);
        $clmn  = $this->validateIdentifier($clmn);

        $sql = "SELECT * FROM table_column_settings WHERE table_name = :t AND col_name = :c";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':t' => $table, ':c' => $clmn]);
        $rqu = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$rqu) return;

        $c_nm = $rqu['col_name'];
        $c_tp = $rqu['col_type'];
        $remp = ucfirst(str_replace("_", " ", $c_nm));
        $frmp = str_replace(" id", "", $remp);

        $intdata = ['int','tinyint','smallint','mediumint','bigint'];
        $strdata = ['varchar','char','text','tinytext','mediumtext','longtext','time','year','date','datetime','timestamp','json','enum','set','point','linestring','polygon','geometry','multipoint','multilinestring','multipolygon','geometrycollection'];
        $doudata = ['binary','varbinary','bit','float','double','decimal'];
        $blodata = ['tinyblob','blob','mediumblob','longblob'];

        if (in_array($c_tp, $intdata) || in_array($c_tp, $doudata)) {
            echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
            <input type="text" class="form-control" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '"></div>';
        } elseif (in_array($c_tp, $strdata) || in_array($c_tp, $blodata)) {
            echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
            <textarea class="form-control" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '"></textarea></div>';
        }
    }

    public function get_enum_values(string $tble, string $field): array {
        $tble  = $this->validateIdentifier($tble);
        $field = $this->validateIdentifier($field);
        $stmt = $this->connection->prepare("SHOW COLUMNS FROM `{$tble}` WHERE Field = :f");
        $stmt->execute([':f' => $field]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return [];
        preg_match("/^enum\(\'(.*)\'\)$/", $row['Type'], $matches);
        return isset($matches[1]) ? explode("','", $matches[1]) : [];
    }

    public function enum_values(string $tble, string $field, $vals): void {
        $options = $this->get_enum_values($tble, $field);
        $frmp = ucfirst(str_replace("_", " ", str_replace(" id", "", $field)));
        echo '<div class="form-group"><label for="' . $this->e($field) . '">' . $this->e($frmp) . ':</label>
        <select class="form-select" id="' . $this->e($field) . '" name="' . $this->e($field) . '">';
        foreach ($options as $option) {
            $sel = ($vals === $option) ? ' SELECTED' : '';
            echo '<option value="' . $this->e($option) . '"' . $sel . '>' . $this->e($option) . '</option>';
        }
        echo '</select></div>';
    }

    public function joinCols(string $tble): void {
        $tble = $this->validateIdentifier($tble);
        $columns = $this->viewColumns($tble);
        $ncol = $this->getID($tble);

        // Configuraciones
        $sqlq = "SELECT * FROM table_column_settings WHERE table_name = :t";
        $stmt = $this->connection->prepare($sqlq);
        $stmt->execute([':t' => $tble]);
        $configs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($configs) {
            foreach ($configs as $rqu) {
                $c_nm = $rqu['col_name'];
                $c_tp = $rqu['col_type'];
                $i_tp = $rqu['input_type'];
                $c_tb = $rqu['j_table'] ?? null;
                $c_id = $rqu['j_id'] ?? null;
                $c_vl = $rqu['j_value'] ?? null;

                if ($c_nm === $ncol) continue;
                $remp = ucfirst(str_replace("_", " ", $c_nm));
                $frmp = str_replace(" id", "", $remp);

                if (in_array($c_tp, $this->itpi)) {
                    if ($i_tp == 3 && $c_tb) {
                        $c_tb = $this->validateIdentifier($c_tb);
                        $c_id = $this->validateIdentifier($c_id);
                        $c_vl = $this->validateIdentifier($c_vl);
                        echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                        <select class="form-select" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '">';
                        $qres = $this->connection->query("SELECT `{$c_id}`, `{$c_vl}` FROM `{$c_tb}`");
                        while ($opt = $qres->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . $this->e($opt[$c_id]) . '">' . $this->e($opt[$c_vl]) . '</option>';
                        }
                        echo '</select></div>';
                    } else {
                        echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                        <input type="text" class="form-control" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '"></div>';
                    }
                } elseif (in_array($c_tp, $this->itpc)) {
                    echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                    <input type="text" class="form-control" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '"></div>';
                } elseif (in_array($c_tp, $this->itpd)) {
                    echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                    <input type="date" class="form-control" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '"></div>';
                } elseif (in_array($c_tp, $this->itpv)) {
                    if ($c_nm === 'imagen') {
                        echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                        <input type="file" class="form-control" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '"></div>';
                    } else {
                        echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                        <input type="text" class="form-control" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '"></div>';
                    }
                } elseif (in_array($c_tp, $this->itpt)) {
                    echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                    <textarea class="form-control" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '"></textarea></div>';
                } elseif (in_array($c_tp, $this->itpe)) {
                    $values = $this->get_enum_values($tble, $c_nm);
                    echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                    <select class="form-select" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '">';
                    foreach ($values as $option) {
                        echo '<option value="' . $this->e($option) . '">' . $this->e($option) . '</option>';
                    }
                    echo '</select></div>';
                }
            }
        } else {
            foreach ($columns as $dtpe) {
                if ($dtpe->name === $ncol) continue;
                $remp = ucfirst(str_replace("_", " ", $dtpe->name));
                $frmp = str_replace(" id", "", $remp);

                if (in_array($dtpe->type, $this->itpi) || in_array($dtpe->type, $this->itpc) || in_array($dtpe->type, $this->itpv)) {
                    echo '<div class="form-group"><label for="' . $this->e($dtpe->name) . '">' . $this->e($frmp) . ':</label>
                    <input type="text" class="form-control" id="' . $this->e($dtpe->name) . '" name="' . $this->e($dtpe->name) . '"></div>';
                } elseif (in_array($dtpe->type, $this->itpd)) {
                    echo '<div class="form-group"><label for="' . $this->e($dtpe->name) . '">' . $this->e($frmp) . ':</label>
                    <input type="date" class="form-control" id="' . $this->e($dtpe->name) . '" name="' . $this->e($dtpe->name) . '"></div>';
                } elseif (in_array($dtpe->type, $this->itpt)) {
                    echo '<div class="form-group"><label for="' . $this->e($dtpe->name) . '">' . $this->e($frmp) . ':</label>
                    <textarea class="form-control" id="' . $this->e($dtpe->name) . '" name="' . $this->e($dtpe->name) . '"></textarea></div>';
                } elseif (in_array($dtpe->type, $this->itpe)) {
                    $values = $this->get_enum_values($tble, $dtpe->name);
                    echo '<div class="form-group"><label for="' . $this->e($dtpe->name) . '">' . $this->e($frmp) . ':</label>
                    <select class="form-select" id="' . $this->e($dtpe->name) . '" name="' . $this->e($dtpe->name) . '">';
                    foreach ($values as $option) {
                        echo '<option value="' . $this->e($option) . '">' . $this->e($option) . '</option>';
                    }
                    echo '</select></div>';
                }
            }
        }
    }

    /* =========================================================
     *  CRUD: INSERT / UPDATE / DELETE
     * ========================================================= */

    /** INSERT seguro con PDO (reemplaza addData e insertData) */
    public function addData(string $tble): void {
        $tble  = $this->validateIdentifier($tble);
        $colID = $this->getID($tble);
        $colmns = $this->viewColumns($tble);

        if (isset($_POST['addrow'])) {
            $cols = [];
            $placeholders = [];
            $params = [];
            foreach ($colmns as $col) {
                if ($col->name === $colID) continue;
                $cols[] = "`{$col->name}`";
                $placeholders[] = ":{$col->name}";
                $params[":{$col->name}"] = $_POST[$col->name] ?? null;
            }
            $sql = "INSERT INTO `{$tble}` (" . implode(", ", $cols) . ") VALUES (" . implode(", ", $placeholders) . ")";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);

            $_SESSION['success'] = 'The data was added correctly';
            header('Location: ' . $this->pgname . '?cms=table_crud&w=list&tbl=' . urlencode($tble));
            exit;
        }

        echo '<form method="post" class="form-horizontal" role="form" id="add_' . $this->e($tble) . '" enctype="multipart/form-data">';
        $this->joinCols($tble);
        echo '<div class="form-group">
        <button type="submit" id="addrow" name="addrow" class="btn btn-primary"><span class="fas fa-plus-square"></span> Add</button>
        </div></form>';
    }

    /** Alias de compatibilidad */
    public function insertData(string $tble): void {
        $this->addData($tble);
    }

    /** UPDATE seguro con PDO (reemplaza updateScript, updateData y editData) */
    public function updateData(string $tble, $id = null): void {
        $tble  = $this->validateIdentifier($tble);
        $colID = $this->getID($tble);
        $colmns = $this->viewColumns($tble);

        if ($id === null) $id = $_GET['id'] ?? null;

        if (isset($_POST['editrow'])) {
            $set = [];
            $params = [];
            foreach ($colmns as $col) {
                if ($col->name === $colID) continue;
                $set[] = "`{$col->name}` = :{$col->name}";
                $params[":{$col->name}"] = $_POST[$col->name] ?? null;
            }
            $params[":id"] = $id;
            $sql = "UPDATE `{$tble}` SET " . implode(", ", $set) . " WHERE `{$colID}` = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);

            $_SESSION['success'] = 'The data was updated correctly.';
            echo "<script>window.onload = function() { location.href = '" . $this->e($this->pgname) . "?cms=table_crud&w=list&tbl=" . $this->e($tble) . "'; };</script>";
            exit;
        }
    }

    public function updateScript(string $tble): void { $this->updateData($tble); }

    /** DELETE seguro con PDO */
    public function deleteData(string $tble, $id): void {
        $tble  = $this->validateIdentifier($tble);
        $colID = $this->getID($tble);

        if (isset($_POST['deleterow'])) {
            $stmt = $this->connection->prepare("DELETE FROM `{$tble}` WHERE `{$colID}` = :id");
            $stmt->execute([':id' => $id]);
            $_SESSION['success'] = 'Record deleted successfully.';
            header('Location: ' . $this->pgname . '?cms=table_crud&w=list&tbl=' . urlencode($tble));
            exit;
        }

        $stmt = $this->connection->prepare("SELECT * FROM `{$tble}` WHERE `{$colID}` = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        echo '<form class="row form-horizontal" role="form" id="delete_' . $this->e($tble) . '" method="POST">';
        if ($row) {
            foreach ($row as $name => $value) {
                if ($name === $colID) continue;
                $remp = str_replace("_", " ", $name);
                echo '<div class="form-group">
                <label for="' . $this->e($name) . '">' . $this->e(ucfirst($remp)) . ':</label>
                <input type="text" class="form-control" id="' . $this->e($name) . '" name="' . $this->e($name) . '" value="' . $this->e($value) . '" readonly>
                </div>';
            }
        }
        echo '<div class="form-group">
        <button type="submit" id="deleterow" name="deleterow" class="btn btn-danger"><span class="fas fa-trash-alt"></span> Delete</button>
        </div></form>';
    }

    /* =========================================================
     *  FORMULARIOS DE EDICIÓN
     * ========================================================= */

    public function inputQEdit(string $tble, $id): void {
        $tble  = $this->validateIdentifier($tble);
        $colID = $this->getID($tble);
        $columns = $this->viewColumns($tble);

        // Obtener fila actual
        $stmt = $this->connection->prepare("SELECT * FROM `{$tble}` WHERE `{$colID}` = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) { echo '<p>Record not found.</p>'; return; }

        // Configuraciones
        $relSql = "SELECT * FROM table_column_settings WHERE table_name = :t";
        $relStmt = $this->connection->prepare($relSql);
        $relStmt->execute([':t' => $tble]);
        $configs = $relStmt->fetchAll(PDO::FETCH_ASSOC);

        echo '<form class="row form-horizontal" role="form" id="edit_' . $this->e($tble) . '" method="POST" enctype="multipart/form-data">';

        if ($configs) {
            foreach ($configs as $rqu) {
                $c_nm = $rqu['col_name'];
                $c_tp = $rqu['col_type'];
                $i_tp = $rqu['input_type'];
                $c_tb = $rqu['j_table'] ?? null;
                $c_id = $rqu['j_id'] ?? null;
                $c_vl = $rqu['j_value'] ?? null;
                if ($c_nm === $colID) continue;
                $cdta = $row[$c_nm] ?? '';
                $remp = ucfirst(str_replace("_", " ", $c_nm));
                $frmp = str_replace(" id", "", $remp);

                if (in_array($c_tp, $this->itpi)) {
                    if ($i_tp == 3 && $c_tb) {
                        $c_tb = $this->validateIdentifier($c_tb);
                        $c_id = $this->validateIdentifier($c_id);
                        $c_vl = $this->validateIdentifier($c_vl);
                        echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                        <select class="form-select" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '">';
                        $qres = $this->connection->query("SELECT `{$c_id}`, `{$c_vl}` FROM `{$c_tb}`");
                        while ($rqj = $qres->fetch(PDO::FETCH_ASSOC)) {
                            $sel = ($cdta == $rqj[$c_id]) ? ' selected' : '';
                            echo '<option value="' . $this->e($rqj[$c_id]) . '"' . $sel . '>' . $this->e($rqj[$c_vl]) . '</option>';
                        }
                        echo '</select></div>';
                    } else {
                        echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                        <input type="text" class="form-control" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '" value="' . $this->e($cdta) . '"></div>';
                    }
                } elseif (in_array($c_tp, $this->itpc) || in_array($c_tp, $this->itpv)) {
                    echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                    <input type="text" class="form-control" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '" value="' . $this->e($cdta) . '"></div>';
                } elseif (in_array($c_tp, $this->itpd)) {
                    echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                    <input type="date" class="form-control" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '" value="' . $this->e($cdta) . '"></div>';
                } elseif (in_array($c_tp, $this->itpt)) {
                    echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                    <textarea class="form-control" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '">' . $this->e($cdta) . '</textarea></div>';
                } elseif (in_array($c_tp, $this->itpe)) {
                    $options = $this->get_enum_values($tble, $c_nm);
                    echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                    <select class="form-select" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '">';
                    foreach ($options as $option) {
                        $sel = ($cdta === $option) ? ' selected' : '';
                        echo '<option value="' . $this->e($option) . '"' . $sel . '>' . $this->e($option) . '</option>';
                    }
                    echo '</select></div>';
                }
            }
        } else {
            foreach ($columns as $finfo) {
                if ($finfo->name === $colID) continue;
                $c_nm = $finfo->name;
                $c_tp = $finfo->type;
                $cdta = $row[$c_nm] ?? '';
                $remp = ucfirst(str_replace("_", " ", $c_nm));
                $frmp = str_replace(" id", "", $remp);

                if (in_array($c_tp, $this->itpi) || in_array($c_tp, $this->itpc) || in_array($c_tp, $this->itpv)) {
                    echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                    <input type="text" class="form-control" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '" value="' . $this->e($cdta) . '"></div>';
                } elseif (in_array($c_tp, $this->itpd)) {
                    echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                    <input type="date" class="form-control" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '" value="' . $this->e($cdta) . '"></div>';
                } elseif (in_array($c_tp, $this->itpt)) {
                    echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                    <textarea class="form-control" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '">' . $this->e($cdta) . '</textarea></div>';
                } elseif (in_array($c_tp, $this->itpe)) {
                    $options = $this->get_enum_values($tble, $c_nm);
                    echo '<div class="form-group"><label for="' . $this->e($c_nm) . '">' . $this->e($frmp) . ':</label>
                    <select class="form-select" id="' . $this->e($c_nm) . '" name="' . $this->e($c_nm) . '">';
                    foreach ($options as $option) {
                        $sel = ($cdta === $option) ? ' selected' : '';
                        echo '<option value="' . $this->e($option) . '"' . $sel . '>' . $this->e($option) . '</option>';
                    }
                    echo '</select></div>';
                }
            }
        }

        echo '<div class="form-group">
        <button type="submit" id="editrow" name="editrow" class="btn btn-primary"><span class="fas fa-edit"></span> Update</button>
        </div></form>';
    }

    public function editColm(string $tble, $id): void {
        $tble  = $this->validateIdentifier($tble);
        $colID = $this->getID($tble);

        $stmt = $this->connection->prepare("SELECT * FROM `{$tble}` WHERE `{$colID}` = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) { echo '<p>Record not found.</p>'; return; }

        $ttle = str_replace("_", " ", $tble);
        echo '<form class="row form-horizontal" role="form" method="POST" enctype="multipart/form-data">
        <fieldset><legend>' . $this->e(ucfirst($ttle)) . '</legend>';

        foreach ($row as $name => $value) {
            if ($name === $colID) continue;
            $remp  = ucfirst(str_replace("_", " ", $name));
            $premp = str_replace(" id", " ", $remp);
            echo '<div class="form-group">
            <label for="' . $this->e($name) . '">' . $this->e($premp) . ':</label>
            <input id="' . $this->e($name) . '" name="' . $this->e($name) . '" value="' . $this->e($value) . '" class="form-control input-md" type="text">
            <span class="help-block">' . $this->e($name) . '</span>
            </div>';
        }

        echo '<div class="form-group">
        <button type="button" id="editrow" name="editrow" class="btn btn-primary"><span class="fas fa-edit"></span> Edit</button>
        </div></fieldset></form>';
    }

    public function addColm(string $tble): void {
        $tble = $this->validateIdentifier($tble);
        $columns = $this->viewColumns($tble);

        echo '<form class="form-horizontal"><fieldset><legend>' . $this->e($tble) . '</legend>';
        foreach ($columns as $meta) {
            $remp = str_replace("_", " ", $meta->name);
            echo '<div class="form-group">
            <label class="col-md-3 control-label">' . $this->e(ucfirst($remp)) . '</label>
            <div class="col-md-8">
            <input id="' . $this->e($meta->name) . '" name="' . $this->e($meta->name) . '" placeholder="' . $this->e(ucfirst($remp)) . '" class="form-control input-md" type="text">
            <span class="help-block">' . $this->e($meta->name) . '</span>
            </div>
            </div>';
        }
        echo '<div class="form-group"><div class="col-md-4">
        <button id="submit" name="submit" class="btn btn-primary">Save</button>
        </div></div></fieldset></form>';
    }

    /* =========================================================
     *  MÉTODOS AUXILIARES (compatibilidad)
     * ========================================================= */

    public function addQuery(string $tble): void {
        $tble = $this->validateIdentifier($tble);
        $columns = $this->viewColumns($tble);
        $colID = $this->getID($tble);
        echo '<form class="row form-horizontal" role="form" method="post" id="query_' . $this->e($tble) . '">';
        foreach ($columns as $finfo) {
            if ($finfo->name === $colID) continue;
            $remp = str_replace("_", " ", $finfo->name);
            echo '<div class="form-group">
            <label for="' . $this->e($finfo->name) . '">' . $this->e(ucfirst($remp)) . ':</label>
            <textarea class="form-control" id="' . $this->e($finfo->name) . '" name="' . $this->e($finfo->name) . '"></textarea>
            </div>';
        }
        echo '<div class="form-group">
        <button type="submit" id="addqueries" name="addqueries" class="btn btn-primary"><span class="fas fa-plus-square"></span> Add queries</button>
        </div></form>';
    }

    public function addpost(string $tble): string {
        $tble = $this->validateIdentifier($tble);
        $colID = $this->getID($tble);
        $columns = $this->viewColumns($tble);
        $lines = [];
        foreach ($columns as $c) {
            if ($c->name === $colID) continue;
            $lines[] = '$' . $c->name . ' = $_POST["' . $c->name . '"];';
        }
        return implode("\n", $lines);
    }

    public function updateInfo(string $tble): string {
        $tble = $this->validateIdentifier($tble);
        $colID = $this->getID($tble);
        $columns = $this->viewColumns($tble);
        $lines = [];
        foreach ($columns as $c) {
            if ($c->name === $colID) continue;
            $lines[] = "`{$c->name}` = :{$c->name}";
        }
        return implode(", ", $lines);
    }

    public function ifMpty(string $tble): string {
        $tble = $this->validateIdentifier($tble);
        $colID = $this->getID($tble);
        $columns = $this->viewColumns($tble);
        $checks = [];
        foreach ($columns as $c) {
            if ($c->name === $colID) continue;
            $checks[] = '!empty($_POST["' . $c->name . '"])';
        }
        return implode(" && ", $checks);
    }

    public function ifEmpty(string $tble): string { return $this->ifMpty($tble); }

    public function addTtl(string $tble): string {
        $tble = $this->validateIdentifier($tble);
        $colID = $this->getID($tble);
        $columns = $this->viewColumns($tble);
        $cols = [];
        foreach ($columns as $c) {
            if ($c->name === $colID) continue;
            $cols[] = '`' . $c->name . '`';
        }
        return implode(" , ", $cols);
    }

    public function addTPost(string $tble): string {
        $tble = $this->validateIdentifier($tble);
        $colID = $this->getID($tble);
        $columns = $this->viewColumns($tble);
        $cols = [];
        foreach ($columns as $c) {
            if ($c->name === $colID) continue;
            $cols[] = ':' . $c->name;
        }
        return implode(" , ", $cols);
    }

    public function supdateData(string $tble): void {
        $tble = $this->validateIdentifier($tble);
        $columns = $this->viewColumns($tble);
        $vars = [];
        foreach ($columns as $c) {
            $vars[] = $c->name . ': $' . $c->name;
        }
        echo implode(", ", $vars);
    }

    public function supdateD(string $tble): void {
        $tble = $this->validateIdentifier($tble);
        $columns = $this->viewColumns($tble);
        $vars = [];
        foreach ($columns as $c) {
            $vars[] = $c->name . ':' . $c->name;
        }
        echo implode(", ", $vars);
    }

    public function addReq(string $tble): string {
        $tble = $this->validateIdentifier($tble);
        $colID = $this->getID($tble);
        $columns = $this->viewColumns($tble);
        foreach ($columns as $c) {
            if ($c->name === $colID) continue;
            return '$' . $c->name . ' = $_REQUEST["' . $c->name . '"];';
        }
        return '';
    }

    public function addReqch(string $tble): string {
        $tble = $this->validateIdentifier($tble);
        $colID = $this->getID($tble);
        $columns = $this->viewColumns($tble);
        $checks = [];
        foreach ($columns as $c) {
            if ($c->name === $colID) continue;
            $checks[] = "' " . $c->name . " : $" . $c->name . " '";
        }
        return implode(" , ", $checks);
    }

    public function addvTtl(string $tble): string {
        $tble = $this->validateIdentifier($tble);
        $colID = $this->getID($tble);
        $columns = $this->viewColumns($tble);
        $checks = [];
        foreach ($columns as $c) {
            if ($c->name === $colID) continue;
            $checks[] = ':' . $c->name;
        }
        return implode(" , ", $checks);
    }

    public function sValues(string $tble): void {
        $tble = $this->validateIdentifier($tble);
        $colID = $this->getID($tble);
        $columns = $this->viewColumns($tble);
        foreach ($columns as $c) {
            if ($c->name === $colID) continue;
            echo 'var ' . $c->name . ' = $("#' . $this->e($c->name) . '").val();' . "\n";
        }
    }

    public function searchData(string $tble, string $col, string $str): void {
        $tble = $this->validateIdentifier($tble);
        $col  = $this->validateIdentifier($col);

        $total_pages = (int)$this->connection->query("SELECT COUNT(*) FROM `{$tble}`")->fetchColumn();
        $colmns = $this->viewColumns($tble);
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $num_results_on_page = 10;
        $calc_page = ($page - 1) * $num_results_on_page;

        $sql = "SELECT * FROM `{$tble}` WHERE `{$col}` LIKE :search LIMIT :offset, :limit";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':search', '%' . $str . '%', PDO::PARAM_STR);
        $stmt->bindValue(':offset', $calc_page, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $num_results_on_page, PDO::PARAM_INT);
        $stmt->execute();

        echo '<table class="table"><thead><tr><th></th>';
        foreach ($colmns as $colmn) {
            $tremp = ucfirst(str_replace("_", " ", $colmn->name));
            $remp  = str_replace(" id", " ", $tremp);
            echo '<th>' . $this->e($remp) . '</th>';
        }
        echo '</tr></thead><tbody>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            $firstVal = $this->e(reset($row));
            echo '<td>
            <a id="editrow" name="editrow" class="btn btn-success" href="index.php?w=edit&amp;tbl=' . $this->e($tble) . '&amp;id=' . $firstVal . '">Editar</a>
            <a id="deleterow" name="deleterow" class="btn btn-danger" href="index.php?w=delete&amp;tbl=' . $this->e($tble) . '&amp;id=' . $firstVal . '">Borrar</a>
            </td>';
        foreach ($colmns as $colmn) {
            $fd = $row[$colmn->name] ?? '';
            $relSql = "SELECT j_table, j_id, j_value FROM table_queries
            WHERE name_table = :t AND col_name = :c AND input_type IS NOT NULL";
            $relStmt = $this->connection->prepare($relSql);
            $relStmt->execute([':t' => $tble, ':c' => $colmn->name]);
            $trow = $relStmt->fetch(PDO::FETCH_ASSOC);
            if ($trow) {
                $tb = $this->validateIdentifier($trow['j_table']);
                $id = $this->validateIdentifier($trow['j_id']);
                $val = $this->validateIdentifier($trow['j_value']);
                $q = "SELECT `{$val}` FROM `{$tb}` WHERE `{$id}` = :fd LIMIT 1";
                $tow = $this->selectData($q, [':fd' => $fd])->fetch(PDO::FETCH_ASSOC);
                echo '<td><a class="goto" href="search.php?w=find&amp;tbl=' . $this->e($tb) . '&amp;id=' . $this->e($fd) . '">' . $this->e($tow[$val] ?? '') . '</a></td>';
            } else {
                echo '<td>' . $this->e($fd) . '</td>';
            }
        }
        echo '</tr>';
        }
        echo '</tbody></table>';

        $totalPageCount = (int)ceil($total_pages / $num_results_on_page);
        if ($totalPageCount > 0) {
            echo '<nav aria-label="Page navigation"><ul class="pagination justify-content-center mx-auto">';
            if ($page > 1) echo '<li class="prev"><a href="search.php?page=' . ($page - 1) . '">Anterior</a></li>';
            if ($page > 3) echo '<li class="start"><a href="search.php?page=1">1</a></li><li class="dots">...</li>';
            if ($page - 2 > 0) echo '<li class="page"><a href="search.php?page=' . ($page - 2) . '">' . ($page - 2) . '</a></li>';
            if ($page - 1 > 0) echo '<li class="page"><a href="search.php?page=' . ($page - 1) . '">' . ($page - 1) . '</a></li>';
            echo '<li class="currentpage"><a href="search.php?page=' . $page . '">' . $page . '</a></li>';
            if ($page + 1 <= $totalPageCount) echo '<li class="page"><a href="search.php?page=' . ($page + 1) . '">' . ($page + 1) . '</a></li>';
            if ($page + 2 <= $totalPageCount) echo '<li class="page"><a href="search.php?page=' . ($page + 2) . '">' . ($page + 2) . '</a></li>';
            if ($page < $totalPageCount - 2) echo '<li class="dots">...</li><li class="end"><a href="search.php?page=' . $totalPageCount . '">' . $totalPageCount . '</a></li>';
            if ($page < $totalPageCount) echo '<li class="next"><a href="search.php?page=' . ($page + 1) . '">Siguiente</a></li>';
            echo '</ul></nav>';
        }
    }

    /** Alias de compatibilidad para el método antiguo */
    public function editData(string $tble, $id): void {
        $this->inputQEdit($tble, $id);
    }
}
?>
