<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DemandaSistema extends Model
{
    public function getCategoriasAttribute()
    {
        if($this->categorias_table && $this->categorias_campo_id && $this->categorias_campo_texto)
        {
            $db = DB::connection($this->conexao);
            $sql = 'select '. $this->categorias_campo_id .' as id, '. $this->categorias_campo_texto .' as nome
                    from '. $this->categorias_table .'
                    where 1=1 ';

            if($this->categorias_filtros) $sql .= ' AND '. $this->categorias_filtros;

            $sql .= ' ORDER BY '. $this->categorias_campo_texto . ' ASC';

            $dados = $db->select($sql);

            return collect($dados);
        }

        return null;
    }

    public function subcategorias($categoria=null)
    {
        return $this->getSubcategoriasAttribute($categoria);
    }

    public function getSubcategoriasAttribute($categoria=null)
    {
        if($this->subcategorias_table && $this->subcategorias_campo_id && $this->subcategorias_campo_texto)
        {
            $sql_categoria_select = ($this->itens_campo_id_categoria) ? ', '. $this->itens_campo_id_categoria .' as categoria' : '';

            $db = DB::connection($this->conexao);
            $sql = 'select '. $this->subcategorias_campo_id .' as id, '. $this->subcategorias_campo_texto .' as nome '.$sql_categoria_select.'
                    from '. $this->subcategorias_table .'
                    where 1=1 ';

            if($this->subcategorias_filtros) $sql .= ' AND '. $this->subcategorias_filtros;

            if($categoria && $this->itens_campo_id_categoria) $sql .= ' AND '. $this->itens_campo_id_categoria . ' = ?';

            $sql .= ' ORDER BY '. $this->subcategorias_campo_texto . ' ASC';

            if($categoria && $this->itens_campo_id_categoria)
            {
                $dados = $db->select($sql,[$categoria]);
            }
            else
            {
                $dados = $db->select($sql);
            }

            return collect($dados);
        }

        return null;
    }

    public function itens($categoria=null, $subcategoria=null)
    {
        return $this->getItensAttribute($categoria,$subcategoria);
    }

    public function getItensAttribute($categoria=null, $subcategoria=null)
    {
        if($this->itens_table && $this->itens_campo_id && $this->itens_campo_texto)
        {
            $sql_categoria_select = ($this->itens_campo_id_categoria) ? ', '. $this->itens_campo_id_categoria .' as categoria' : '';
            $sql_subcategoria_select = ($this->itens_campo_id_subcategoria) ? ', '. $this->itens_campo_id_subcategoria .' as subcategoria' : '';

            $db = DB::connection($this->conexao);
            $sql = 'select '. $this->itens_campo_id .' as id, '. $this->itens_campo_texto .' as nome '.$sql_categoria_select.' '.$sql_subcategoria_select.'
                    from '. $this->itens_table .'
                    where 1=1 ';

            if($this->itens_filtros) $sql .= ' AND '. $this->itens_filtros;

            if($categoria && $this->itens_campo_id_categoria) $sql .= ' AND '. $this->itens_campo_id_categoria . ' = ? ';
            if($subcategoria && $this->itens_campo_id_subcategoria) $sql .= ' AND '. $this->itens_campo_id_subcategoria . ' = ? ';

            $sql .= ' ORDER BY '. $this->itens_campo_texto . ' ASC';

            if(($categoria && $this->itens_campo_id_categoria) || ($subcategoria && $this->itens_campo_id_subcategoria))
            {

                $params = [];
                if($categoria) array_push($params,$categoria);
                if($subcategoria) array_push($params,$subcategoria);

                $dados = $db->select($sql, $params);
            }
            else
            {
                $dados = $db->select($sql);
            }

            return collect($dados);
        }

        return null;
    }

    public function getItemById($id) {
        $db = DB::connection($this->conexao);

        $sql = 'select '. $this->itens_campo_id .' as id, '. $this->itens_campo_texto .' as nome
                from '. $this->itens_table .'
                where '. $this->itens_campo_id.'=' . $id;

        //ddd($sql);

        return collect($db->select($sql))->first();
    }
}
