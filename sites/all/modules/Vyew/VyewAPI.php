<?php

/** REV 20110412  */
/**
 * class: VyewAPI Wrapper Class
 *
 * About the Vyew API:
 * The Vyew API allows trusted partners and the general public to perform various 
 * actions and queries on the Vyew system, such as create, duplicate and manipulate 
 * VyewBook / meeting rooms.
 * *Note: All example code below is given in PHP.*
 *
 * Getting Started:
 * - Register or Login to your Vyew Account (<http://vyew.com/login> or  <http://vyew.com/register>)
 * - Go here to get your API Key:  <http://vyew.com/api/public>
 * - Download the VyewAPI Class here: <http://xnet.vyew.com/docs/api/serverside>
 * - The above link also has a short tutorial
 *
 * Usage:
 * > 	$api_key="12344567";
 * > 	$api_secret="abcdefg";
 * > 	require_once('VyewAPI.php');
 * >	$vyew = new VyewAPI($api_key, $api_secret);
 * >
 * >	//--- then you can begin making calls, such as:
 * >	$vyew->create();
 * 
 * @package VyewAPI
 */
class VyewAPI {

	/**
	 * Debug logging? false=no, true=log to default error_log, or "mylogname.log" to write to custom log file
	 */
	var $debuglog=false;	

	/**	variable: useFakeData 
	 *  Used for testing.
	 * 	- {boolean} If true, all methods will return dummy data instead of calling the server. 
	 * 	- Hard code this variable in the the class source file to use.
	 */
	var $useFakeData=false;


    /**
     * --------------------------------------------------------------------------------------
     * Section: Constructor
     * --------------------------------------------------------------------------------------
     */
	/** func: VyewAPI
	 * Construct a VyewAPI object.
	 * 
	 * Parameters:
	 * 		apikey {string} - Your API key will be provided to you
	 * 		secret {string} - Your API secret will be provided to you
	 *
	*/

	function VyewAPI($apikey, $secret, $apiurl="https://vyew.com/api/v2.1/api.php"){
		$this->apikey = $apikey;
		$this->secret = $secret;
		$this->apiurl = $apiurl;
		if($this->debuglog){
			set_error_handler(array(&$this, "reportError"));
		}
	}
	
	function reportError ($code, $descr, $filename, $line)
	{
    	if (!($code & error_reporting())) return;
		echo "Error $code in line $line of $filename:\n";
		echo $descr."\n";
	}


    /**
     * --------------------------------------------------------------------------------------
     * Section: Public API Methods
     * --------------------------------------------------------------------------------------
     */

	/**
	 *	func: create
     *	Creates a new VyewBook/meeting room. If the owner is omitted, the user who is
     *	associated with the api key becomes the user. If owner is "guest," the VyewBook 
     *	becomes the owner of a new guest user. An example scenario in which "guest" 
     *	would be needed: if you had were a chat application provider, for example, 
     *	and wanted your users to be able to spawn meetings with other members on your 
     *	chat list. They could they create new rooms with their chat buddies, as guest users.
     *	Creates a new VyewBook/meeting room, or makes a copy of an existing VyewBook. 
     *
	 * Optional Parameters:
     *	name {string} - Name for the new VyewBook (default is "New VyewBook (n)", 
     *					where n is a number incremented)
     *	type {string}	- Type of user interface for this meeting room. 
	 * 						Options are (default is "vyew"): 
     *						- *vyew* : normal UI mode 
     *						- *revyew* : Slimmed down UI, shows less drawing tools
     *						- *deskshare* : jumps straight to Desktop Sharing tab
     *	owner {string} - 	For Corporate/Group accounts only. 
	 *						The email address, UserID, or "guest"
     *						Specifying anything other than guest, is only available to 
     *						members of a group. Users belonging to a group can assign 
     *						ownership of this book to other users in the group. 
	 *
     * Returns:
     *		{array} - With these items:
     *			- code {number} - status or error code. (1=OK, <1=Error)
     *			- message {string} - status or error message
     *			- VyewBookID {string} - the VyewBook ID of the newly created Vyewbook/meeting room
     *			- URL {string} - Entry URL of the new VyewBook/meeting room
     *
	 * Example:
	 * >	$result = $vyew->create('My New VyewBook'); 
	 * >	if($result[0]==1) echo "Here's your new VyewBook: ${result[3]}";
	 * >	 
	 * >	//--- making a copy of a vyewbook
	 * >	$result = $vyew->create('A Copy of a VyewBook');
	 * >	if($result[0]==1) $vyewAPI->import( TODO
	 * >	 
	 * >	//--- Example usage for creating guest-owned VyewBook/meeting room for inviting
	 * >	//--- multiple people into one room
	 * >	$someUsernames=array('bob','jill','jack');
	 * >	$roomName=$someUsernames[0]."s room";          
	 * >	$result = $vyew->create($roomName,null,"guest");
	 * >	if($result[0]==1){
	 * >	    // $result[3] would be like: "http://vyew.com/room/12345/67890"
	 * >	    // then, by adding on the ?username= option you could distribute the
	 * >	    // url to each person and spawn a new browser window for each
	 * >	    // eg.
	 * >	    // "http://vyew.com/room/12345/67890?username=bob"
	 * >	    // "http://vyew.com/room/12345/67890?username=jack"
	 * >	    // "http://vyew.com/room/12345/67890?username=jill"
	 * >	    foreach ($someUsernames as $uname){
	 * >	        $url = $result[3]."?username=$uname";
	 * >	 
	 * >	        //hypothetical function that would then distribute the url
	 * >	        //to each person
	 * >	        send_this_url_to_person($uname, $url);
	 * >	    }
	 * >	}
	 *
     * See Also:
     *		<Glossary> for more info about groups.
     *
	 */
	public function create($name=null, $type='vyew', $owner=null)
	{
		$args=array('name'=>$name, 'type'=>$type, 'owner'=>$owner);
		$res = $this->callAPI('create', $args);
		return $res;
	}


	/** func: export
	 * (NOT YET SUPPORTED)
	 * Export a VyewBook to another format, such as jpg, pdf, ppt, etc.
	 * 
	 * Parameters:
	 * 		vyewbook {mixed} - Book ID or Meeting ID
	 * 		type {string} - jpg|pdf|swf|ppt|vyew
	 *
	 * Optional Parameters:
	 *		page {mixed} - (optional) a page number, or range of pages (ie. "1-7")
	 *
     * Returns:
     *      {array} - With these items:
     *          - code {number} - status or error code. (1=OK, <1=Error)
     *          - message {string} - status or error message
     *          - VyewBookID {string} - the VyewBook ID of the newly created Vyewbook/meeting room
     *          - URL {string} - Entry URL of the new VyewBook/meeting room
	 *
	 * Example:
	 * >	$result = $vyew->export(123456, 'pdf');
	 * >	if($result[0]==1) echo "Download the pdf: ${result[2]}";
	 *
	 */
	function export($vyewbook, $type, $page=null)
	{
        $args=array('vyewbook'=>$vyewbook, 'type'=>$type, 'page'=>$page);
        $res = $this->callAPI('export', $args);
        return $res;		
	}



    /** func: invite
     * Invite a user (by email address) to a VyewBook.
     *
     * Parameters:
     *  vyewbook {mixed} - <book ID> or <Meeting ID> or an Array of <book ID> or <meetingID>
     *  email {mixed} - string:"email@address.com" or array of email addresses
     *  sendemail {number} - true/falsewhether or not to send an invite email to recipience (default is 1)
     *  options {array} - an associative array (dictionary) which may consist of:
     *      - *usertype* {string} - pres|reviewer|mod|view (default is "view" (See <UserType Codes>)
     *      - *timestart* {string} - (NOT YET SUPPORTED) <YYYY-MM-DD HH:MM +TZ>
     *          Example: "2008-09-01 13:00 -08"
     *          If TZ (Timezone is omitted, defaults to GMT)
     *      - *timeend* {string} - (NOT YET SUPPORTED) <YYYY-MM-DD HH:MM +TZ>
     *      - *duration* {number} - (NOT YET SUPPORTED) <minutes> Note: timeend and duration cannot be used together, only use one
     *      - *caninvite* {number} - true/false Whether or not invited user can invite others. Default "0".
     *      - *sendemail* {number} - true/false Whether an email should be sent to the invitee. Default "0".
     *      - *notifyjoin* {number} - true/false Whether the owner of the VyewBook should be notified when
     *          the invited user joins the VyewBook. Default  * "0".
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status
     *      - URL {string} - The invite URL to be used by the invited user to gain access to the VyewBook
     *
     * Examples:
     * (start code)
     * $vyew->invite(1234, 'kriley@calfootball.com');
     *
     * $inv_options = array('usertype'=>'mod',
     *             'timestart'=>'2009-01-01 10:00 -08',
     *             'duration'=>60),
     *             'notifyjoin=>1);
     *
     * $vyew->invite(1234, 'tedford@calfootball.com', 1, $inv_options);
     * $vyew->invite(array(1234,2345), array("tedford@calfootball.com", "jbest@calfootball.com"), 0, $inv_options);
     *
     * //--- another example
     * $result = $vyew->invite(1234, $to_email, true, array(
     *  'usertype'=>'mod'
     *  'timestart'=>'2010-01-01 10:00 -08',
     *  'duration'=>60,
     *  'caninvite'=>1,
     *  ));
     * (end code)
     *
     * See also:
     *  - <Error Codes>
     *  - <Glossary>
     */
    function invite ( $vyewbook, $email, $sendemail=true, $options=array() )
    {
	  if(is_array($email) && count($email) > 0){
		$emailstr = "";
		for($i=0; $i<count($email); $i++){
			$emailstr .= $email[$i] . ",";
		}
	  }else{
        	$emailstr = $email;
	  }

  	  if(is_array($vyewbook) && count($vyewbook) > 0){
		$bookstr = "";
		for($i=0; $i<count($vyewbook); $i++){
			$bookstr .= $vyewbook[$i] . ",";
		}
	  }else{
        	$bookstr = $vyewbook;
	  }
        $args=array( 'vyewbook'=>$bookstr, 'email'=>$emailstr, 'sendemail'=>$sendemail, 'options'=>$options);
        $res = $this->callAPI('invite', $args);
        return $res;
    }


	/** func: getbooks
	 * Retrieve IDs, names and other information of all VyewBooks owned by a particular user
	 * If no userID passed, will return books for owner of apikey
  	 *
	 * Optional Parameters:
	 *  user {mixed} - User ID or email address
	 *
	 * Returns:
	 *  {array} - With these items:
	 *      - code {number} - status or error code. (1=OK, <1=Error)
	 *      - message {string} - status
	 *      - books {array} - associative array of books with these keys:
	 *		  [id, name, meetingID, URL]
	 *
	 * Example:
	 * (start code)
	 * $result = $vyew->getBooks( 1234 );
	 * $result = $vyew->getBooks( "bwillis@diehard.com" );
	 *
	 *
	 * //--- if $result[0]==1, then $result[2] would look like this:
	 * array(
	 *   array(123, 'My VyewBook 1', ...),
	 *   array(124, 'My VyewBook 2', ...),
	 *   ...
	 * );
	 * (end code)
	 *
	 * See also:
	 *  - #Error-Codes
	 *
	 */
	function getbooks( $user=null )
	{
		$args=array( 'user'=>$user );
        	$res = $this->callAPI('getbooks', $args, 3);

		//json decode the book list
		if($res[2]) $res[2] = json_decode($res[2]);

        	return $res;
	}


	/** func: getlogs
	 * Retrieve access logs (entry and exit times) for a specific VyewBook.
	 *
	 * Parameters:
	 *  vyewbook {mixed} - VyewBook ID or Meeting ID
	 *  timeformat {string} - epoch|iso|string (default is epoch)
	 *      - *epoch*: seconds since Jan 1, 1970
	 *      - *iso*: '2008-11-20 23:12:01' (GMT)
	 *      - *string*: "Mon Sep 7, 2009 11:12pm" (GMT)
	 *  filterin {string} - a partial email address or full userID, can be an array for multiple filters
	 *  filterout {string} - a partial email address or full userID, can be an array for multiple filters
	 *
	 * Returns:
	 *  {array} - With these items:
	 *      - code {number} - status or error code. (1=OK, <1=Error)
	 *      - message {string} - status
	 *      - logData {array} - multi-dimensional array of log data containing:
	 *          [userEmail, entryTimeDate, exitTimeDate, timeDiffMins]</box>
	 *
	 * Example:
	 * >	$result = $vyew->getlogs( 1234 );
	 * >	$result = $vyew->getlogs( 1234, 'string' );
	 * >	$result = $vyew->getlogs( 1234, 'iso', 'tim');
	 * >
	 * >	//--- if $result[0]==1, then $result[2] would look like this:
	 * >	array(
	 * >		array('timjones@gmail.com', '01-20-2007 08:20:00', '01-20-2007 08:25:00', 5),
	 * >		array('timothyh@yahoo.com',  '01-30-2007 18:00:00', '01-20-2007 19:00:00', 60)
	 * >	);
	 *
	 */
	function getlogs( $vyewbook, $timeformat=null, $filterin=NULL, $filterout=NULL )
	{
		if($filterin != NULL){
			if(is_array($filterin)){
				$filterinstr = "";
				for($i=0; $i<count($filterin); $i++){
					if(strlen($filterin[$i]) > 0){
						$filterinstr .= $filterin[$i];
						if($i<count($filterin)-1) $filterinstr .= ",";
					}
				}
			}else if(strlen($filterin) > 0){
				$filterinstr = $filterin;
			}
		}
		if($filterout != NULL){
			if(is_array($filterout)){
				$filteroutstr = "";
				for($i=0; $i<count($filterout); $i++){
					if(strlen($filterout[$i]) > 0){
						$filteroutstr .= $filterout[$i];
						if($i<count($filterout)-1) $filteroutstr .= ",";
					}
				}
			}else if(strlen($filterout) > 0){
				$filteroutstr = $filterout;
			}
		}
		

		$args=array( 'vyewbook'=>$vyewbook, 'timeformat'=>$timeformat, 'filterin'=>$filterinstr, 'filterout'=>$filteroutstr );
        	$res = $this->callAPI('getlogs', $args, 3);
        	return $res;

	}






    /**
     * --------------------------------------------------------------------------------------
     * Section: File Import Methods (Public API)
	 *	Importing can take place a variety of ways:
	 *		- Import a document that is online at a specific URL
	 *		- Import pages from an existing VyewBook into a different VyewBook
	 *		- Import a document from your server, into a VyewBook on the Vyew server
	 *		- Import a document on your users client machine directly to the Vyew server
	 *	
	 *	The 3rd and 4th options require the file to be sent via HTTP POST to the Vyew Server.
	 *
     * 	An import via HTTP POST takes 3 stages:
     *      - Request an ImportURL to POST the file to [OPTIONAL, see note below]
     *      - POST the file (upload) to the ImportURL
     *      - Wait for the file to be converted and inserted to the VyewBook (on the Vyew Server)
     * During the 3rd stage, checkImportStatus allows you to
     * get the percentage completion status of the conversion.
     *
     * About getImportPostURL:
     * This function call is only required if you want to provide a progress update 
	 * (progress bar/percentage) to the user or if you want your user to be able to upload
	 * directly from their client machine (ie. through html, ajax, or flash).
     *
     * In these cases getImportPostURL() would be called prior to the upload. The unique
     * URL/Upload ID returned would then be used to pass on to the users browser for direct
     * upload, or called continually by a progress bar to check the status.
     *
     * --------------------------------------------------------------------------------------
     */


	/** func: import
 	 * Import documents, images, text or pages from another VyewBook into an existing VyewBook
 	 * 
	 * Parameters: 
	 *	vyewbook {mixed} - bookID or meetingID
	 *	source {string or array} - Can take various inputs:
	 *		- To send a file via POST: "post:<filename>"
	 *		- (NOT YET SUPPORTED) To import from another VyewBook: "<VyewBookID>:<PageNum>" or "<VyewBookID:<PageStart>-<PageEnd>"
	 *		- To import a document that is online: "http://domain.com/document.doc"
	 * dontWaitForConvert {bool} - If true, function will return after upload, but 
	 *						before conversion is complete on server. NOTE: This setting is
	 *						currently ignored, and it will always wait for file conversion
	 *						before returning.
	 *
	 * Returns: 
	 *      {array} - With these items:
	 *          - code {number} - status or error code. (1=OK, <1=Error)
	 *          - message {string} - status or error message
	 *          - URL {string} - <url of book just imported into>
	 *
	 * Example:
	 * >	//--- Create a new book and then import an online document into it.
	 * >	$result = $vyew->import(1234, 'http://domain.com/myresume.doc');
	 * >	 
	 * >	//--- Import a document from my server to an existing VyewBook by POSTING the file data multipart encoded
	 * >	$result = $vyew->import(1234, 'post:/path/to/mydocument.doc');
	 * >	 
	 * >	//--- Same as above, but dont wait for conversion to complete, before returning
	 * >	$result = $vyew->import(1234, 'post:/path/to/mydocument.doc', true);
	 * >	 
	 * >	//--- Import from my server, and alert me of conversion status
	 * >	$result = $vyew->import(1234, 'post:/path/to/mydocument.doc', false);
	 * >	 
	 *
	 */
	function import( $vyewbook, $source, $dontWaitForConvert=false )
	{

		//--- check if source is a post, and pre-fetch the post url
		if( substr(strtolower($source),0,5) == "post:" ){
			
			$res = $this->getImportPostURL($vyewbook);
			
			if($res[0]!=1) return $res;
			
			$importID = $res[3];
			$postURL = $res[2];

			$ch = curl_init( $postURL );
			$localFile = substr($source,5);

			error_log("import: $postURL, $importID, $localFile");
			$postData = array('Filedata'=>"@$localFile");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			$curlResult = curl_exec($ch);
			curl_close($ch);

			if(!$curlResult){
				return array(0, "Upload failed");
			}else{
				$parts = explode("[mid]", $curlResult);
				$mid = $parts[1];
			}

			$cRes = $this->checkImportStatus($importID);
			$cRes[] = $mid;
			return $cRes;
		}

		$args=array('vyewbook'=>$vyewbook, 'source'=>$source);
        $res = $this->callAPI('import', $args);
        return $res;
	}



	/** func: getImportPostURL
	 * Retrieves a unique URL for which to post a file to a specific VyewBook.
     * This function is only required if you want to show your users a progress bar during
  	 * the upload/import process, or if you want the client to be able to upload
	 * directly to a VyewBook from their client machine. See the "File Import Methods" section.
  	 * 
  	 * 
	 * Parameters:
	 * 	vyewbook {mixed} - Book ID or Meeting ID
	 * 	
	 * Returns:
	 *	{array} - With these items:
     *  	- code {number} - status or error code. (1=OK, <1=Error)
     *   	- message {string} - status or error message
	 *		- importURL {string} - the url to POST a file to for importing/converting to the VyewBook
	 *		- importID {string} - unique Import File ID (for checking its status later, if needed)
	 *
	 * Example:
	 * >	//--- Allow a client to POST directly to thew Vyew Server
	 * >	$result = $vyew->getImportPostURL( 1234 );
	 * >	$postURL = $result[2];
	 * >	echo "<form method='post' action='$postURL'>";
	 * >	echo "<input type='file' size=50>";
	 * >	echo "</form>";
	 */
	private function getImportPostURL( $vyewbook )
	{
	    	$args=array( 'vyewbook'=>$vyewbook );
		$res = $this->callAPI('getImportPostURL', $args);
		return $res;
	}


	/** func: checkImportStatus
	 * An import via POST takes 3 stages: 
	 *		- Request a URL to POST to
	 *		- POST the file to the Url (upload)
	 *		- The file is then converted on the Vyew server and inserted into the targe VyewBook
	 * During the 3rd stage, is what this function checkImportStatus applies to, so you can
	 * get the percentage completion status of the conversion.
	 *
	 * Parameters:
	 *	importID {string} - the import file ID which was returned with getImportPostURL()
	 *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
	 *		- percent {number} - a number between 0 and 100 of completion status
	 *
	 */
	function checkImportStatus( $importID )
	{
	    	$args=array( 'importID'=>$importID);
		$res =  $this->callAPI('checkImportStatus', $args);
		return $res;
	}







    /**
     * --------------------------------------------------------------------------------------
     * Section: Corporate/Enterprise Methods
	 * 		The following methods are available to corporate/enterprise customers only.
       *          Email bizdev@vyew.com for method availability.
     * --------------------------------------------------------------------------------------
     */

    /** func: addGroup
     * Adds a new group.
     *
     * Parameters:
     *  name {string} - name of group
     *  parent {mixed} - ID or name of parent group (default is root group)
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
     *      - newGroupID {number} - the GroupID fro the newly created group
     *
     */
    function addGroup( $name, $parent=null )
    {
    	$args=array( 'name'=>$name, 'parent'=>$parent );
        $res = $this->callAPI('addGroup', $args);
        return $res;
    }




    /** func: addUserToGroup
     * Adds a current user to a group.
     *
     * Parameters:
     *  email {string} - email address
     *  group {mixed} - groupID or group name
     *  userlevel {mixed} - userleveltypeID or name of a userlevel:
	 *						The current default names are:
	 *						- 'User' - User can only view their own books
	 *						- 'Auditor' - User can view their own books AND books belonging to users in the same group (they will enter those
	 *                                   books with a "Viewer" usertype
	 *						- 'Group Admin' - User can view their own books AND books belonging to users in the same group (they will enter those
	 *                                   books with a "Moderator" usertype
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
     *
     */
    function addUserToGroup( $email, $group, $userlevel )
    {
    	$args=array( 'email'=>$email, 'group'=>$group, 'userlevel'=>$userlevel );
        $res = $this->callAPI('addUserToGroup', $args);
        return $res;
    }


    /** func: moveUserToGroup
     * Moves user from one group to another
     *
     * Parameters:
     *  email {string} - email address
     *  to_group {mixed} - groupID or group name to where she should be moved
     *  from_group {mixed} - groupID or group name from where she should be moved
     *  userlevel {mixed} - userleveltypeID or name of a userlevel:
	 *						The current default names are:
	 *						- 'User' - User can only view their own books
	 *						- 'Auditor' - User can view their own books AND books belonging to users in the same group (they will enter those
	 *                                   books with a "Viewer" usertype
	 *						- 'Group Admin' - User can view their own books AND books belonging to users in the same group (they will enter those
	 *                                   books with a "Moderator" usertype
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
     *
     */
    function moveUserToGroup( $email, $to_group, $from_group, $userlevel )
    {
    	$args=array( 'email'=>$email, 'to_group'=>$to_group, 'from_group'=>$from_group, 'userlevel'=>$userlevel );
        $res = $this->callAPI('moveUserToGroup', $args);
        return $res;
    }






    /** func: createUser
     * Creates a new user within your enterprise/group.
     *
     * Parameters:
     *  email {string} - The users email address
     *  pass {string} - a password that meets password requirements
     *  fname {string} - First name
     *  lname {string} - Last name
	 *	emailUserPasswordLink {bool} - if TRUE $pass will be disregarded and user
	 *			will be emailed a link to set their password (default: FALSE)
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
     *          - newUserID {number} - a unique identifier for the newly created user
     *
     * Notes:
     * If you omit the 'pass' parameter, or set it to '' (nothing), then this user
     * will be unable to log in from the vyew.com website. The only way he'll be
     * able to log in is if you call the <login> API on your end.
     *
     * Example:
     * >    $vyew->createUser('bart.simpson@duff.com', 'sTron6PassW0rd!', 'Bart', 'Simpson');
     * >    //--- create a new user for the enterprise only (has no password)
     * >    $vyew->createUser('jseinfeld@microsoft.com', '', 'Jerry', 'Seinfeld');
     */
    function createUser( $email, $pass, $fname, $lname, $emailUserPasswordLink=FALSE )
    {
    	$args=array( 'email'=>$email, 'pass'=>$pass, 'fname'=>$fname, 'lname'=>$lname, 'emailUserPasswordLink'=>$emailUserPasswordLink );
        $res = $this->callAPI('createUser', $args);
        return $res;
    }



/** func: upgradeuser
     * Upgrade a group user to a monthly Pro or Plus account (this function is not available for all groups).
     *
     * Parameters:
     *  user {mixed} - The user ID or email of the user to upgrade
     *  type {mixed} - The type of account to upgrade to ("pro" or "plus")
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status
     *
     * Example:
     *
     */
    function upgradeuser($user, $type)
    {
  	  $args=array( 'user'=>$user, 'type'=>$type );
        $res = $this->callAPI('upgradeuser', $args);

        return $res;
    }



/** func: deleteuser
     * Delete a user from the group and the entire Vyew system.  Any pending charges belonging to the user will be cancelled.
     *
     * Parameters:
     *  user {mixed} - The user ID or email of the user to delete
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status
     *
     * Example:
     *
     */
    function deleteuser($user)
    {
  	  $args=array( 'user'=>$user );
        $res = $this->callAPI('deleteuser', $args);

        return $res;
    }


/** func: getaccounttype
     * Get the account type of a user.
     *
     * Parameters:
     *  user {mixed} - The user ID or email of the user
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status
     *
     * Example:
     *
     */
    function getaccounttype($user)
    {
  	  $args=array( 'user'=>$user );
        $res = $this->callAPI('getaccounttype', $args);

        return $res;
    }


/** func: downgradeuser
     * Downgrade a group user to a Free account (this function is not available for all groups).
     *
     * Parameters:
     *  user {mixed} - The user ID or email of the user to downgrade
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status
     *
     * Example:
     *
     */
    function downgradeuser($user)
    {
  	  $args=array( 'user'=>$user );
        $res = $this->callAPI('downgradeuser', $args);

        return $res;
    }



/** func: getusers
     * Gets a list of all users belonging to the group.
     *
     * Parameters:
     *  group {mixed} - Group ID or Group Name (Optional: if not provided, will return all users belonging to the root group)
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status
     *      - users {array} - associative array of users with these keys:
     *		  [email, userID]
     *
     * Example:
     *
     */
    function getusers($group=NULL)
    {
		$args=array( 'group'=>$group );
		$res = $this->callAPI('getusers', $args, 3);

        //json decode the book list
		if($res[2]) $res[2] = json_decode($res[2]);

		return $res;
    }



    /** func: delGroup
     * Deletes a group.
     *
     * Parameters:
     *  group {mixed} - groupID or name of group
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
     *
     * Example:
     *
     */
    function delGroup( $group )
    {
    	$args=array( 'group'=>$group );
        $res = $this->callAPI('delGroup', $args);
        return $res;
    }

	/** func: hasPermission
     * Checks if a user has granted access to the given API functions
	 * associated with your API key.
     *
     * Parameters:
     *  email {string} - email of user to check
	 *  perms (string) - comma-delimited string of functions (i.e. "create,invite") to check
	 *                   VALID ARGS: "create", "import", "invite", "getbooks", "getlogs", "getImportPostURL", "checkImportStatus"
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, 0=Does NOT have access to one or more of the provided functions, <0=Error)
     *      - message {string} - status or error message
     *
     * Example:
     *
     */
    function hasPermission( $email, $perms )
    {
    	$args=array( 'email'=>$email, 'perms'=>$perms );
        $res = $this->callAPI('hasPermission', $args);
        return $res;
    }
	
	/** func: requestPermission
     * Retrieve a URL to provide to a user to allow usage of the given api functions
	 * associated with your API key.
     *
     * Parameters:
     *  email {string} - email of user who wish to have access to
	 *  perms (string) - comma-delimited string of functions (i.e. "create,invite") to invoke on that user
	 *                   VALID ARGS: "create", "import", "invite", "getbooks", "getlogs", "getImportPostURL", "checkImportStatus"
	 *  returl (string) - URL to return to after the user clicks ALLOW or DENY
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
     *
     * Example:
     *
     */
    function requestPermission( $email, $perms, $returl )
    {
    	$args=array( 'email'=>$email, 'perms'=>$perms, 'returl'=>$returl );
        $res = $this->callAPI('requestPermission', $args);
        return $res;
    }
	

    /** func: delBook
     * Deletes a book belonging to a user in the group.
     *
     * Parameters:
     *  book {number} - bookID
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
     *
     * Example:
     *
     */
    function delBook( $book )
    {
    	$args=array( 'book'=>$book );
        $res = $this->callAPI('delBook', $args);
        return $res;
    }



    /** func: delUserFromGroup
     * Deletes a user from a group.
     *
     * Parameters:
     *  email {string} - email address
     *  group {mixed} - groupID or group name
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
     *
     * Example:
     *
     */
    function delUserFromGroup( $email, $group )
    {
    	$args=array( 'email'=>$email, 'group'=>$group );
        $res = $this->callAPI('delUserFromGroup', $args);
        return $res;
    }





    /** func: login
     * Logs a user in to the Vyew system.
     *
     * Parameters:
     *  email {string} - email address
     *  pw {string} - password
     *  book {string} - bookID to where the user should be sent after logging in
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
     *      - URL {string} - the URL to forward the user to
     *
     * Example:
     * >    $result = vyewAPI('login',array('email'=>'hsimpson@duffbeer.com', 'pw'=>'dohhhh'));
     * >    if($result[0]==1) header("Location: ${result[2]}");
     * >    else //show error in $result[1];
     * >
     * >    // Log user in, and forward them to a certain book
     * >    vyewAPI('login',array('email'=>'hsimpson@duffbeer.com', 'pw'=>'dohh44!', 'book'=>'123_987'));
     * >    vyewAPI('login',array('email'=>'hsimpson@duffbeer.com', 'pw'=>'dohh44!', 'book'=>'About donuts'));
     *
     */
    function login( $email, $pw, $book=null )
    {
    	$args=array( 'email'=>$email, 'pw'=>$pw, 'book'=>$book );
        $res = $this->callAPI('login', $args);
        return $res;
    }





    /** func: logout
     * Logs the current user out of the Vyew system.
     *
     * Parameters:
     *  returnURL {string} - The URL to send the user after logging out
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
     *          - URL {string} - the user must visit this URL in order to log him out.
     *                      If returnURL was specified, he will be redirected there immediately,
     *                      otherwise he will see a generic "You have been logged out of Vyew" page.
     *
     */
    function logout( $returnURL=null )
    {
    	$args=array( 'returnURL'=>$returnURL );
        $res = $this->callAPI('logout', $args);
        return $res;
    }



    /** func: delUserFromAllGroups
     * Deletes a user completely from the group and all sub-groups, but their
     * public Vyew account will still remain
     *
     * Parameters:
     *  email {string} - Email address of user to remove
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
     *
     * Example:
	 * >	$vyew->delUserFromAllGroups('bart@simpsons.com');
     */
    function delUserFromAllGroups( $email )
    {
    	$args=array( 'email'=>$email );
        $res = $this->callAPI('delUserFromAllGroups', $args);
        return $res;
    }


	/** func: updateUserInfo
     * Updates a users email, screenname and/or account password
     *
     * Parameters:
     *  email {string} - email address of the user to edit
     *  propsObj {array} - associative array (dictionary) of user properties and their values.
     *                      Possible property names are:
     *                      - *new_email* - new email of user
     *                      - *pswd* - new password of user
     *                      - *screenname* - new screen name of user
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
     *
     * Example:
	 * >	$propsObj=array('new_email'=>"newemail@domain.com", 'pswd'=>"new_password", 'screenname'=>"John Doe");
	 * >	$vyew->updateUserInfo('oldemail@domain.com', $propsObj);
     */
    function updateUserInfo( $email, $propsObj )
    {
    	$args=array( 'email'=>$email, 'propsObj'=>$propsObj );
        $res = $this->callAPI('updateUserInfo', $args);
        return $res;
    }
	

    /** func: setLimits
     * Sets a users limits, such as book limit, the number of book participants allowed, etc.
     *
     * Parameters:
     *  email {string} - email address of the user to edit
     *  limitsObj {array} - associative array (dictionary) of limitID names, and their values.
     *                      Possible limitID names are:
     *                      - *book_limit* - How many books the user is allowed to have
     *                      - *open_book_limit* - How many books can be open with 2 or more people in it
     *                      - *book_page_limit* - How many pages per book is allowed
     *                      - *part_limit* - Participant limit - how many people allowed in a book at one time
     *                      - *has_desktop_share* - User is allowed to share their desktop
     *                      - *has_custom_skin* - User is allowed to change the interface skin colors
     *                      - *has_video* - User is allowed to display their webcam in their meetings
     *                      - *has_audio* - User is allowed to use the built-in VOIP audio in their meetings
     *                      - *has_conf_call* - User can display the conference call number in their meetings
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
     *
     * Example:
	 * >	$someLimits=array('book_limit'=>5, 'has_video'=>false, 'has_conf_call'=>true);
	 * >	$vyew->setLimits('hsimpson@duff.com', $someLimits);
     */
    function setLimits( $email, $limitsObj )
    {
    	$args=array( 'email'=>$email, 'limitsObj'=>$limitsObj );
        $res = $this->callAPI('setlimits', $args);
        return $res;
    }

	
	/** func: getLimits
     * Gets the limits for a user, such as book limit, the number of book participants allowed, etc.
     *
     * Parameters:
     *  email {string} - email address of the user to get the limits for
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
	 *      - limits {array} - associative array of limits with these keys:
	 *                      - *book_limit* - How many books the user is allowed to have
     *                      - *open_book_limit* - How many books can be open with 2 or more people in it
     *                      - *book_page_limit* - How many pages per book is allowed
     *                      - *part_limit* - Participant limit - how many people allowed in a book at one time
	 *                      - *has_custom_id* - User has the custom url option
     *                      - *has_desktop_share* - User is allowed to share their desktop
     *                      - *has_custom_skin* - User is allowed to change the interface skin colors
	 *                      - *has_custom_ad* - User has custom room banner
     *                      - *has_video* - User is allowed to display their webcam in their meetings
     *                      - *has_audio* - User is allowed to use the built-in VOIP audio in their meetings
     *                      - *has_conf_call* - User can display the conference call number in their meetings
     *
     * Example:
	 * >	$vyew->getLimits('hsimpson@duff.com');
     */
    function getLimits( $email )
    {
    	$args=array( 'email'=>$email );
        $res = $this->callAPI('getlimits', $args);
		
		//json decode the limits list
		if($res[2]) $res[2] = json_decode($res[2]);
        return $res;
    }


    /** func:getGroupLogs
     * Retrieve logs of group activity such as adding/deleting users to groups
     *
     * Parameters:
     *  timestamp {int} - Epoch unix time. Only logs AFTER this time will be retrieved
     *  limit {int} - How many logs lines to retrieve
     *
     * Returns:
     *  {array} - With these items:
     *      - code {number} - status or error code. (1=OK, <1=Error)
     *      - message {string} - status or error message
     *      - data {string} - tab delim text of, for example:
     *
     *      <TIMESTAMP> <ACTION> <DATA> <DATA> <DATA> <DATA>
     *      0000000000 del_user <email>
     *      0000000000 update_user <old_email> <new_email> <new_pswd>
     *      0000000000 group_add_user <groupID> <useremail>
     *      0000000000 group_del_user <groupID> <useremail>
     *      0000000000 group_add_group <groupID_parent> <groupID_child>
     *      0000000000 group_del_group <groupID_parent> <groupID_child>
     *      
     *      Sample data:
     *      0000000000 del_user  tim@email.com
     *      0000000000 update_user  tim@email.com tim_new@email.com new_passwrd
     *      0000000000 group_add_user 12345  john.doe@email.com
     *      0000000000 group_del_user 12345  john.doe@email.com
     *      0000000000 group_add_group 12345  98765
     *      0000000000 group_del_group 98765  12345
     */
    function getGroupLogs($timestamp=0,$limit=100)
    {
        $args=array( 'timestamp'=>$timestamp, 'limit'=>$limit );
        $res = $this->callAPI('getGroupLogs', $args);
        return $res;
    }



    /**
     * --------------------------------------------------------------------------------------
     * PRIVATE METHODS
     * --------------------------------------------------------------------------------------
     */

	/**
	 * Makes a remote call to Vyew API
	 * @param cmd {string} - command string ID
	 * @argsArray {array} - associative array of argument names and argument values
	 * @explode_count {integer} - maximum length of the array to explode
	 * @return {array} - array of responses
	 */
	private function callAPI( $cmd, $argsArray=array(), $explode_count=NULL )
	{
		$this->trace("callAPI:$cmd");
		$this->trace($argsArray);

		$epoch_time=time();

		//--- assemble argument array into string
		$query = "cmd=$cmd";
		foreach ($argsArray as $argName=>$argValue) {
			if(is_array($argValue)){
				foreach ($argValue as $argName2=>$argValue2) {
					$query .= "&" . $argName2 . "=" . urlencode($argValue2);
				}				
			}else{
			   $query .= "&" . $argName . "=" . urlencode($argValue);
			}
		}
		$query .= "&key=" . $this->apikey . "&time=$epoch_time";

		//--- make md5 hash of the query + secret string
		$md5 = md5($query . $this->secret);
		$url = $this->apiurl."?$query&md5=$md5";

		//--- simple GET request, put its contents into $response
		$response = file_get_contents($url);

		$this->trace("callAPI RESPONSE:$cmd");
        $this->trace($response);

		//--- convert "|" (pipe) delimited string to array
		$responseArray = $this->processVyewResult($response, $explode_count);

        $this->trace($responseArray);

		return $responseArray;
	}




	/** 
	 * Processes result text which is normally in "|" pipe delimited format
  	 * into an array
	 * @param resultTxt {string}
	 * @explode_count {integer} - maximum length of the array to explode
	 * @return {array}
	 */
	private function processVyewResult($resultTxt, $explode_count=NULL)
	{
		if($explode_count != NULL){
			$v=explode("|",$resultTxt,$explode_count);
		}else{
			$v=explode("|",$resultTxt);
		}
		foreach($v as $key=>$val){
			$v[$key]=trim($val);
		}
		$v[0] = intval($v[0]);
		return $v;
	}

	

	/**
	 * Debugging use only: will export to /var/log/php.log (or default log file), if $this->debuglog not set
	 */
	private function trace($var,$txt=false)
	{
		if(!$this->debuglog) return;
		if(is_string($this->debuglog)){
			if($txt) error_log($txt."\n",3,$this->debuglog);
            error_log(print_r($var)."\n",3,$this->debuglog);
		}else{
		if($txt) error_log($txt);
		error_log(print_r($var));
	}
	}


	/**
	 * --------------------------------------------------------------------------------------
	 * Section: Addtional Information
	 * --------------------------------------------------------------------------------------
	 *
	 * About: Glossary
	 * Book ID, Meeting ID, Base Meeting ID - These ID's can be found by opening a VyewBook 
	 * 		and looking in the browser title bar. 
	 *		Title Bar Example: "Vyew.com : myName/MyVyewBook (1234_5678)"
	 *		The *Meeting ID* is 1234_5678, 
	 *		the *Base Meeting ID* is 1234, 
	 *		and the *Book ID* is 5678. 
	 *		In other words, a Meeting ID is made up of Base Meeting ID + Book ID.
	 * VyewBook - The term VyewBook can be used interchangeably with "meeting room." 
	 *		It is the collection of pages of content plus collaboration/conferencing/drawings tools. 
	 *	
	 *
	 * About: Error Codes
     * 1 	- Success
     * -1 	- Some arguments have invalid/missing data
     * -3 	- User does not exist
     * -5 	- User already exists
     * -6 	- User is not member of specified group
     * -7 	- VyewBook/meeting/subject does not exist
     * -8 	- old ID for NSTEP, should now be -7 (subject ID doesn't exist)
     * -9 	- VyewBook not owned by group (formerly:book not owned by nstep)
     * -10 	- Bad group ID or ambiguous name
     * -20 	- Command requested has reached max usage for given API Key
	 *
	 *
	 * About: UserType Codes
	 * Viewer (view) - A viewer can only view and chat in a meeting room. She cannot draw, or modify data. 
	 *				   If the room is in SYNC mode, she cannot navigate pages either.
	 *				   If the room is in UNSYNC mode, she CAN navigate pages.
	 * Reviewer (reviewer) - A Reviewer has limited drawing rights and cannot delete any data.
	 * Collaborator (collab) - A collaborator has full drawing, deleting and inviting rights.
	 * Moderator (mod) - A moderator has full rights to change other users' permissions
	 *
	 */


}//eo class


?>

