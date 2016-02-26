-------------------------------------------------------------------------------

Debug logging during a client request.
With this you can
- measure any action 
- find bottlenecks in your code.

Licence GPL 3.0

author: Axel Hahn
http://www.axel-hahn.de

-------------------------------------------------------------------------------
 
USAGE:
(1) Trigger a message with add() to add a marker
(2) The render() method lists all items in a table with time since start
    and the delta to the last message. 
    An additional div on top right shows the execution time total
    and the time of the longest action (with a link to it).


See the example.php.

You don't need to write any print_r and var_dump anymore and remove it
afterwards. Add _GET and _POST, and put a $oLog->add() at start and end
of any action you want to measure.

For production do not execute render method. Wrap it with a flag:

	if ($bIsDevelopEnvironment){
		echo $oLog->render();
	}


If you init the logger globally you can put requests into your class
example:

    /**
     * add a log messsage
     * @global object $oLog
     * @param  string $sMessage  messeage text
     * @param  string $sLevel    warnlevel of the given message
     * @return bool
     */
    private function log($sMessage, $sLevel = "info") {
        global $oLog;
        if (!$oLog ||! is_object($oLog) || !method_exists($oLog, "add")){
            return false;
        }
        return $oLog->add("class " . __CLASS__ . " - " . $sMessage, $sLevel);
    }
