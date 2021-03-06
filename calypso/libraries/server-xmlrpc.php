<?php       

	require_once ('roster.inc');
	require_once ('XML/RPC/Server.php');

	$xmlrpc_methods = array (
		'blogger.newPost' => array ('function' => 'blogger_newPost'),
		//'blogger.editPost' => array ('function' => 'blogger_editPost'),
		//'blogger.getUsersBlogs' => array ('function' => 'blogger_getUsersBlogs'),
		//'blogger.getUserInfo' => array ('function' => 'blogger_getUserInfo'),
		//'blogger.getTemplate' => array ('function' => 'blogger_getTemplate'),
		//'blogger.blogger.setTemplate' => array ('function' => 'blogger_blogger.setTemplate')
		//'metaWeblog.editPost' => array ('function' => 'metaWeblog_editPost'),
		//'metaWeblog.getPost' => array ('function' => 'metaWeblog_getPost'),
		//'metaWeblog.newMediaObject' => array ('function' => 'metaWeblog_newMediaObject'),
		//'metaWeblog.getCategories' => array ('function' => 'metaWeblog_getCategories'),
		//'metaWeblog.getRecentPosts' => array ('function' => 'metaWeblog_getRecentPosts'),
		'metaWeblog.newPost' => array ('function' => 'metaWeblog_newPost')
	);

	/**
	 * Creating a new object of this type automatically
	 * Parsed the XML-RPC request and handles the
	 * response.
	 */
	$xmlrpc_server = new XML_RPC_Server ($xmlrpc_methods);

	// Bye bye
	exit;

	function metaWeblog_newPost ($params) 
	{
		global $smarty;

		$userid = $params[1];
		$userid = $userid->scalarval ();

		$password = $params[2];
		$password = $password->scalarval ();
		$login = new Login ($userid, password);

		if ($login->IsValid ()) 
		{
			$title = $params[3];
			$title = $title->structmem ('title');
			$title = $title->scalarval ();

			$content = $params[3];
			$content = $content->structmem ('description');
			$content = $content->scalarval ();

			$status = $status[4];
			$status = $status->scalarval ();

			calypso_create_entry ($userid, $title, $content, array (), $status);
			$entryid = $smarty->get_template_vars ('entryid');

			return new XML_RPC_Response (
				new XML_RPC_Value ($entryid, "int")
			);
  		} else {
			return new XML_RPC_Response (0,
				"301", 
				"The username and password you entered " .
				"was not accepted. Please try again."
			);	
		}
	}

	function blogger_newPost ($params) 
	{
		global $smarty;

		$blogid = $params[1];
		$blogid = $blogid->scalarval ();

		$userid = $params[2];
		$userid = $userid->scalarval ();

		$password = $params[3];
		$password = $password->scalarval ();
		$login = new Login ($userid, password);

		if ($login->IsValid ()) 
		{
			$title = date ('r');

			$content = $params[4];
			$content = $content->structmem ('description');
			$content = $content->scalarval ();

			$status = $status[5];
			$status = $status->scalarval ();

			calypso_create_entry ($userid, $title, $content, array (), $status);
			$entryid = $smarty->get_template_vars ('entryid');

			return new XML_RPC_Response (
				new XML_RPC_Value ($entryid, "int")
			);
  		} else {
			return new XML_RPC_Response (0,
				"301", 
				"The username and password you entered " .
				"was not accepted. Please try again."
			);	
		}
	}

	function gettime($p) 
	{
	        // Get the first parameter
	        $format = $p->getParam (0);

	        // Look to see if it was supplied
	        if (empty ($format)) {
	                // In case it wasn't, this is our default.
	                $format = 'Y-m-d h:i:s';
	        } else {
	                // Get the value of the parameter.
	                $format = $format->scalarval ();
	        }

	        // Build a return value
	        $retval = date ($format, time ());

	        // Return it as a response
	        return new XML_RPC_Response (new XML_RPC_Value ($retval, "string")); 
	}
?>
