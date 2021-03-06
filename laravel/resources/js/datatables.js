require( 'jszip' );
require( 'pdfmake' );
require( 'datatables.net-bs4' );
require( 'datatables.net-autofill-bs4' );
require( 'datatables.net-buttons-bs4' );
require( 'datatables.net-buttons-bs4/js/buttons.bootstrap4' );
require( 'datatables.net-colreorder-bs4' );
require( 'datatables.net-datetime' );
require( 'datatables.net-fixedcolumns-bs4' );
require( 'datatables.net-fixedheader-bs4' );
require( 'datatables.net-keytable-bs4' );
require( 'datatables.net-responsive-bs4' );
require( 'datatables.net-rowgroup-bs4' );
require( 'datatables.net-rowreorder-bs4' );
require( 'datatables.net-scroller-bs4' );
require( 'datatables.net-searchbuilder-bs4' );
require( 'datatables.net-searchpanes-bs4' );
require( 'datatables.net-select-bs4' );



window.DATATABLES_RENDER_SITUACAO = (data, type, row, meta) => {
    let saida = ``;

    if(data)
        saida = `<span class="badge badge-success">Ativo</span>`;
    else
        saida = `<span class="badge badge-light">Inativo</span>`;

    return  saida;
}

window.DATATABLES_RENDER_SITUACAO_DEMANDA = (data, type, row, meta) => {

    switch(data)
    {
        case 'A':
            return `<span class="badge badge-light">Aguardando finalização checklist vinculado</span>`;
            break;

        case 'P':
            return `<span class="badge badge-light">Aguardando integração</span>`;
            break;

        case 'C':
            return `<span class="badge badge-info">Integração iniciada</span>`;
            break;
    }

    return  data;
}

window.DATATABLES_RENDER_SITUACAO_DEMANDA_TRATAR = (data, type, row, meta) => {
    if(row['respondida'])
    {
        switch(data)
        {
            case 'A':
                return `<span class="badge badge-light">Aguardando finalização checklist vinculado</span>`;
                break;

            case 'P':
                return `<span class="badge badge-light">Aguardando integração</span>`;
                break;

            case 'C':
                return `<span class="badge badge-success">Respondida</span>`;
                break;
        }
    }
    else
    {
        return `<span class="badge badge-light">Aguardando resposta</span>`;
    }

    return  data;
}

window.DATATABLES_TIPO_AGENDAMENTO = (data, type, row, meta) => {

    if(typeof data == "object")
        row = data;

    let saida = `<span style="width: 16px; height: 16px; margin-right:5px; background-color: ${row.cor}" class="d-inline-block align-text-bottom"></span>${row.nome}`;
    return  saida;
}

window.DATATABLES_PROGRESSO_AZUL = (data, type, row, meta) => {
    let cor = (data >= 100) ? 'bg-info' : '';
    let saida = `<div class="progress md-progress mr-3 mb-0 border" style="height: 20px">
        <div class="progress-bar ${cor}" role="progressbar" style="width: ${data}%; height: 20px" aria-valuenow="${data}"
             aria-valuemin="0" aria-valuemax="100">${data}%
        </div>
    </div>`;
    return  saida;
}

window.DATATABLES_DATA_BR = (data, type, row, meta) => {
    if(data){
        $dt = moment(data, "YYYY-MM-DD");
        return  $dt.format('DD/MM/YYYY');
    } else {
        return '';
    }
}

window.DATATABLES_IDIOMA = {
    "emptyTable": "Nenhum registro encontrado",
    "info": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
    "infoEmpty": "Mostrando 0 até 0 de 0 registros",
    "infoFiltered": "(Filtrados de _MAX_ registros)",
    "infoThousands": ".",
    "loadingRecords": "Carregando...",
    "processing": "Processando...",
    "zeroRecords": "Nenhum registro encontrado",
    "search": "Pesquisar",
    "paginate": {
    "next": "Próximo",
        "previous": "Anterior",
        "first": "Primeiro",
        "last": "Último"
},
"aria": {
    "sortAscending": ": Ordenar colunas de forma ascendente",
        "sortDescending": ": Ordenar colunas de forma descendente"
},
"select": {
    "1": "%d linha selecionada",
        "rows": {
        "0": "Nenhuma linha selecionada",
            "1": "Selecionado 1 linha",
            "_": "Selecionado %d linhas"
    },
    "_": "%d linhas selecionadas",
        "cells": {
        "1": "1 célula selecionada",
            "_": "%d células selecionadas"
    },
    "columns": {
        "1": "1 coluna selecionada",
            "_": "%d colunas selecionadas"
    }
},
"buttons": {
    "copySuccess": {
        "1": "Uma linha copiada com sucesso",
            "_": "%d linhas copiadas com sucesso"
    },
    "collection": "Coleção  <span class=\"ui-button-icon-primary ui-icon ui-icon-triangle-1-s\"></span>",
        "colvis": "Visibilidade da Coluna",
        "colvisRestore": "Restaurar Visibilidade",
        "copy": "Copiar",
        "copyKeys": "Pressione ctrl ou u2318 + C para copiar os dados da tabela para a área de transferência do sistema. Para cancelar, clique nesta mensagem ou pressione Esc..",
        "copyTitle": "Copiar para a Área de Transferência",
        "csv": "CSV",
        "excel": "Excel",
        "pageLength": {
        "1": "Mostrar 1 registro",
            "-1": "Mostrar todos os registros",
            "_": "Mostrar %d registros"
    },
    "pdf": "PDF",
        "print": "Imprimir"
},
"autoFill": {
    "cancel": "Cancelar",
        "fill": "Preencher todas as células com",
        "fillHorizontal": "Preencher células horizontalmente",
        "fillVertical": "Preencher células verticalmente"
},
"lengthMenu": "Exibir _MENU_ resultados por página",
    "searchBuilder": {
    "add": "Adicionar Condição",
        "button": {
        "0": "Construtor de Pesquisa",
            "_": "Construtor de Pesquisa (%d)"
    },
    "clearAll": "Limpar Tudo",
        "condition": "Condição",
        "conditions": {
        "date": {
            "after": "Depois",
                "before": "Antes",
                "between": "Entre",
                "empty": "Vazio",
                "equals": "Igual",
                "not": "Não",
                "notBetween": "Não Entre",
                "notEmpty": "Não Vazio"
        },
        "number": {
            "between": "Entre",
                "empty": "Vazio",
                "equals": "Igual",
                "gt": "Maior Que",
                "gte": "Maior ou Igual a",
                "lt": "Menor Que",
                "lte": "Menor ou Igual a",
                "not": "Não",
                "notBetween": "Não Entre",
                "notEmpty": "Não Vazio"
        },
        "string": {
            "contains": "Contém",
                "empty": "Vazio",
                "endsWith": "Termina Com",
                "equals": "Igual",
                "not": "Não",
                "notEmpty": "Não Vazio",
                "startsWith": "Começa Com"
        },
        "array": {
            "contains": "Contém",
                "empty": "Vazio",
                "equals": "Igual à",
                "not": "Não",
                "notEmpty": "Não vazio",
                "without": "Não possui"
        }
    },
    "data": "Data",
        "deleteTitle": "Excluir regra de filtragem",
        "logicAnd": "E",
        "logicOr": "Ou",
        "title": {
        "0": "Construtor de Pesquisa",
            "_": "Construtor de Pesquisa (%d)"
    },
    "value": "Valor"
},
"searchPanes": {
    "clearMessage": "Limpar Tudo",
        "collapse": {
        "0": "Painéis de Pesquisa",
            "_": "Painéis de Pesquisa (%d)"
    },
    "count": "{total}",
        "countFiltered": "{shown} ({total})",
        "emptyPanes": "Nenhum Painel de Pesquisa",
        "loadMessage": "Carregando Painéis de Pesquisa...",
        "title": "Filtros Ativos"
},
"searchPlaceholder": "Digite um termo para pesquisar",
    "thousands": ".",
    "datetime": {
    "previous": "Anterior",
        "next": "Próximo",
        "hours": "Hora",
        "minutes": "Minuto",
        "seconds": "Segundo",
        "amPm": [
        "am",
        "pm"
    ],
        "unknown": "-"
},
"editor": {
    "close": "Fechar",
        "create": {
        "button": "Novo",
            "submit": "Criar",
            "title": "Criar novo registro"
    },
    "edit": {
        "button": "Editar",
            "submit": "Atualizar",
            "title": "Editar registro"
    },
    "error": {
        "system": "Ocorreu um erro no sistema (<a target=\"\\\"rel=\"nofollow\"href=\"\\\">Mais informações</a>)."
    },
    "multi": {
        "noMulti": "Essa entrada pode ser editada individualmente, mas não como parte do grupo",
            "restore": "Desfazer alterações",
            "title": "Multiplos valores",
            "info": "Os itens selecionados contêm valores diferentes para esta entrada. Para editar e definir todos os itens para esta entrada com o mesmo valor, clique ou toque aqui, caso contrário, eles manterão seus valores individuais."
    },
    "remove": {
        "button": "Remover",
            "confirm": {
            "1": "Tem certeza que quer deletar 1 linha?",
                "_": "Tem certeza que quer deletar %d linhas?"
        },
        "submit": "Remover",
            "title": "Remover registro"
    }
},
"decimal": ","
};

$.extend( true, $.fn.dataTable.defaults, {
    language: window.DATATABLES_IDIOMA
} );
