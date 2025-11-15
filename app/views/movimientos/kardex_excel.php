<?php
?>

<table border="1" cellspacing="0" cellpadding="4" style="border-collapse:collapse; font-family:Segoe UI, Arial, sans-serif; font-size:11px;">

    <!-- ENCABEZADO GYM -->
    <tr>
        <th colspan="11" style="text-align:center; font-size:16px; font-weight:bold; background-color:#d9ead3;">
            GYM SPORT CENTER
        </th>
    </tr>
    <tr>
        <th colspan="11" style="text-align:center; font-weight:bold; background-color:#f2f2f2;">
            TARJETA KARDEX
        </th>
    </tr>

    <!-- DATOS DEL PRODUCTO -->
    <tr>
        <td colspan="11">
            <strong>PRODUCTO:</strong>
            <?= htmlspecialchars(($producto['codigo'] ?? '') . ' ' . ($producto['nombre'] ?? '')) ?>
            &nbsp;&nbsp;&nbsp;
            <strong>UNIDAD:</strong>
            <?= htmlspecialchars($producto['unidad'] ?? '') ?>
            &nbsp;&nbsp;&nbsp;
            <strong>METODO:</strong>
            Primeras Entradas Primeras Salidas (PEPS)
        </td>
    </tr>

    <!-- CABECERA DE TABLA: PRIMERA FILA -->
    <tr style="background-color:#c6efce; font-weight:bold; text-align:center;">
        <td rowspan="2">FECHA</td>
        <td rowspan="2">CONCEPTO</td>

        <td colspan="3">ENTRADAS</td>
        <td colspan="3">SALIDAS</td>
        <td colspan="3">EXISTENCIAS</td>
    </tr>

    <!-- CABECERA DE TABLA: SEGUNDA FILA -->
    <tr style="background-color:#c6efce; font-weight:bold; text-align:center;">
        <td>U.</td><td>COSTO</td><td>TOTAL</td>
        <td>U.</td><td>COSTO</td><td>TOTAL</td>
        <td>U.</td><td>COSTO</td><td>TOTAL</td>
    </tr>

    <!-- CUERPO DEL KARDEX -->
    <?php foreach ($movs as $row): ?>
        <tr>
            <!-- FECHA -->
            <td style="text-align:center;">
                <?php
                    $f = $row['fecha'] ?? '';
                    echo $f ? date('d/m/Y', strtotime($f)) : '';
                ?>
            </td>

            <!-- CONCEPTO -->
            <td style="text-align:left;">
                <?= htmlspecialchars($row['concepto'] ?? '') ?>
            </td>

            <!-- ENTRADAS -->
            <td style="text-align:right;">
                <?= ($row['entrada_u'] != 0) ? number_format($row['entrada_u'], 2) : '' ?>
            </td>
            <td style="text-align:right;">
                <?= ($row['entrada_u'] != 0) ? number_format($row['entrada_costo'], 2) : '' ?>
            </td>
            <td style="text-align:right;">
                <?= ($row['entrada_u'] != 0) ? number_format($row['entrada_total'], 2) : '' ?>
            </td>

            <!-- SALIDAS -->
            <td style="text-align:right;">
                <?= ($row['salida_u'] != 0) ? number_format($row['salida_u'], 2) : '' ?>
            </td>
            <td style="text-align:right;">
                <?= ($row['salida_u'] != 0) ? number_format($row['salida_costo'], 2) : '' ?>
            </td>
            <td style="text-align:right;">
                <?= ($row['salida_u'] != 0) ? number_format($row['salida_total'], 2) : '' ?>
            </td>

            <!-- EXISTENCIAS -->
            <td style="text-align:right;">
                <?= ($row['exist_u'] != 0) ? number_format($row['exist_u'], 2) : '' ?>
            </td>
            <td style="text-align:right;">
                <?= ($row['exist_u'] != 0) ? number_format($row['exist_costo'], 2) : '' ?>
            </td>
            <td style="text-align:right;">
                <?= ($row['exist_u'] != 0) ? number_format($row['exist_total'], 2) : '' ?>
            </td>
        </tr>
    <?php endforeach; ?>

</table>
