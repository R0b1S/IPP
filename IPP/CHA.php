<?php

/*
*	Author: Robert Misura
*	Project: CHA - C Header Analysis
*	Subject: IPP - php script
*	Name: CHA.php
*/

//Functions---------------------------------------------------------------------------------------

function writeHelp(){
	echo "HELP MESSAGE:
	This is PHP script, which analyze C header files (.h). 
	Script finds functions in files. Allowed arguments:

	--help              	Prints a help message.
	--input=fileordir		Input file or directory.
	--output=file 			Name of output file, default option is STDOUT.		
	--pretty-xml=k 			Spacing in XML file. Variable k is number of spaces.
	--no-inline				Scprit ignores functions with inline description.
	--max-par=n 			Script searches for function with maximum n parameters.
	--no-duplicates			If file or directory contians same functions, ignores next one.
	--remove-whitespace		Replaces multiple white spaces for one white space.

	Good luck ;) 				

		 ";
}

function argumentsParse($argv,$argc){
 	$longopts = array(
 		"help",
 		"input:",
 		"output:",
 		"pretty-xml::",
 		"no-inline",
 		"max-par:",
 		"no-duplicates",
 		"remove-whitespace",
 	);

 	$input_path = getcwd();
 	$dir = "./";
 	$output_file = STDOUT;
 	$pretty_xml = false;
 	$k = -1;
 	$no_inline = false;
 	$max_par = false;
 	$no_duplicates = false;
 	$remove_whitespace = false;

 	if($argc > 1){
 		$arguments = getopt($longopts);
		if(count($arguments) != ($argc-1))		{
			fwrite(STRERR, "Invalid format of arguments.");
			exit(1);
		}//if count test

		foreach ($arguments as $argument => $argument_value) {
			switch($argument){
				case "help":
					if(argc != 2){
						fwrite(STRERR, "Invalid amount of arguments. HELP can t be combined with other arguments");
						exit(1);
					}
				break;

				case "input":
					$input_path = $argument_value;
				break;

				case "output":
					if(!fopen($argument_value, "w")){
						fwrite(STRERR, "Error: Can t open output file.");
						exit(3);
					}
				break;

				case "pretty-xml":

				break;


			}//switch
		}//foreach


 	}//if argc>1

}//function 

//-----------------------------------------------------------------------------------------------

argumentsParse($argv,$argc);


//Errors messages
function errMsg(){
	echo "too many arguments \n";
}

?>
