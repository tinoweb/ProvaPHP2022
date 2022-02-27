<?php

/**
* Calcula o numero de dias entre 2 datas.
* As datas passadas sempre serao validas e a primeira data sempre sera menor que a segunda data.
* @param string $dataInicial No formato YYYY-MM-DD
* @param string $dataFinal No formato YYYY-MM-DD
* @return int O numero de dias entre as datas
**/
function calculaDias($dataInicial, $dataFinal) {
	/*
		- Setembro, abril, junho e novembro tem 30 dias, todos os outros meses tem 31 exceto fevereiro que tem 28, exceto nos anos bissextos nos quais ele tem 29.
		- Os anos bissexto tem 366 dias e os demais 365.
		- Todo ano divisivel por 4 e um ano bissexto.
		- A regra acima não e valida para anos divisiveis por 100. Estes anos devem ser divisiveis por 400 para serem anos bissextos. Assim, o ano 1700, 1800, 1900 e 2100 nao sao bissextos, mas 2000 e bissexto.
		- Não e permitido o uso de classes e funcoes de data da linguagem (DateTime, mktime, strtotime, etc).
	*/

	$meses30dias = [4, 6, 9];
	$diasOutrosMesses = 31;
	$feveriroDias = 28;
	$feveriroBissextosDias = 29;
	$anoBissextosDias = 366;
	$anoDias = 365;


	$datExpInicial = explode('-', $dataInicial);
	$datExpFinal = explode('-', $dataFinal);
	$anoIn = $datExpInicial[0];
	$mesIn = $datExpInicial[1];
	$diaIn = $datExpInicial[2];

	$anoFn = $datExpFinal[0];
	$mesFn = $datExpFinal[1];
	$diaFn = $datExpFinal[2];

	// se os anos passados sao diferentes faz o calculo de dias entre esses anos e reserve numa variavel
	if($anoIn and $anoFn){
		$diferAno = $anoFn - $anoIn;
		if($diferAno >= 0){
			$diasAno = calculaDiasAno($anoIn,$anoFn);
			$diasMes = calculaDiasMesAnoDiferente($mesIn, $mesFn, $anoIn, $anoFn);
			list($operador, $resultDias) = $diasMes;
			$diasDia = calculaDias_dta($diaIn,$diaFn);
			list($operadorDias, $resultDiasDias) = $diasDia;

			if ($operador == 'menos') {
				$diasAno = $diasAno - $resultDias;
			}else{
				$diasAno = $diasAno + $resultDias;
			}

			if ($operadorDias == 'menos') {
				$diasAno-=$resultDiasDias;
			}else{
				$diasAno+=$resultDiasDias;
			}

			return abs($diasAno);

		}
	}

}

function calculaDiasAno($anoIn,$anoFn)
{
	$pegAnos = range($anoIn, $anoFn);
	array_pop($pegAnos);
	$diasAnoRetorna=0;
	$anoBi=0;
	foreach ($pegAnos as $key => $value) {
		if(isAnoBissexto($value)){
			$anoBi++;
			$diasAnoRetorna+=366;
		}else{
			$diasAnoRetorna+=365;
		}
	}
	return $diasAnoRetorna;
}

function calculaDias_dta($diaIn,$diaFn)
{
	if($diaIn>$diaFn){
		$ope = $diaIn-$diaFn;
		return ["menos", $ope];
	}
	$ope = $diaFn-$diaIn;
	  return ["mais", $ope];
}

function getDayMeses($statusAno)
{
	$mesAnoDias = [
		0 => 31,
		1 => 28,
		2 => 31,
		3 => 30,
		4 => 31,
		5 => 30,
		6 => 31,
		7 => 31,
		8 => 30,
		9 => 31,
		10 => 31,
		11 => 31
	];
	if ($statusAno){
		$mesAnoDias[1] = 29;
		return $mesAnoDias;
	}
	return $mesAnoDias;
}

function checkDayMesesAnoBisexto($ano=false)
{
	if(isAnoBissexto($ano)){
		return getDayMeses(true);
	}
	return getDayMeses(false);
}

function verificaFevereiroBisexto($rangeAno)
{
	$conjuntoArrayBissesto = [];
	foreach ($rangeAno as $chave => $ano) {
		$conjuntoArrayBissesto[] = checkDayMesesAnoBisexto($ano);
	}
	$MesBissesto = [];
	foreach ($conjuntoArrayBissesto as $key => $conjArrBiss) {
		foreach ($conjArrBiss as $k => $value) {
			if($value == 29){
				$MesBissesto[$key] = $value;
			}
		}
	}
	$totalMesBiss = count($MesBissesto);
	return $totalMesBiss;
}

function calculaDiasMesAnoDiferente($mesIn, $mesFn, $anoIn, $anoFn)
{
	$pegDiasMes = range($mesIn, $mesFn);
	$rangeAno   = range($anoIn, $anoFn);

	if($mesIn<$mesFn)
	{
			$pegDiasMes = range($mesIn, $mesFn);
			array_pop($pegDiasMes);

			$totalMesBiss = verificaFevereiroBisexto($rangeAno);

			$somaDias=0;
			foreach (getDayMeses(false) as $key => $dias) {
				foreach ($pegDiasMes as $ey => $value) {
					if(($value-1) == $key){
						$somaDias += $dias;
						if(($key == 1 and $totalMesBiss>0) and $dias != 29){
							$somaDias += 1;
							$totalMesBiss--;
						}
					}
				}
			}
			return ["mais", $somaDias];
	}else{
			$pegDiasMes = range($mesIn, $mesFn);
			array_shift($pegDiasMes);
			array_shift($rangeAno);

			$totalMesBiss = verificaFevereiroBisexto($rangeAno);
			$somaDias=0;

			foreach (checkDayMesesAnoBisexto() as $key => $dias) {
				foreach ($pegDiasMes as $ey => $value) {
					if(($value-1) == $key){
						$somaDias += $dias;

						if($key == 1 and $totalMesBiss>0 and ($dias != 29)){
							$somaDias -= 1;
							$totalMesBiss--;
						}
						if($key == 1 and $totalMesBiss==7){
							$somaDias -= 1;
							$totalMesBiss--;
						}

					}
				}
			}

		return ["menos", $somaDias];
	}
}

function isAnoBissexto($ano)
{
	$ano = (int)$ano;
	if (($ano % 4 == 0) && ($ano % 100 != 0 || $ano %400 == 0)) {
	    return true;
	}
	return false;
}


//
/***** Teste 01 *****/
$dataInicial = "2018-01-01";
$dataFinal = "2018-01-02";
$resultadoEsperado = 1;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("01", $resultadoEsperado, $resultado);

/***** Teste 02 *****/
$dataInicial = "2018-01-01";
$dataFinal = "2018-02-01";
$resultadoEsperado = 31;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("02", $resultadoEsperado, $resultado);

/***** Teste 03 *****/
$dataInicial = "2018-01-01";
$dataFinal = "2018-02-02";
$resultadoEsperado = 32;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("03", $resultadoEsperado, $resultado);

/***** Teste 04 *****/
$dataInicial = "2018-01-01";
$dataFinal = "2018-02-28";
$resultadoEsperado = 58;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("04", $resultadoEsperado, $resultado);

/***** Teste 05 *****/
$dataInicial = "2018-01-15";
$dataFinal = "2018-03-15";
$resultadoEsperado = 59;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("05", $resultadoEsperado, $resultado);

/***** Teste 06 *****/
$dataInicial = "2018-01-01";
$dataFinal = "2019-01-01";
$resultadoEsperado = 365;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("06", $resultadoEsperado, $resultado);

/***** Teste 07 *****/
$dataInicial = "2018-01-01";
$dataFinal = "2020-01-01";
$resultadoEsperado = 730;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("07", $resultadoEsperado, $resultado);

/***** Teste 08 *****/
$dataInicial = "2018-12-31";
$dataFinal = "2019-01-01";
$resultadoEsperado = 1;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("08", $resultadoEsperado, $resultado);

/***** Teste 09 *****/
$dataInicial = "2018-05-31";
$dataFinal = "2018-06-01";
$resultadoEsperado = 1;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("09", $resultadoEsperado, $resultado);

/***** Teste 10 *****/
$dataInicial = "2018-05-31";
$dataFinal = "2019-06-01";
$resultadoEsperado = 366;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("10", $resultadoEsperado, $resultado);

/***** Teste 11 *****/
$dataInicial = "2016-02-01";
$dataFinal = "2016-03-01";
$resultadoEsperado = 29;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("11", $resultadoEsperado, $resultado);

/***** Teste 12 *****/
$dataInicial = "2016-01-01";
$dataFinal = "2016-03-01";
$resultadoEsperado = 60;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("12", $resultadoEsperado, $resultado);

/***** Teste 13 *****/
$dataInicial = "1981-09-21";
$dataFinal = "2009-02-12";
$resultadoEsperado = 10006;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("13", $resultadoEsperado, $resultado);

/***** Teste 14 *****/
$dataInicial = "1981-07-31";
$dataFinal = "2009-02-12";
$resultadoEsperado = 10058;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("14", $resultadoEsperado, $resultado);

/***** Teste 15 *****/
$dataInicial = "2004-03-01";
$dataFinal = "2009-02-12";
$resultadoEsperado = 1809;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("15", $resultadoEsperado, $resultado);

/***** Teste 16 *****/
$dataInicial = "2004-03-01";
$dataFinal = "2009-02-12";
$resultadoEsperado = 1809;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("16", $resultadoEsperado, $resultado);

/***** Teste 17 *****/
$dataInicial = "1900-02-01";
$dataFinal = "1900-03-01";
$resultadoEsperado = 28;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("17", $resultadoEsperado, $resultado);

/***** Teste 18 *****/
$dataInicial = "1899-01-01";
$dataFinal = "1901-01-01";
$resultadoEsperado = 730;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("18", $resultadoEsperado, $resultado);

/***** Teste 19 *****/
$dataInicial = "2000-02-01";
$dataFinal = "2000-03-01";
$resultadoEsperado = 29;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("19", $resultadoEsperado, $resultado);

/***** Teste 20 *****/
$dataInicial = "1999-01-01";
$dataFinal = "2001-01-01";
$resultadoEsperado = 731;
$resultado = calculaDias($dataInicial, $dataFinal);
verificaResultado("20", $resultadoEsperado, $resultado);


function verificaResultado($nTeste, $resultadoEsperado, $resultado) {
	if(intval($resultadoEsperado) == intval($resultado)) {
		echo "Teste $nTeste passou.<br>";
		echo "<br>";
	} else {
		echo "Teste $nTeste NAO passou (Resultado esperado = $resultadoEsperado, Resultado obtido = $resultado).\n";
		echo "<br>";
	}
}

?>
