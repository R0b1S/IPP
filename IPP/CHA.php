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

	--help              		Prints a help message.
	--input=fileordir		Input file or directory.
	--output=file 			Name of output file, default option is STDOUT.		
	--pretty-xml=k 			Spacing in XML file. Variable k is number of spaces.
	--no-inline			Scprit ignores functions with inline description.
	--max-par=n 			Script searches for function with maximum n parameters.
	--no-duplicates			If file or directory contians same functions, ignores next one.
	--remove-whitespace		Replaces multiple white spaces for one white space.

	Good luck ;) 				

		 \n";
}

function fileExploration($input_path){
	 						
	if(($file_content = file_get_contents($input_path)) == false){ 						//testing if we were able to get content or if file is not empt
		fwrite(STDERR, "Error: Cant get file content or file is empty.\n");
		return 0;
	}

	$useless_content = array("/\".*?\"/", 		//strings
							 "/\/\/.*?\n/",		//row comments
							 "/\/\*.*?\*\//s", 	// block comments
							 );

	$file_content = preg_replace($useless_content, "", $file_content); 					//ged rid of useless content


	if(($function_search=preg_match_all("/[a-z,A-Z_,0-9]+?[[:alpha:]\s]*?[[:graph:]]+?[\s]+?[[:graph:]]+?[\s]*?\([[:graph:]\s]*?\)[\s]*?[;{]/", $file_content, $found_function)) !== 0){
		//var_dump($found_function);
		return $found_function;
	}
	return 0;
}


//-----------------------------------------------------------
// getopt() part => testing parameters
	
	
	//DEFAULT VALUES => arguments are not set-----------------
 	$input_path = getcwd(); //default path
 	$dir = "./";
 	$output_file = "php://output"; // default output
 	$pretty_xml = false;
 	$k = -1; // p_xmp == false
 	$no_inline = false;
 	$max_par = -1; //default -1 => max-par argument is not set
 	$no_duplicates = false;
 	$remove_whitespace = false;
 	//---------------------------------------------------------

 	if($argc > 1){ // first argument(argv[1]) is name of php script file(CHA.php)
 		$arguments = getopt("false",["help",                   	// without : -> no additional value
							 		"input:",					// one :	 -> required additional value
							 		"output:",					// double :: -> optional value
							 		"pretty-xml::",
							 		"no-inline",
							 		"max-par:",
							 		"no-duplicates",
							 		"remove-whitespace",]);
		if(count($arguments) != ($argc-1) || $arguments == false){
			fwrite(STDERR, "Invalid format of arguments.\n");
			exit(1);
		}//if count test
		
		//var_dump($arguments); 

		foreach ($arguments as $argument => $argument_value){
			switch($argument){
				case "help":
					if($argc != 2){
						fwrite(STDERR, "Invalid amount of arguments. HELP can t be combined with other arguments.\n");
						exit(1);
					}
					writeHelp();
				break;

				case "input":
					$input_path = $argument_value;
				break;

				case "output":
					if((fopen($argument_value, "w")) == false){
						fwrite(STDERR, "Error: Can t open output file.\n");
						exit(3);
					}
				break;

				case "pretty-xml":
					if($argument_value == "")
						$k = 4; //default value if pretty-xml is set but has no additional value
					else{
						if(!is_numeric($argument_value) && (int)$argument_value < 0){
							fwrite(STDERR, "Error: Pretty-xml must be number bigger than 0.\n");
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
						fwrite(STDERR, "Error: Argument max-par must be number.\n");
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

// end of getopt() part
// ----------------------------------------------------------------------------------

//fileExploration($input_path);
//var_dump($arguments);

echo "zatial ok\n";


if(is_file($input_path)){
	if(!file_exists($input_path)){
		fwrite(STDERR, "Error: File does not exists.\n");
		exit(2);
	}
	
	$XML_document = new XMLWriter();
	
	$XML_document->openMemory();
	$XML_document->startDocument('1.0','UTF-8');

	$XML_document->setIndent(true);
	
	$XML_document->startElement('functions');
	$XML_document->writeAttribute('dir',"");


	if(($f_functions = fileExploration($input_path))!=0){
	  writeContentToXML($input_path, $output_file, $pretty_xml, $k, $no_inline, $max_par, $no_duplicates, $remove_whitespace);		
	}

	var_dump($f_functions);

	$XML_document->endElement();
	$XML_document->endDocument();

	$XML_content = $XML_document->outputMemory();
	$XML_document->openUri($output_file);
	$XML_document->writeRaw($XML_content);

}
if(is_dir($input_path)){
		//TODO

}


echo "still ok\n";


echo "$output_file\n";
//print $xml->saveXML;


$input_path = getcwd(); //default path
 	$dir = "./";
 	$output_file = "php://output"; // default output
 	$pretty_xml = false;
 	$k = -1; // p_xmp == false
 	$no_inline = false;
 	$max_par = -1; //default -1 => max-par argument is not set
 	$no_duplicates = false;
 	$remove_whitespace = false;


?>
