<?php

foreach(glob(__DIR__ ."/Libraries/*.[pP][hH][pP]") as $file) {

	require $file;

}