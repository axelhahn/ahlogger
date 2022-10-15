# All Methods

<!-- BEGIN DOC-COMMENT H3 ../logger.class.php -->
### `class logger`

Debug logging during a client request. So you can measure any action find bottlenecks in your code.

Source: https://github.com/axelhahn/ahlogger

USAGE:<br> (1) Trigger a message with add() to add a marker<br> (2) The render() method lists all items in a table with time since start     and the delta to the last message. <br>

### `public function __construct(\$sInitMessage = "Logger was initialized.")`

constuctor
**Parameters:**

* `$sInitMessage` — `string` — init message

**Return:**

  boolean

### `public function add(\$sMessage, $sLevel = "info")`

add a logging message
**Parameters:**

* `$sMessage` — `type` — %s
* `$sLevel` — `type` — %s

**Return:**

  boolean

### `public function enableDebug(\$bEnable=true)`

enable / disable debugging
**Parameters:**

* `$bEnable` — `type` — %s

**Return:**

  type

### `public function enableDebugByIp(\$aIpArray)`

enable client debugging by a given array of allowed ip addresses
**Parameters:**

* `$aIpArray` — `array` — list of ip addresses in a flat array

**Return:**

  boolean

### `protected function _getBar(\$iVal, $iMax)`

get html code for a progressbar with divs
**Parameters:**

* `$iVal` — `int|float` — value between 0..max value
* `$iMax` — `int|float` — max value

**Return:**

  {string}

### `public function render()`

render output of all logging messages

### `public function renderCli()`

render output of all logging messages for cli output
**Return:**

  string

<!-- END DOC-COMMENT -->