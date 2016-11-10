<?php

namespace SimpleORM\app\model;

class AppModel extends Model
{
    public function where($dados = null)
    {
        if (!empty($dados)) {
            foreach ($dados as $k => $v) {
                if (is_int($v)) {
                    $where[] = "{$k} = {$v}";
                } else {
                    $where[] = "{$k} = '{$v}'";
                }
            }

            return (count($where) > 1) ? implode(' AND ', $where) : ' AND '.$where[0];
        }

        return null;
    }
}