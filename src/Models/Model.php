<?php

namespace App\Models;

use App\Core\Database;

class Model extends Database {
    private $database;

    public function create () {
        $cols = array();
        $vals = array();
        $inters = array();

        foreach ($this as $col => $val) {
            if ($val !== null && $col !== 'table' && $col !== 'database') {
                $cols[] = $col;
                $vals[] = $val;
                $inters[] = "?";
            }
        }

        $colsList = implode(', ', $cols);
        $intersList = implode(', ', $inters);

        return $this -> runQuery("INSERT INTO {$this -> table} (" . $colsList . ") VALUES (" . $intersList . ")", $vals);
    }

    public function read (array $columns = null, array $criteria = null, array $join = null, int $limit = null, int $offset = null, string $count = null, string $fetch = null) {
        $params = array(
            'columns' => $columns,
            'criteria' => $criteria,
            'join' => $join,
            'limit' => $limit,
            'offset' => $offset,
            'count' => $count,
            'fetch' => $fetch
        );

        $criteriaString = "";
        $columnsString = "*";
        $joinString = "";
        $limitString = "";
        $offsetString = "";
        $criterionValues = null;

        foreach ($params as $key => $value) {
            if ($value) switch ($key) {
                case 'columns':
                    $columns = array();

                    foreach ($value as $column) {
                        $columns[] = $column;
                    }

                    $columnsString = implode(", ", $columns);
                    break;

                case 'criteria':
                    $criterionColumns = array();
                    $criterionValues = array();

                    foreach ($value as $criterionColumn => $criterionValue) {
                        $criterionColumns[] = "$criterionColumn ?";
                        $criterionValues[] = $criterionValue;
                    }

                    $criteriaString = " WHERE " . implode(" AND ", $criterionColumns);
                    break;

                case 'join':
                    $string = null;
                    foreach ($value['tables'] as $val) {
                        $joinString .= " JOIN " . $val['table2'][0] . " " . $val['table2'][1] . " ON " . $val['table1'][1] . "." . $val['table1'][2] . " = " . $val['table2'][1] . "." . $val['table2'][2];
                    }
                    break;

                case 'limit':
                    $limitString = " LIMIT $limit";
                    break;

                case 'offset':
                    if ($params['limit']) $offsetString = " OFFSET $offset";
                    break;

                case 'count':
                    $columnsString = "COUNT($columnsString) AS $value";
                    break;

            }
        }

        $sqlQuery = "SELECT " . $columnsString . " FROM {$this -> table} " . ((isset($join)) ? $join['table'] : null) . $joinString . $criteriaString . $limitString . $offsetString;
        $query = $this -> runQuery($sqlQuery, $criterionValues);

        if ($fetch == 'fetch') return $query -> fetch();
        else if ($fetch === 'fetchAll') return $query -> fetchAll();
        else return null;
    }

    public function update (array $id) {
        $fields = array();
        foreach ($this as $col => $val) {
            if ($val !== null && $col !== 'table' && $col !== 'database') $fields[] = "$col = '$val'";
        }

        $fieldsList = implode(', ', $fields);

        return $this -> runQuery("UPDATE {$this -> table} SET {$fieldsList} WHERE " . key($id) . " = " . reset($id));
    }

    public function delete (int $id, string $idField) {
        $sqlQuery = "DELETE FROM {$this -> table} WHERE $idField = $id";
        return $this -> runQuery($sqlQuery);
    }

    public function runQuery (string $req, array $attrs = null) {
        $this -> database = Database::getInstance();

        if ($attrs !== null) {
            $query = $this -> database -> prepare($req);
            $query -> execute($attrs);

            return $query;

        } else return $this -> database -> query($req);
    }

    public function hydrate (mixed $data) {
        foreach ($data as $col => $val) {
            $setter = 'set' . ucfirst($col);

            if (method_exists($this, $setter)) $this -> $setter($val);
        }

        return $this;
    }
}