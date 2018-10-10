<?php

require_once 'class/mpConfig.php';

$sec = new mpSecurity();
if( !$sec->isAuth() )
{
    echo "Sem Autorização";
    die;
}

?>    

<table class="newTracking">
    <tr>
        <td align="right"><span class="mandatory">(*) Obrigatório</span></td> 
        <td></td>
    </tr>
    <tr>
        <td align="right" valign="top" style="padding-top: 8px;">Loja: <span class="mandatory">(*)</span></td>
        <td><select style="width: 175px;">
                <option>AliExpress</option>
                <option>DealExtreme</option>
            </select> 
            <p> <a href="shops.php">Clique aqui se não temos a loja que procura</a> </p>
            </td>
    </tr>
    <tr>
        <td align="right" valign="top" style="padding-top: 8px;">Transportadora: <span class="mandatory">(*)</span></td>
        <td><select style="width: 175px;">
                <option>Correios</option>
            </select> 
            <p> <a href="contact.php?t">Clique aqui para solicitar a transportadora que procura</a> </p>
            </td>
    </tr>
    <tr>
        <td align="right" valign="top" style="padding-top: 8px;">Código de Rastreio: <span class="mandatory">(*)</span></td>
        <td><input type="text" style="width: 161px;">
            &nbsp;
            (+)</td>
    </tr>
    <tr>
        <td align="right">Código do Pedido na Loja: <span class="mandatory">(*)</span></td>
        <td><input type="text" style="width: 161px;"></td>
    </tr>
    <tr>
        <td align="right">Descrição do Pedido:</td>
        <td><input type="text" style="width: 161px;"></td>
    </tr>
    <tr>
        <td align="right">Valor Total do Pedido:</td>
        <td><select style="width: 65px;">
                <option>US$</option>
                <option>R$</option>
            </select>
            <input type="text" size="11" style="width: 93px;"></td>
    </tr>
    <tr>
        <td align="right">Data Compra:</td>
        <td><input type="text" style="width: 80px;"></td>
    </tr>
</table>