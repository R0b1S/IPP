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

 	$input_path = getcwd(); //default path
 	$dir = "./";
 	$output_file = STDOUT; // default output
 	$pretty_xml = false;
 	$k = -1; // p_xmp == false
 	$no_inline = false;
 	$max_par = -1; //default -1
 	$no_duplicates = false;
 	$remove_whitespace = false;

 	if($argc > 1){
 		$arguments = getopt($longopts);
		if(count($arguments) != ($argc-1))		{
			fwrite(STDERR, "Invalid format of arguments.");
			exit(1);
		}//if count test

		foreach ($arguments as $argument => $argument_value) {
			switch($argument){
				case "help":
					if(argc != 2){
						fwrite(STDERR, "Invalid amount of arguments. HELP can t be combined with other arguments");
						exit(1);
					}
				break;

				case "input":
					$input_path = $argument_value;
				break;

				case "output":
					if(!(fopen($argument_value, "w"))){
						fwrite(STDERR, "Error: Can t open output file.");
						exit(3);
					}
				break;

				case "pretty-xml":
					if($argument_value == "")
						$k = 4;
					else{
						if(!is_numeric($argument_value) && (int)$argument_value < 0){
							fwrite(STDERR, "Error: Pretty-xml must be number bigger than 0.");
							exit(1);
						}
						else{
							$k = $argument_value;
						}
					}
					$pretty_xml = true;
				break;

				case "no-inline":
					$no_inline = true;
				break;

				case "max-par":
					if(!is_numeric($argument_value)){
						fwrite(STDERR, "Error: Argument max-par must be number.");
						exit(1);
					}

					$max_par = $argument_value;
				break;

				case "no-duplicates":
					$no_duplicates = true;
				break;

				case "remove-whitespace":
					$remove_whitespace = true;
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
