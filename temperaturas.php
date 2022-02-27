<?php

/**
* Retorna a temperatura mais proxima de zero.
* Se duas temperaturas com o mesmo valor absouto (uma positiva e outra negativa) serem igualmente proxima a zero, deve ser dada a preferencia para o valor positivo.
* @param array $temperaturas Lista de temperaturas
* @return int A temperatura mais proxima de zero
**/
function menorTemperatura($temperaturas) {
		if(empty($temperaturas)){
        return 0;
    }

    $proximo = 0;
    for ($i = 0; $i < count($temperaturas) ; $i++) {
        if ($proximo === 0) {
            $proximo = $temperaturas[$i];
        } else if ($temperaturas[$i] > 0 && $temperaturas[$i] <= abs($proximo)) {
					$proximo = $temperaturas[$i];
        } else if ($temperaturas[$i] < 0 && -$temperaturas[$i] < abs($proximo)) {
					if($temperaturas[$i] < 0 and in_array(0, $temperaturas)){
						$proximo = 0;
					}else{
						$proximo = $temperaturas[$i];
					}
        }
    }

    return $proximo;
}


/***** Teste 01 *****/
$temperaturas = array( 17, 32, 14, 21 );
$resultadoEsperado = 14;
$resultado = menorTemperatura($temperaturas);
verificaResultado("01", $resultadoEsperado, $resultado);
// die;


/***** Teste 02 *****/
$temperaturas = array( 27, -8, -12, 9 );
$resultadoEsperado = -8;
$resultado = menorTemperatura($temperaturas);
verificaResultado("02", $resultadoEsperado, $resultado);
// die;


/***** Teste 03 *****/
$temperaturas = array( -6, 14, 42, 6, 25, -18 );
$resultadoEsperado = 6;
$resultado = menorTemperatura($temperaturas);
verificaResultado("03", $resultadoEsperado, $resultado);


/***** Teste 04 *****/
$temperaturas = array( 34, 11, 13, -23, -11, 18 );
$resultadoEsperado = 11;
$resultado = menorTemperatura($temperaturas);
verificaResultado("04", $resultadoEsperado, $resultado);


/***** Teste 05 *****/
$temperaturas = array( 17, 0, 28, -4 );
$resultadoEsperado = 0;
$resultado = menorTemperatura($temperaturas);
verificaResultado("05", $resultadoEsperado, $resultado);

/***** Teste 06 *****/
$temperaturas = array( -10, 27, 9, -12 );
$resultadoEsperado = 9;
$resultado = menorTemperatura($temperaturas);
verificaResultado("06", $resultadoEsperado, $resultado);

/***** Teste 07 *****/
$temperaturas = array( -47, -14, -5, -12, -8 );
$resultadoEsperado = -5;
$resultado = menorTemperatura($temperaturas);
verificaResultado("07", $resultadoEsperado, $resultado);

/***** Teste 08 *****/
$temperaturas = array( -47, -14, -5, -12, -5 );
$resultadoEsperado = -5;
$resultado = menorTemperatura($temperaturas);
verificaResultado("08", $resultadoEsperado, $resultado);

/***** Teste 09 *****/
$temperaturas = array( -7, 12, -13, 8 );
$resultadoEsperado = -7;
$resultado = menorTemperatura($temperaturas);
verificaResultado("09", $resultadoEsperado, $resultado);


function verificaResultado($nTeste, $resultadoEsperado, $resultado) {
	if(intval($resultadoEsperado) === intval($resultado)) {
		echo "Teste $nTeste passou.\n";
		echo "<br>";
	} else {
		echo "Teste $nTeste NAO passou (Resultado esperado = $resultadoEsperado, Resultado obtido = $resultado).\n";
		echo "<br>";
	}
}

?>
