<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Checklist</title>
    <style>
        html,
        body {
            margin: 0.3cm;
            padding: 0.5;
            font-family: "Roboto", -apple-system, "San Francisco", "Segoe UI", "Helvetica Neue", sans-serif;
            font-size: 12pt;
            background-color: #fff;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        div.page-break {
            page-break-after: always;
        }

        /* For tables in the document */
        table {
            /* Avoid page breaks inside */
            page-break-inside: avoid;
        }

        .page {
            width: 100%;
            padding-left: 1cm;
            padding-top: 1cm;
            padding-right: 1cm;
            padding-bottom: 1cm;
            background: #fff;
            outline: 0;
        }

        tbody tr:nth-child(odd) {
            background-color: #ccc;
        }

    </style>
</head>

<body>
    <div>
        <table style="font-size:13px; border-spacing: 0px;" width="100%">
            <thead>
                <tr>
                    <th colspan="4" style="font-size:20px">Checklist</th>
                </tr>
                <tr>
                    <td colspan="2" style="border-top: 3px solid #000; line-height: 20px;"><b>Unidade:</b></td>
                    <td colspan="2" style="border-top: 3px solid #000; line-height: 20px;"><b>Data:</b></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($itens as $item)
                    @if ($item->isMacroitem())
                        <tr>
                            <td colspan="4"
                                style="border-bottom: 1px solid #000; border-top: 3px solid #000; line-height:23px;">
                                <b>{{ $item->nome }}</b>
                                @if ($item->foto == 'S')
                                    &nbsp;<i>(foto obrigatória)</i>
                                @endif
                            </td>
                        </tr>
                        @foreach ($item->subitens as $subitem)
                            <tr>
                                <td style="padding-left: 20px; border-bottom: 1px solid #000;  line-height:23px;">
                                    {{ $subitem->nome }}
                                    @if ($subitem->foto == 'S')
                                        &nbsp;<i>(foto obrigatória)</i>
                                    @endif
                                </td>
                                <td style="border-bottom: 1px solid #000; ">
                                    <div
                                        style="height: 13px; width: 15px; border: 2px solid #000; display: inline-block; margin-top: 3px;">
                                    </div>
                                    <label>
                                        N/A
                                    </label>
                                </td>
                                <td style="border-bottom: 1px solid #000;">
                                    <div
                                        style="height: 13px; width: 15px; border: 2px solid #000; display: inline-block;margin-top: 3px;">
                                    </div>
                                    <label>
                                        Conforme
                                    </label>
                                </td>
                                <td style="border-bottom: 1px solid #000;">
                                    <label>
                                        <div
                                            style="height: 13px; width: 15px; border: 2px solid #000; display: inline-block; margin-top: 3px;">
                                        </div>

                                        Inconforme
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        <table style="font-size:13px; border-spacing: 0px;" width="100%">
            <thead>
                <tr>
                    <th colspan="2" style="font-size:20px">Checklist - Demandas</th>
                </tr>
                <tr>
                    <td width="10%"
                        style="border-top: 3px solid #000; border-bottom: 3px solid #000; line-height: 20px;">
                        <b>Unidade:</b>
                    </td>
                    <td width="90%"
                        style=" border-top: 3px solid #000; border-bottom: 3px solid #000; line-height: 20px;">
                        <b>Data:</b>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="border-right: 1px solid #000;">Destino/Sistema</th>
                    <th>Anotações</th>
                </tr>
                @for ($i = 0; $i <= 12; $i++)
                    <tr>
                        <td style="border-bottom: 3px solid #000; border-right: 1px solid #000; line-height: 68px;">
                            &nbsp;</td>
                        <td style="border-bottom: 3px solid #000; line-height: 68px;">&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

</body>

</html>
