<?php

  //FUNCIONES PARA COMBOBOX
  function fnvalijaSelect( $var_valija )
  {
    if ( empty( $_POST['cmbValijas'] ) ) 
      return "";
    else if ( $_POST['cmbValijas'] == $var_valija ) 
      return "selected";
  }

  function fnloteSelect( $var_lote )
  {
    if ( empty( $_POST['cmbLotes'] ) )
      return "";
    else if ( $_POST['cmbLotes'] == $var_lote )
      return "selected";
  }

  function fntipomovimientoSelect( $var_tipomovimiento )
  {
    if ( empty( $_POST['cmbtipomovimiento'] ) ) 
      return 0;    
    else if ( $_POST['cmbtipomovimiento'] == $var_tipomovimiento )
      return "selected";
  }

  function fntdelegacionSelect($var_delegacion)
  {
    if (empty($_POST['cmbDelegaciones']))
      return "";
    else if ($_POST['cmbDelegaciones'] == $var_delegacion)
      return "selected";
  }

  function fntsubdelegacionSelect($var_subdelegacion)
  {
    if ( empty( $_POST['cmbSubdelegaciones'] ) && $_POST['cmbSubdelegaciones'] <> 0 ) {
      return "";
      /*echo "nad";*/
    }
    else if ( $_POST['cmbSubdelegaciones'] == $var_subdelegacion ) {
      return "selected";
      /*echo "els";*/
    }
  }

  function fntPuestoSelect($var_id_puesto)
  {
    if (empty($_POST['cmbPuesto']))
      return "";
    else if ($_POST['cmbPuesto'] == $var_id_puesto)
      return "selected";
  }

  function fntcmbgponuevoSelect($var_gponuevo)
  {
    if ( empty( $_POST['cmbgponuevo'] ) )
      return "";
    else if ( $_POST['cmbgponuevo'] == $var_gponuevo )
      return "selected";
  }

  function fntcmbgpoactualSelect($var_gpoactual)
  {
    if ( empty( $_POST['cmbgpoactual'] ) )
      return "";
    else if ( $_POST['cmbgpoactual'] == $var_gpoactual )
      return "selected";
  }

  function fntcmbcausarechazoSelect($var_causarechazo)
  {
    if ( empty( $_POST['cmbcausarechazo'] ) && $_POST['cmbcausarechazo'] <> 0 )
      return "";
    else if ( $_POST['cmbcausarechazo'] == $var_causarechazo )
      return "selected";
  }

  function fntPersonaUSAFSelect($var_persona)
  {
    if ( empty( $_POST['cmbPersonaUSAF'] ) && $_POST['cmbPersonaUSAF'] <> 0 )
      return "";
    else if ( $_POST['cmbPersonaUSAF'] == $var_persona )
      return "selected";
  }

  function fntPersonaSolicitanteSelect($var_persona)
  {
    if ( empty( $_POST['cmbPersonaSolicitante'] ) && $_POST['cmbPersonaSolicitante'] <> 0 )
      return "";
    else if ( $_POST['cmbPersonaSolicitante'] == $var_persona )
      return "selected";
  }

  function fntPersonaTitularSelect($var_persona)
  {
    if ( empty( $_POST['cmbPersonaTitular'] ) && $_POST['cmbPersonaTitular'] <> 0 )
      return "";
    else if ( $_POST['cmbPersonaTitular'] == $var_persona )
      return "selected";
  }

  function fnOpcionUSAFSelect( $var_opcion )
  {
    if ( empty( $_POST['cmbOpcion'] ) ) 
      return 0;    
    else if ( $_POST['cmbOpcion'] == $var_tipomovimiento )
      return "selected";
  }

?>
