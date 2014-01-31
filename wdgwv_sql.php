<?php
/*
                   :....................,:,              
                ,.`,,,::;;;;;;;;;;;;;;;;:;`              
              `...`,::;:::::;;;;;;;;;;;;;::'             
             ,..``,,,::::::::::::::::;:;;:::;            
            :.,,``..::;;,,,,,,,,,,,,,:;;;;;::;`          
           ,.,,,`...,:.:,,,,,,,,,,,,,:;:;;;;:;;          
          `..,,``...;;,;::::::::::::::'';';';:''         
          ,,,,,``..:;,;;:::::::::::::;';;';';;'';        
         ,,,,,``....;,,:::::::;;;;;;;;':'''';''+;        
         :,::```....,,,:;;;;;;;;;;;;;;;''''';';';;       
        `,,::``.....,,,;;;;;;;;;;;;;;;;'''''';';;;'      
        :;:::``......,;;;;;;;;:::::;;;;'''''';;;;:       
        ;;;::,`.....,::;;::::::;;;;;;;;'''''';;,;;,      
        ;:;;:;`....,:::::::::::::::::;;;;'''':;,;;;      
        ';;;;;.,,,,::::::::::::::::::;;;;;''':::;;'      
        ;';;;;.;,,,,::::::::::::::::;;;;;;;''::;;;'      
        ;'';;:;..,,,;;;:;;:::;;;;;;;;;;;;;;;':::;;'      
        ;'';;;;;.,,;:;;;;;;;;;;;;;;;;;;;;;;;;;:;':;      
        ;''';;:;;.;;;;;;;;;;;;;;;;;;;;;;;;;;;''';:.      
        :';';;;;;;::,,,,,,,,,,,,,,:;;;;;;;;;;'''';       
         '';;;;:;;;.,,,,,,,,,,,,,,,,:;;;;;;;;'''''       
         '''';;;;;:..,,,,,,,,,,,,,,,,,;;;;;;;''':,       
         .'''';;;;....,,,,,,,,,,,,,,,,,,,:;;;''''        
          ''''';;;;....,,,,,,,,,,,,,,,,,,;;;''';.        
           '''';;;::.......,,,,,,,,,,,,,:;;;''''         
           `''';;;;:,......,,,,,,,,,,,,,;;;;;''          
            .'';;;;;:.....,,,,,,,,,,,,,,:;;;;'           
             `;;;;;:,....,,,,,,,,,,,,,,,:;;''            
               ;';;,,..,.,,,,,,,,,,,,,,,;;',             
                 '';:,,,,,,,,,,,,,,,::;;;:               
                  `:;'''''''''''''''';:.                 
                                                         
 ,,,::::::::::::::::::::::::;;;;,::::::::::::::::::::::::
 ,::::::::::::::::::::::::::;;;;,::::::::::::::::::::::::
 ,:; ## ## ##  #####     ####      ## ## ##  ##   ##  ;::
 ,,; ## ## ##  ## ##    ##         ## ## ##  ##   ##  ;::
 ,,; ## ## ##  ##  ##  ##   ####   ## ## ##   ## ##   ;::
 ,,' ## ## ##  ## ##    ##    ##   ## ## ##   ## ##   :::
 ,:: ########  ####      ######    ########    ###    :::
 ,,,:,,:,,:::,,,:;:::::::::::::::;;;:::;:;:::::::::::::::
 ,,,,,,,,,,,,,,,,,,,,,,,,:,::::::;;;;:::::;;;;::::;;;;:::
                                                         
	     (c) WDGWV. 2013, http://www.wdgwv.com           
	 websites, Apps, Hosting, Services, Development.      

  File Checked.
  Checked by: WdG.
  File created: WdG.
  date: 27-JAN-2014
  Last update: 29-JAN-2014

  © WDGWV, www.wdgwv.com
  All Rights Reserved.
*/

/*
	## DISCLAIMER // INFO 		##
	
This set of functions is basicly to support the deprecated mysql_* functions
So if you replace mysql_* with wdgwv_sql_* it will work again.
The wdgwv_sql_* is written in pdo, and is only meaned for temporary! 
There is no error real error triggering, the error triggering works, but errors like
	you have a error in qour query near XXXXX
Is not supported yet.

This code is written by WdG (Wesley de Groot) 
This code is tested by WdG and EH (Edwin Huijboom)
This code is provided by WDGWV
more info? http://wdgwv.github.io/MySQL_Support.html

	## END OF DISCLAIMER // INFO ##
*/

// Config & Objects
$wdgwv_sql = array();
$wdgwv_sql['config']['trigger_error'] = false;
$wdgwv_sql['config']['fetch_type']    = null; //PDO::FETCH_ASSOC (names)
											  //PDO::FETCH_NUM   (array 0,1,2,3,etc)
$wdgwv_sql['error']					  = null; //WGT: FIX: 29-JAN-2014

#function wdgwv_sql_real_escape_string ( string )
# Replaces mysql_real_escape_string.
## WdG: 29 JAN 2014
function wdgwv_sql_real_escape_string ( $string )
{
	return $string; //NOT NEEDED FOR PDO
}

#function wdgwv_sql_escape_string ( string )
# Replaces mysql_escape_string.
## WdG: 29 JAN 2014
function wdgwv_sql_escape_string ( $string )
{
	return $string; //NOT NEEDED FOR PDO
}

#function wdgwv_sql_trigger_error ( error )
# Replaces none, checks if needed to trigger a error otherwise save it in $wdgwv_sql.
## WdG: 27 JAN 2014
function wdgwv_sql_trigger_error ($error)
{
	//Load global config
	global $wdgwv_sql;

	//what to do? reset or make a error?
	if ( $error == false)
	{
		//reset the error.
		$wdgwv_sql['old_error'] = $wdgwv_sql['error'];
		$wdgwv_sql['error'] 	= null;
	}
	else
	{
		// need to trigger the error?
		if ( $wdgwv_sql['config']['trigger_error'] )
		{
			//yes, trigger the error.
			trigger_error($error);
		}
		else
		{
			//nope, save the error only...
			$wdgwv_sql['old_error'] = $wdgwv_sql['error'];
			$wdgwv_sql['error'] 	= $error;
		}
	}
}

#function wdgwv_sql_sql_connect(hostname, username, password)
# Replaces mysql_connect(hostname, username, password)
## WdG: 27 JAN 2014
function wdgwv_sql_connect($hostname, $username, $password)
{
	//Load global config
	global $wdgwv_sql;
	wdgwv_sql_trigger_error(false);	
	//Set some parameters
	$wdgwv_sql['connect']['hostname'] = $hostname;
	$wdgwv_sql['connect']['username'] = $username;
	$wdgwv_sql['connect']['password'] = $password;

	//return: true (connection)
	// couse pdo need database for connect.
	return true;
}

#function wdgwv_sql_select_db(database, link = null )
# Replaces mysql_select_db(database, link)
## WdG: 27 JAN 2014
function wdgwv_sql_select_db($database, $link = null)
{
	//Load global config
	global $wdgwv_sql;
	wdgwv_sql_trigger_error(false);	

	//Set database name
	$wdgwv_sql['connect']['database'] = $database;

	//we do nothing with $link.

	//Let's connect...
	$wdgwv_sql['connection'] = new PDO(
										'mysql:host=' . 							//Say use mysql with host..
											$wdgwv_sql['connect']['hostname'] .		// <-- HOST
										';dbname=' .								//And database...
											$wdgwv_sql['connect']['database'] .		// <-- DATABASE
										';charset:utf8',							//With Charset utf8.
										
											$wdgwv_sql['connect']['username'],		// <-- USERNAME.
											$wdgwv_sql['connect']['password']		// <-- PASSWORD.
									   );

	if ( @$wdgwv_sql['connection'] ) //if connected then..
	{
		// set the PDO modes...
		$wdgwv_sql['connection'] -> setAttribute(PDO::ATTR_ERRMODE,	 		 PDO::ERRMODE_EXCEPTION);
		$wdgwv_sql['connection'] -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

		//and return i'm connected
		return true;
	}
	else
	{
		// Failed... trigger a error
		wdgwv_sql_trigger_error('Can\'t Connect or Select database');

		// And sent i did'nt connect...
		return false;
	}
}

#function wdgwv_sql_error( )
# Replaces mysql_error( )
## WdG: 27 JAN 2014
function wdgwv_sql_error ( )
{
	//Load global config
	global $wdgwv_sql;

	// Is there a error?
	if ( isset ( $wdgwv_sql['error'] ) )
		return $wdgwv_sql['error']; //Yes, so return it back
	else
		return null; //Nope error is null.
}

#function wdgwv_sql_query( query, link = null )
# Replaces mysql_query( query, link )
## WdG: 27 JAN 2014
function wdgwv_sql_query ( $query, $link = null )
{
	//Load global config
	global $wdgwv_sql;
	wdgwv_sql_trigger_error(false);	//Reset errors
	$wdgwv_sql['last']['query'] = $query; //Saves last query...

	// Execute and return command...
	$cmd = (
			@$wdgwv_sql['connection'] -> query ( $query )
			// 							^ Query...
		   );

	// Is the command executed good?
	if ( $cmd )
	{
		return $cmd; //Yes, so return the Query data!
	}
	else
	{
		// No trigger a error...
		wdgwv_sql_trigger_error('Query: ' . $query . ' Was not executed, please check your query');

		// and return i've a error.
		return false;
	}
}

#function wdgwv_sql_fetch_array ( query, foreach, link = null )
# Replaces mysql_fetch_array ( query, link )
## WdG: 27 JAN 2014
function wdgwv_sql_fetch_array( $command, $foreach = false, $type = null )
{
	//Load global config
	global $wdgwv_sql;
	wdgwv_sql_trigger_error(false);	

	//Create a temporary array.
	$tempArray = array();

	while ( $row = ( @$command->fetch ( $wdgwv_sql['config']['fetch_type'] ) ) )
		// Load the data, but please ignore errors!
	{
		// Put the data to the temporary array.
		$tempArray[] = $row;		
	}

	// if the size is zero, i think it's a error, so i trigger a error!
	if ( sizeof($tempArray) == 0 )
	{
		//error so trigger a error.
		wdgwv_sql_trigger_error('Error can\'t fetch data.');

		// return false we got nothing?!
		return false;
	}
	else
	{
		if ( sizeof($tempArray) == 1) //WGT: Fix 29-JAN-2014 one item then not needed to
		{
		 	if (!$foreach)
		 		$ret = $tempArray[0]; //!in a foreach.... ;)
			else
				$ret = $tempArray; //in a foreach!!!!

			return $ret; // have $arr[0][SELECTED ITEMS]
		}
		else
		{
			return $tempArray; //no error, so just return the data.
		}
	}

	unset($tempArray); // unset the temporary array to clean up the mess.
}

#function wdgwv_sql_fetch_assoc ( query, foreach )
# Replaces mysql_fetch_assoc ( query )
## WdG: 27 JAN 2014
function wdgwv_sql_fetch_assoc ( $command, $foreach = false, $type = null )
{
	//Load global config
	global $wdgwv_sql;
	wdgwv_sql_trigger_error(false);	

	//Create a temporary array.
	$tempArray = array();

	while ( $row = ( @$command->fetch ( PDO::FETCH_ASSOC ) ) )
		// Load the data, but please ignore errors!
	{
		// Put the data to the temporary array.
		$tempArray[] = $row;		
	}

	// if the size is zero, i think it's a error, so i trigger a error!
	if ( sizeof($tempArray) == 0 )
	{
		//error so trigger a error.
		wdgwv_sql_trigger_error('Error can\'t fetch data.');

		// return false we got nothing?!
		return false;
	}
	else
	{
		if ( sizeof($tempArray) == 1) //WGT: Fix 29-JAN-2014 one item then not needed to
		{
		 	if (!$foreach)
		 		$ret = $tempArray[0]; //!in a foreach.... ;)
			else
				$ret = $tempArray; //in a foreach!!!!

			return $ret; // have $arr[0][SELECTED ITEMS]
		}
		else
			return $tempArray; //no error, so just return the data.
	}

	unset($tempArray); // unset the temporary array to clean up the mess.
}

#function wdgwv_sql_fetch_row ( query, type = null )
# Replaces mysql_fetch_row ( query, type )
## WdG: 27 JAN 2014
function wdgwv_sql_fetch_row( $command, $type = null )
{
	//Load global config
	global $wdgwv_sql;
	wdgwv_sql_trigger_error(false);	

	//Create a temporary array.
	$tempArray = array();

	while ( $row = ( @$command->fetch ( $wdgwv_sql['config']['fetch_type'] ) ) )
		// Load the data, but please ignore errors!
	{
		// Put the data to the temporary array.
		$tempArray[] = $row;		
	}

	// if the size is zero, i think it's a error, so i trigger a error!
	if ( sizeof($tempArray) == 0 )
	{
		//error so trigger a error.
		wdgwv_sql_trigger_error('Error can\'t fetch data.');

		// return false we got nothing?!
		return false;
	}
	else
	{
		return $tempArray[0]; //no error, so just return the data.
	}

	unset($tempArray); // unset the temporary array to clean up the mess.
}

#function wdgwv_sql_num_rows ( query, link = null )
# Replaces mysql_num_rows ( query, link )
## WdG: 27 JAN 2014
function wdgwv_sql_num_rows ( $query, $link = null )
{
	//Load global config
	global $wdgwv_sql;

	//Don't know if error reset is neccecary.
	wdgwv_sql_trigger_error(false);

	//num the rows
	$num_rows = ( $query -> rowCount ( ) );

	//is there a error?!
	if ($num_rows)
	{
		//no error, return the count...
		return $num_rows;
	}
	else
	{
		//ERROR, trigger a error
		wdgwv_sql_error('Can\'t count rows');

		//error so return false.
		return false;
	}
}

#function wdgwv_sql_insert_id ( query = null )
# Replaces mysql_insert_id ( query )
## WdG: 27 JAN 2014
function wdgwv_sql_insert_id ( $query = null )
{
	//Load global config
	global $wdgwv_sql;

	//Don't know if error reset is neccecary.
	wdgwv_sql_trigger_error(false);

	//num the rows
	$lastInsertedId = ( @$wdgwv_sql['connection'] -> lastInsertId ( ) );

	//is there a error?!
	if ($lastInsertedId)
	{
		//no error, return the count...
		return $lastInsertedId;
	}
	else
	{
		//ERROR, trigger a error
		wdgwv_sql_error('Can\'t fetch id of last row');

		//error so return false.
		return false;
	}
}

#function wdgwv_sql_affected_rows ( query = null )
# Replaces mysql_affected_rows ( query )
## WdG: 27 JAN 2014
function wdgwv_sql_affected_rows ( $query = null )
{
	//Load global config
	global $wdgwv_sql;

	//Don't know if error reset is neccecary.
	wdgwv_sql_trigger_error(false);

	//num the rows
	$affected_rows = ( @$wdgwv_sql['connection'] -> lastInsertId ( ) );

	//is there a error?!
	if ($affected_rows)
	{
		//no error, return the count...
		return $affected_rows;
	}
	else
	{
		//ERROR, trigger a error
		wdgwv_sql_error('Can\'t get affected rows');

		//error so return false.
		return false;
	}
}
?>