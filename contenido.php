<?php

defined('_JEXEC') or die('Acceso denegado');

/* Importando la libreria base de plugins de Joomla */

jimport('joomla.plugin.plugin');

/* Generando el plugin */

class plgContentContenido extends JPlugin{

	/* Asignando plugin de contenido */
    
	function plgContentContenido(&$subject){

		parent::__construct($subject);

	}

	/* Asignando metodo que utilizara el plugin */

    function onContentBeforeDisplay(&$article, &$params, $limitstart){

            date_default_timezone_set("Europe/Madrid");
            $currentHour = date(G);
            $currentMinute = date(i);
            $currentTime = $currentHour.":".$currentMinute;

            // Printing Current Date in Madrid
            $month = date("F");
            $day = date("d");
            $year = date("Y");
            $currentDate = $month." ".$day.", ".$year;
            $unix = mktime();

            // Preparing Rescuetime API Call
            function rescuetimeApi($url)
            {
                $file_url = fopen ($url, "r");
                $text = "";
                while ($trozo = fgets($file_url, 1024))
                {
                    $text .= $trozo;
                }
                return $text;
            }

            // Rescuetime API Call
            $URL_API_RESCUETIME = "https://www.rescuetime.com/anapi/data?key=B63iNmZ785e8u822nKgTVYTk2eVtjyDH2r78VqiP&perspective=rank&restrict_kind=overview&restrict_begin=".$year."-".$month."-".$day."&restrict_end=".$year."-".$month."-".$day."&format=json";

            // Saving Rescuetime API Response in $apiResponse
            $apiResponse = rescuetimeApi($URL_API_RESCUETIME);

            // $apiResponse is not the value we need. We need the Total Seconds Worked. We use regular expressions for this purpose.
             preg_match('/,([0-9]{1,5}),[0-9]{1,5},"Uncategorized"/', $apiResponse,$totalSeconds);


            // Printing the Total Seconds Worked
            // print "Total Seconds Worked - ".$totalSeconds[1]." seconds<br><br>";

            // Converting the Total Seconds Worked ($totalSeconds[1]) in hours, minutes and seconds

            $seconds = ($totalSeconds[1]%60);

            $totalMinutes = ($totalSeconds[1]/60);

            $minutes = ($totalMinutes%60);

            $hours = (int)($totalMinutes/60);


            // Printing the Total Time Worked in hours, minutes and seconds. We use a basic control structure to print "hour" or "hours" as appropriate
            if($hours==1)
            {
                $totalTimeWorked = $hours." hora ".$minutes." minutos ".$seconds ." segundos";
            }
            else
            {
                $totalTimeWorked = $hours." horas ".$minutes." minutos ".$seconds ." segundos";
            }

            // Preparing quizlet API Call
            function quizletApi($urls)
            {
                $archivo_url = fopen ($urls, "r");
                $texto = "";
                while ($trozos = fgets($archivo_url, 1024))
                {
                    $texto .= $trozos;
                }
                return $texto;
            }

            // Quizlet API Call
            $URL_API_QUIZLET = "https://api.quizlet.com/2.0/users/miguelmanzan/studied?client_id=rkQWj7huN2&callback=foo";

            // Saving Quizlet API Response in $apiResponse
            $apiRespuesta = rescuetimeApi($URL_API_QUIZLET);


            // $apiResponse is not the value we need. We need the Start Date. We use regular expressions for this purpose.
            preg_match('/start_date":(\d+),/', $apiRespuesta,$startDate);
            // echo $startDate[1]."<br><br>";


            // $apiResponse is not the value we need. We need also the Finish Date. We use regular expressions for this purpose.
            preg_match('/finish_date":(\d+),/', $apiRespuesta,$finishDate);
            // echo $finishDate[1]."<br><br>";


            // Calculando el Tiempo de Estudio
            $timeStudied = $finishDate[1] - $startDate[1];
            // echo "Tiempo Estudiado - ".$timeStudied." segundos<br><br>";

            // Converting the Time Studied ($timeStudied) in hours, minutes and seconds

            $timeStudiedSeconds = ($timeStudied%60);

            $timeStudiedTotalMinutes = ($timeStudied/60);

            $timeStudiedMinutes = ($timeStudiedTotalMinutes%60);

            $timeStudiedHours = (int)($timeStudiedTotalMinutes/60);


            $totalTimeStudied = $timeStudiedHours." horas ".$timeStudiedMinutes." minutos ".$timeStudiedSeconds ." segundos";


            // Calculando último estudio

            $lastStudy = $unix - $startDate[1];
            // print "Último estudio hace - ".$lastStudy." segundos<br><br>";


            // Converting the Last Study ($timeStudied) in hours, minutes and seconds

            $lastStudySeconds = ($lastStudy%60);

            $lastStudyTotalMinutes = ($lastStudy/60);

            $lastStudyMinutes = ($lastStudyTotalMinutes%60);

            $lastStudyHours = (int)($lastStudyTotalMinutes/60);

            $totalLastStudy = $lastStudyHours." horas ".$lastStudyMinutes." minutos ".$lastStudySeconds ." segundos";


            ?>
            <!-- Table Section -->
            <section id="pricing-table">
            <div class="container">
                <div class="row">
                    <div class="pricing">
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="pricing-table">
                                <div class="pricing-header">

                                    <p class="pricing-rate"><sup>Fecha</sup> <?php print $currentDate; ?><br> <sup>Hora</sup> <?php print $currentTime; ?></p>
                                </div>

                                <div class="pricing-list">
                                    <ul>
                                        <li><span>Tiempo Trabajado &nbsp;&nbsp;&nbsp;</span> <?php print $totalTimeWorked; ?> </li>
                                        <li><span>Tiempo Estudiado &nbsp;&nbsp;&nbsp;</span> <?php print $totalTimeStudied; ?> </li>
                                        <li><span>Ultimo Estudio hace &nbsp;&nbsp;&nbsp;</span> <?php print $totalLastStudy; ?> </li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            </section>
            <!-- Table Section End -->
            <?php

    }  

}